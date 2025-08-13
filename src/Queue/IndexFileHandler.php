<?php

namespace Litzinger\DexterCore\Queue;

use Litzinger\DexterCore\Contracts\ConfigInterface;
use Litzinger\DexterCore\Contracts\IndexableRepositoryInterface;
use Litzinger\DexterCore\Contracts\LoggerInterface;
use Litzinger\DexterCore\Service\Indexer\IndexFileCommand;
use Litzinger\DexterCore\Service\Indexer\IndexProvider;

class IndexFileHandler
{
    private IndexableRepositoryInterface $repository;
    private ConfigInterface $config;
    private IndexProvider $indexer;
    private LoggerInterface $logger;
    private array $pipelines;

    public function __construct(
        IndexableRepositoryInterface $repository,
        ConfigInterface $config,
        IndexProvider $indexer,
        LoggerInterface $logger,
        array $pipelines
    ) {
        $this->repository = $repository;
        $this->config = $config;
        $this->indexer = $indexer;
        $this->logger = $logger;
        $this->pipelines = $pipelines;
    }

    public function handle(int $fileId): bool
    {
        try {
            $indexable = $this->repository->find($fileId);

            if (!$indexable) {
                return true; // No file, so we're done.
            }

            // In EE, we would fire a hook here to allow config overrides.
            // The logic for that will be in the EE-specific job.

            $indices = $this->config->get('indices');
            // This is still a bit coupled to EE, but we need a way to get the index name.
            // The alternative is to pass the index name in the job, but that's a bigger refactor.
            $indexName = $indices['upload_dir_' . $indexable->get('upload_location_id')] ?? null;

            if (!$indexName) {
                $this->logger->debug(sprintf('No index name found for upload location %d', $indexable->get('upload_location_id')));
                return false;
            }

            $command = new IndexFileCommand(
                $indexName,
                $indexable,
                $this->config,
                $this->pipelines
            );

            $response = $this->indexer->single($command, false);

            if (!$response->isSuccess()) {
                $this->logger->debug('[Dexter] ' . json_encode($response->getErrors(), true));
                return false;
            }
        } catch (\Exception $e) {
            $this->logger->debug('[Dexter] Error indexing file: ' . $e->getMessage());
            return false;
        }

        return true;
    }
}

<?php

namespace Litzinger\DexterCore\Queue;

use Litzinger\DexterCore\Contracts\ConfigInterface;
use Litzinger\DexterCore\Contracts\IndexableRepositoryInterface;
use Litzinger\DexterCore\Contracts\LoggerInterface;
use Litzinger\DexterCore\Service\Indexer\IndexEntryCommand;
use Litzinger\DexterCore\Service\Indexer\IndexProvider;

class IndexEntryHandler
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

    public function handle(int $entryId): bool
    {
        try {
            $indexable = $this->repository->find($entryId);

            if (!$indexable) {
                return true; // No entry, so we're done.
            }

            $indices = $this->config->get('indices');
            $indexName = $indices['channel_' . $indexable->get('channel_id')] ?? null;

            if (!$indexName) {
                $this->logger->debug(sprintf('No index name found for channel %d', $indexable->get('channel_id')));
                return false;
            }

            $command = new IndexEntryCommand(
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
            $this->logger->debug('[Dexter] Error indexing entry: ' . $e->getMessage());
            return false;
        }

        return true;
    }
}

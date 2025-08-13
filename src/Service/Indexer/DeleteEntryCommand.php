<?php

namespace Litzinger\DexterCore\Service\Indexer;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;

class DeleteEntryCommand implements DeleteCommand
{
    public function __construct(
        public string $indexName,
        public IndexableInterface $indexable,
        public ConfigInterface $config,
    ) {
    }

    public function execute(): bool
    {
        return true;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getId(): int
    {
        return $this->indexable->getId();
    }

    public function getUniqueId(): string
    {
        return 'entry_' . $this->indexable->getId();
    }

    public function getQueueJobName(): string
    {
        return 'delete-entry';
    }
}

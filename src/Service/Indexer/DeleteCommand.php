<?php

namespace Litzinger\DexterCore\Service\Indexer;

use Litzinger\DexterCore\Contracts\QueueableCommand;

interface DeleteCommand extends QueueableCommand
{
    public function execute(): bool;

    public function getId(): int;

    public function getUniqueId(): string;

    public function getIndexName(): string;
}

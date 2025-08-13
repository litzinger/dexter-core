<?php

namespace BoldMinded\Dexter\Shared\Service\Indexer;

use BoldMinded\Dexter\Shared\Contracts\QueueableCommand;

interface IndexCommand extends QueueableCommand
{
    public function execute(): array;

    public function getIndexName(): string;

    public function getValues(): array;

    public function getId(): int;

    public function getUniqueId(): string;
}

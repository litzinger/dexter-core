<?php

namespace BoldMinded\Dexter\Shared\Service\Indexer;

use BoldMinded\Dexter\Shared\Contracts\QueueableCommand;

interface DeleteCommand extends QueueableCommand
{
    public function execute(): bool;

    public function getId(): int;

    public function getUniqueId(): string;

    public function getIndexName(): string;
}

<?php

namespace BoldMinded\Dexter\Shared\Service\Indexer;

interface ReIndexCommands
{
    public function getCommandCollection(): IndexCommandCollection;

    public function getAlerts(): array;

    public function getIndexName(): string;
}

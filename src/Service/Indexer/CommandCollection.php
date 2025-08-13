<?php

namespace BoldMinded\Dexter\Shared\Service\Indexer;

interface CommandCollection
{
    public function getCommands(): array;

    public function count(): int;
}

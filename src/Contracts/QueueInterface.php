<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface QueueInterface
{
    public function push(string $job, array $data): void;
}

<?php

namespace DexterCore\Contracts;

interface QueueInterface
{
    public function push(string $job, array $data): void;
}

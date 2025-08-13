<?php

namespace DexterCore\Contracts;

interface QueueableCommand
{
    public function getQueueJobName(): string;
}

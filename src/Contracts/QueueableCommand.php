<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface QueueableCommand
{
    public function getQueueJobName(): string;
}

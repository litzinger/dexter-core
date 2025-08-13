<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface IndexableRepositoryInterface
{
    public function find(int $id): ?IndexableInterface;
}

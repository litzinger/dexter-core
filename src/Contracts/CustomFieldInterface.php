<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface CustomFieldInterface
{
    public function getName(): string;

    public function getType(): string;

    public function getSettings(): array;

    public function getValue(): mixed;

    public function getFacade(): object;
}

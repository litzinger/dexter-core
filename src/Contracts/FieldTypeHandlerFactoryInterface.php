<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface FieldTypeHandlerFactoryInterface
{
    public function create(string $fieldType): ?FieldTypeInterface;
}

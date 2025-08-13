<?php

namespace DexterCore\Contracts;

interface FieldTypeHandlerFactoryInterface
{
    public function create(string $fieldType): ?FieldTypeInterface;
}

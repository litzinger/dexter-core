<?php

namespace Litzinger\DexterCore\Contracts;

interface FieldTypeHandlerFactoryInterface
{
    public function create(string $fieldType): ?FieldTypeInterface;
}

<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface FieldTypeInterface
{
    public function process(
        IndexableInterface $indexable,
        ConfigInterface $config,
        int $fieldId,
        array $fieldSettings,
        $fieldValue,
        object $fieldFacade
    ): mixed;

    public function setsMultipleProperties(): bool;
}

<?php

namespace BoldMinded\Dexter\Shared\Service\Field;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;
use BoldMinded\Dexter\Shared\Contracts\FieldTypeInterface;

class AbstractField implements FieldTypeInterface
{
    public function process(
        IndexableInterface $indexable,
        ConfigInterface $config,
        $fieldId,
        $fieldSettings,
        $value,
        $fieldFacade = null
    ) {
        return $value;
    }

    public function setsMultipleProperties(): bool
    {
        return false;
    }
}

<?php

namespace DexterCore\Service\Field;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;
use DexterCore\Contracts\FieldTypeInterface;

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

<?php

namespace Litzinger\DexterCore\Service\Field;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;
use DexterCore\Contracts\FieldTypeInterface;

class AbstractTextField implements FieldTypeInterface
{
    public function process(
        IndexableInterface $indexable,
        ConfigInterface $config,
        $fieldId,
        $fieldSettings,
        $value,
        $fieldFacade = null
    ) {
        return strip_tags($value ?? '');
    }

    public function setsMultipleProperties(): bool
    {
        return false;
    }
}

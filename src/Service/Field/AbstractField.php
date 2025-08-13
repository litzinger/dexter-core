<?php

namespace Litzinger\DexterCore\Service\Field;

use Litzinger\DexterCore\Contracts\ConfigInterface;
use Litzinger\DexterCore\Contracts\IndexableInterface;
use Litzinger\DexterCore\Contracts\FieldTypeInterface;

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

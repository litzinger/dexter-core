<?php

namespace DexterCore\Service\Field;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;

class CheckboxesFieldType extends AbstractField
{
    public function process(
        IndexableInterface $indexable,
        ConfigInterface $config,
        $fieldId,
        $fieldSettings,
        $value,
        $fieldFacade = null
    ) {
        return explode('|', $value);
    }
}

<?php

namespace BoldMinded\Dexter\Shared\Service\Field;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;

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

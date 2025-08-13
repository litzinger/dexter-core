<?php

namespace Litzinger\DexterCore\Service\Pipeline;

use Litzinger\DexterCore\Contracts\ConfigInterface;
use Litzinger\DexterCore\Contracts\IndexableInterface;
use Litzinger\DexterCore\Contracts\FieldTypeHandlerFactoryInterface;
use DateTimeInterface;

class CustomFieldsPipeline
{
    private IndexableInterface $indexable;
    private ConfigInterface $config;
    private FieldTypeHandlerFactoryInterface $fieldTypeHandlerFactory;

    public function __construct(
        IndexableInterface $indexable,
        ConfigInterface $config,
        FieldTypeHandlerFactoryInterface $fieldTypeHandlerFactory
    ) {
        $this->indexable = $indexable;
        $this->config = $config;
        $this->fieldTypeHandlerFactory = $fieldTypeHandlerFactory;
    }

    public function __invoke(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        $requiredFields = $this->config->get('requiredFields');
        $customFields = $this->config->get('customFields');
        $acceptedFields = array_merge($requiredFields, $customFields);

        $fields = $this->indexable->getCustomFields();
        $newValues = [];

        foreach ($fields as $field) {
            $fieldName = $field->getName();
            $value = $field->getValue();
            $fieldTypeHandler = $this->fieldTypeHandlerFactory->create($field->getType());

            if ($fieldTypeHandler) {
                $value = $fieldTypeHandler->process(
                    $this->indexable,
                    $this->config,
                    $field->getId(),
                    $field->getSettings(),
                    $value,
                    $field->getFacade()
                );
            }

            if (in_array($fieldName, $acceptedFields)) {
                if ($value instanceof DateTimeInterface) {
                    $newValues[$fieldName] = $value->getTimestamp();
                } elseif (
                    $fieldTypeHandler &&
                    $fieldTypeHandler->setsMultipleProperties() &&
                    is_array($value)
                ) {
                    foreach ($value as $key => $val) {
                        if ($val instanceof DateTimeInterface) {
                            $newValues[$key] = $val->getTimestamp();
                        } else {
                            $newValues[$key] = $val;
                        }
                    }
                } else {
                    $newValues[$fieldName] = $value;
                }
            }
        }

        return $newValues;
    }
}

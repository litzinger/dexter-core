<?php

namespace Litzinger\DexterCore\Service\Pipeline;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;

class FilePipeline
{
    private IndexableInterface $indexable;
    private ConfigInterface $config;

    public function __construct(IndexableInterface $indexable, ConfigInterface $config)
    {
        $this->indexable = $indexable;
        $this->config = $config;
    }

    public function __invoke(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        $defaultIndexableFields = [
            'description',
            'credit',
            'location',
            'file_name',
            'file_id',
            'file_size',
            'site_id',
            'upload_date',
            'modified_date',
        ];

        $configIndexableFields = $this->config->get('fileIndexableProperties');

        if (!empty($configIndexableFields)) {
            $defaultIndexableFields = $configIndexableFields;
        }

        $values = array_filter($values, function ($key) use ($defaultIndexableFields) {
            return in_array($key, $defaultIndexableFields);
        }, ARRAY_FILTER_USE_KEY);

        $values['url'] = $this->indexable->getAbsoluteUrl();

        return $values;
    }
}

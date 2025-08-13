<?php

namespace BoldMinded\Dexter\Shared\Service\Pipeline;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;
use BoldMinded\Dexter\Shared\Contracts\CategoryInterface;

class CategoryPipeline
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

        $categories = $this->config->get('categories');

        if (empty($categories)) {
            return $values;
        }

        $allCategories = $this->indexable->getRelated('Categories');
        $pluck = [];

        /** @var CategoryInterface $category */
        foreach ($allCategories as $category) {
            if (in_array($category->getGroupId(), $categories)) {
                $pluck[] = $category->getName();
            }
        }

        $values['categories'] = $pluck;

        return $values;
    }
}

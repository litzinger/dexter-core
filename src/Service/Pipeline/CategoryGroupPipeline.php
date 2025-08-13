<?php

namespace Litzinger\DexterCore\Service\Pipeline;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;
use DexterCore\Contracts\CategoryInterface;

class CategoryGroupPipeline
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

        $categoryGroups = $this->config->get('categoryGroups');

        if (empty($categoryGroups)) {
            return $values;
        }

        $categories = $this->indexable->getRelated('Categories');
        $collection = [];

        /** @var CategoryInterface $category */
        foreach ($categories as $category) {
            // This filtering should be done in the implementation of getRelated
            // if (in_array($category->getGroupId(), $categoryGroups)) {
                $collection[$category->getGroupName()][$category->getId()] = $category->getName();
            // }
        }

        if (!empty($collection)) {
            $values['categoryGroups'] = $collection;
        }

        return $values;
    }
}

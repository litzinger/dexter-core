<?php

namespace Litzinger\DexterCore\Service\Pipeline;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;
use DexterCore\Contracts\CategoryInterface;

class CategoryMenusPipeline
{
    private array $categoryLevels = [];
    private IndexableInterface $indexable;
    private array $currentCategoryLevel = [];
    private ConfigInterface $config;
    private array $postedCategories = [];

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

        $categories = $this->indexable->getRelated('Categories');
        // index by id
        foreach ($categories as $category) {
            $this->postedCategories[$category->getId()] = $category;
        }

        $categoryMenuGroups = $this->config->get('categoryMenuGroups');

        foreach ($this->postedCategories as $category) {
            if (!in_array($category->getGroupId(), $categoryMenuGroups)) {
                continue;
            }

            $this->renderTree($category);
        }

        foreach ($this->categoryLevels as $chain) {
            $ordered = array_reverse($chain);
            $count = count($ordered) - 1;

            if ($count <= 0) {
                $count = 0;
            }

            $values['categories.lvl' . $count][] = implode(' > ', $ordered);
        }

        return $values;
    }

    private function renderTree(CategoryInterface $category)
    {
        $parentId = $category->getParentId() ?? 0;

        $this->currentCategoryLevel[] = $category->getName();

        if ($parentId === 0) {
            // Save the current chain and reset the current
            $this->categoryLevels[] = $this->currentCategoryLevel;
            $this->currentCategoryLevel = [];

            return;
        }

        $this->renderTree($this->postedCategories[$parentId]);
    }
}

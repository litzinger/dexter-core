<?php

namespace BoldMinded\Dexter\Shared\Service\Pipeline;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;
use BoldMinded\Dexter\Shared\Service\FileDescriber;

class FileDescribePipeline
{
    private IndexableInterface $indexable;
    private ConfigInterface $config;
    private FileDescriber $fileDescriber;

    public function __construct(
        IndexableInterface $indexable,
        ConfigInterface $config,
        FileDescriber $fileDescriber
    ) {
        $this->indexable = $indexable;
        $this->config = $config;
        $this->fileDescriber = $fileDescriber;
    }

    public function __invoke(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        $whichOptions = $this->indexable->isImage() ? 'Image' : 'Document';
        $createDescription = $this->config->get(sprintf('parse%sContents.createDescription', $whichOptions)) === true;
        $createCategories = $this->config->get(sprintf('parse%sContents.createCategories', $whichOptions)) === true;
        $replaceDescription = $this->config->get(sprintf('parse%sContents.replaceDescription', $whichOptions)) === true;
        $replaceCategories = $this->config->get(sprintf('parse%sContents.replaceCategories', $whichOptions)) === true;

        if (
            !$createCategories
            && !$createDescription
            && !$replaceCategories
            && !$replaceDescription
        ) {
            return $values;
        }

        $description = $this->fileDescriber->describe($this->indexable);

        if ($this->fileDescriber->isJson($description)) {
            $descriptionData = json_decode($description, true);
            $newDescription = $descriptionData['description'] ?? '';
            $newCategories = $descriptionData['tags'] ?? [];
        } else {
            $newDescription = $description;
            $newCategories = [];
        }

        if ($replaceDescription && $newDescription) {
            $values['description'] = $newDescription;
        }

        if ($replaceCategories && !empty($newCategories)) {
            $values['categories'] = $newCategories;
        }

        return $values;
    }
}

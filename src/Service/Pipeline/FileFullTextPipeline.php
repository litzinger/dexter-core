<?php

namespace BoldMinded\Dexter\Shared\Service\Pipeline;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;
use BoldMinded\Dexter\Shared\Service\FileParser;

class FileFullTextPipeline
{
    private IndexableInterface $indexable;
    private ConfigInterface $config;
    private FileParser $fileParser;

    public function __construct(
        IndexableInterface $indexable,
        ConfigInterface $config,
        FileParser $fileParser
    ) {
        $this->indexable = $indexable;
        $this->config = $config;
        $this->fileParser = $fileParser;
    }

    public function __invoke(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        if ($this->config->get('includeFullText') === false) {
            return $values;
        }

        $flat = [];

        array_walk_recursive($values, function ($value, $key) use (&$flat) {
            if (
                $value &&
                in_array($key, ['file_name', 'title', 'description', 'credit', 'location']) &&
                is_scalar($value) &&
                !is_numeric($value)
            ) {
                $flat[] = (string) $value;
            }
        });

        $parsedText = $this->fileParser->parse($this->indexable);

        if ($parsedText) {
            $flat[] = $parsedText;
        }

        $values['__full_text'] = strip_tags(implode(' ', array_unique($flat)));

        return $values;
    }
}

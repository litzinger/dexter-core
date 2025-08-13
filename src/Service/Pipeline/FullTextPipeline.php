<?php

namespace BoldMinded\Dexter\Shared\Service\Pipeline;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;
use BoldMinded\Dexter\Shared\Service\StopWordRemover;

class FullTextPipeline
{
    private IndexableInterface $indexable;
    private ConfigInterface $config;
    private StopWordRemover $stopWordRemover;

    public function __construct(
        IndexableInterface $indexable,
        ConfigInterface $config,
        StopWordRemover $stopWordRemover
    ) {
        $this->indexable = $indexable;
        $this->config = $config;
        $this->stopWordRemover = $stopWordRemover;
    }

    public function __invoke(array $values): array
    {
        if (empty($values)) {
            return [];
        }

        if ($this->config->get('includeFullText') === false) {
            return $values;
        }

        $values['__full_text'] = $this->flatten($values);

        return $values;
    }

    private function flatten(array $array): string
    {
        $flat = [];

        array_walk_recursive($array, function ($value, $key) use (&$flat) {
            if ($value && is_scalar($value) && !is_numeric($value)) {
                $flat[] = (string) $value;
            }
        });

        $text = strip_tags(implode(' ', array_unique($flat)));

        // In the EE implementation, we'll fire a hook here.
        // For now, we'll just call the stop word remover directly.
        $text = $this->stopWordRemover->remove($text, $this->config->get('stopWords'));

        return $text;
    }
}

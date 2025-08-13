<?php

namespace BoldMinded\Dexter\Shared\Service\Pipeline;

use BoldMinded\Dexter\Shared\Contracts\ConfigInterface;
use BoldMinded\Dexter\Shared\Contracts\IndexableInterface;

class CommentsPipeline
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

        $comments = $this->indexable->getRelated('Comments');

        if (empty($comments)) {
            $values['__comments'] = [];
            return $values;
        }

        $commentCleaned = array_map(function ($comment) {
            return trim(strip_tags($comment));
        }, $comments);

        $values['__comments'] = $commentCleaned;

        return $values;
    }
}

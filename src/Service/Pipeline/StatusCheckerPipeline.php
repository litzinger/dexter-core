<?php

namespace Litzinger\DexterCore\Service\Pipeline;

use DexterCore\Contracts\ConfigInterface;
use DexterCore\Contracts\IndexableInterface;

class StatusCheckerPipeline
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

        $status = $values['status'] ?? null;

        if ($status && !in_array($status, $this->config->get('statuses'))) {
            $values = [];
        }

        return $values;
    }
}

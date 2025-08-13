<?php

namespace Litzinger\DexterCore\Service\Provider;

class AIProviderFactory
{
    public static function create(AIOptions $options): ProviderInterface
    {
        if (!$options->provider || !$options->key || !$options->model) {
            return new DummyProvider();
        }

        if ($options->provider === 'openAi' && $options->key) {
            return new OpenAIProvider($options);
        }

        throw new \InvalidArgumentException('Invalid options provided for AI provider.');
    }
}

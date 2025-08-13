<?php

namespace Litzinger\DexterCore\Service\Provider;

use DexterCore\Service\Options;

class DummyProvider implements ProviderInterface
{
    public function __construct(Options $options)
    {
    }

    public function request(string $prompt, string $content, string $requestType): string
    {
        return '';
    }
}

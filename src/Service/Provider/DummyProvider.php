<?php

namespace BoldMinded\Dexter\Shared\Service\Provider;

use BoldMinded\Dexter\Shared\Service\Options;

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

<?php

namespace Litzinger\DexterCore\Service\DocumentParsers;

use DexterCore\Service\Options;
use DexterCore\Service\Provider\AIOptions;

class DocumentOptions implements Options
{
    public function __construct(
        public ?int $maxPages = null,
        public ?int $maxWords = null,
        public Options|null $ai = null,
    ) {
    }
    public static function fromArray(array $options): self
    {
        $aiOptions = AIOptions::fromArray($options['ai'] ?? []);

        return new self(
            maxPages: $options['maxPages'] ?? null,
            maxWords: $options['maxWords'] ?? null,
            ai: $aiOptions
        );
    }
}

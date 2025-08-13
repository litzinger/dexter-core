<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

use BoldMinded\Dexter\Shared\Service\Options;
use BoldMinded\Dexter\Shared\Service\Provider\AIOptions;

class ImageOptions implements Options
{
    public function __construct(
        public Options|null $ai,
    ) {
    }
    public static function fromArray(array $options): self
    {
        $aiOptions = AIOptions::fromArray($options['ai'] ?? []);

        return new self(
            ai: $aiOptions
        );
    }
}

<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

class WebpParser extends AbstractImageParser
{
    public function getSupportedMimeTypes(): array
    {
        return ['image/webp'];
    }
}

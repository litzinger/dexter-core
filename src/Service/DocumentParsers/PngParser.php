<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

class PngParser extends AbstractImageParser
{
    public function getSupportedMimeTypes(): array
    {
        return ['image/png', 'image/x-png'];
    }
}

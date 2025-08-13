<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

class JpegParser extends AbstractImageParser
{
    public function getSupportedMimeTypes(): array
    {
        return ['image/jpeg'];
    }
}

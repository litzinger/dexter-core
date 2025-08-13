<?php

namespace DexterCore\Service\DocumentParsers;

class JpegParser extends AbstractImageParser
{
    public function getSupportedMimeTypes(): array
    {
        return ['image/jpeg'];
    }
}

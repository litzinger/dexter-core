<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

class GifParser extends AbstractImageParser
{
    public function getSupportedMimeTypes(): array
    {
        return ['image/gif', 'image/x-gif'];
    }
}

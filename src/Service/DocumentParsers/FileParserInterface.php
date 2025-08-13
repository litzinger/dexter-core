<?php

namespace BoldMinded\Dexter\Shared\Service\DocumentParsers;

interface FileParserInterface
{
    public function parse(string $filePath, array $options = []): string;
    public function describe(string $filePath, array $options = []): string;
    public function getSupportedMimeTypes(): array;
}

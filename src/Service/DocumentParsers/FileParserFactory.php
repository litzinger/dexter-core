<?php

namespace DexterCore\Service\DocumentParsers;

use DexterCore\Contracts\ConfigInterface;

class FileParserFactory
{
    private array $parsers = [];
    private array $mimeTypeMap = [];

    public function __construct(public ConfigInterface $config)
    {
        $this->registerDefaultParsers();
    }

    private function registerDefaultParsers(): void
    {
        $this->registerParser(new PdfParser());
        $this->registerParser(new TxtParser());
        $this->registerParser(new CsvParser());
        $this->registerParser(new XmlParser());
        $this->registerParser(new DocParser());
        $this->registerParser(new JpegParser());
        $this->registerParser(new PngParser());
        $this->registerParser(new GifParser());
        $this->registerParser(new WebpParser());
    }

    public function registerParser(FileParserInterface $parser): void
    {
        $className = get_class($parser);
        $this->parsers[$className] = $parser;

        foreach ($parser->getSupportedMimeTypes() as $mimeType) {
            $this->mimeTypeMap[$mimeType] = $className;
        }
    }

    public function createParser(string $mimeType): FileParserInterface
    {
        if (!isset($this->mimeTypeMap[$mimeType])) {
            throw new \InvalidArgumentException(sprintf('Unsupported MIME type: %s', $mimeType));
        }

        $className = $this->mimeTypeMap[$mimeType];
        return $this->parsers[$className];
    }

    public function parseFile(
        string $filePath,
        ?string $mimeType = null,
        array $options = [],
    ): string {
        if ($mimeType === null) {
            $mimeType = $this->detectMimeType($filePath);
        }

        $parser = $this->createParser($mimeType);
        return $parser->parse($filePath, $this->buildOptions($mimeType, $options));
    }

    public function describeFile(
        string $filePath,
        ?string $mimeType = null,
        array $options = [],
    ): string {
        if ($mimeType === null) {
            $mimeType = $this->detectMimeType($filePath);
        }

        $parser = $this->createParser($mimeType);
        return $parser->describe($filePath, $this->buildOptions($mimeType, $options));
    }

    private function buildOptions(string $mimeType, array $options): array
    {
        $provider = $this->config->get('aiProvider', 'openAi');

        $whichOptions = in_array($mimeType, $this->imageMimeTypes()) ? 'Image' : 'Document';
        $prompt = $this->config->get(sprintf('parse%sContents.describePrompt', $whichOptions)) ?: 'Describe this file';

        $options['ai'] = [
            'provider' => $provider,
            'key' => $this->config->get($provider . '.key'),
            'model' => $this->config->get($provider . '.model'),
            'embedModel' => $this->config->get($provider . '.embedModel'),
            'temperature' => $this->config->get($provider . '.temperature'),
            'frequencyPenalty' => $this->config->get($provider . '.frequencyPenalty'),
            'presencePenalty' => $this->config->get($provider . '.presencePenalty'),
            'maxTokens' => $this->config->get($provider . '.maxTokens'),
            'prompt' => $prompt,
        ];

        return $options;
    }

    public function getSupportedMimeTypes(): array
    {
        return array_keys($this->mimeTypeMap);
    }

    private function detectMimeType(string $filePath): string
    {
        $mimeType = mime_content_type($filePath);

        if ($mimeType === false) {
            // Fallback to file extension
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $extensionMap = [
                'pdf' => 'application/pdf',
                'txt' => 'text/plain',
                'csv' => 'text/csv',
                'xml' => 'application/xml',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];

            if (isset($extensionMap[$extension])) {
                return $extensionMap[$extension];
            }

            throw new \RuntimeException(sprintf('Could not detect MIME type for file: %s', $filePath));
        }

        return $mimeType;
    }

    private function imageMimeTypes(): array
    {
        return [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
        ];
    }
}

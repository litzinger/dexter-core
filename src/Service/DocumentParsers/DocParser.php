<?php

namespace DexterCore\Service\DocumentParsers;

use BoldMinded\Dexter\Dependency\PhpOffice\PhpWord\IOFactory;

class DocParser extends AbstractDocumentParser
{
    public function parse(string $filePath, array $options = []): string
    {
        $parseOptions = $this->getParseOptions($options);

        try {
            $phpWord = IOFactory::load($filePath);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif (method_exists($element, 'getElements')) {
                        $text .= $this->extractTextFromElements($element->getElements()) . "\n";
                    }

                    // Check word limit during processing
                    if ($parseOptions->maxWords !== null) {
                        $wordCount = str_word_count($text);
                        if ($wordCount >= $parseOptions->maxWords) {
                            break 2;
                        }
                    }
                }
            }

            return $this->applyLimits(trim($text), $parseOptions);
        } catch (\Exception $e) {
            throw new \RuntimeException("Error parsing DOC file: " . $e->getMessage());
        }
    }

    private function extractTextFromElements($elements): string
    {
        $text = '';
        foreach ($elements as $element) {
            if (method_exists($element, 'getText')) {
                $text .= $element->getText() . ' ';
            } elseif (method_exists($element, 'getElements')) {
                $text .= $this->extractTextFromElements($element->getElements()) . ' ';
            }
        }
        return $text;
    }

    public function getSupportedMimeTypes(): array
    {
        return [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
    }
}

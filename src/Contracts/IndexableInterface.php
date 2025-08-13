<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface IndexableInterface
{
    public function get(string $key): mixed;

    public function getValues(): array;

    public function getId(): int;

    public function getUniqueId(): string;

    public function getRelated(string $type): array;

    /**
     * @return CustomFieldInterface[]
     */
    public function getCustomFields(): array;

    public function getMimeType(): string;

    public function getAbsoluteUrl(): string;

    public function getAbsolutePath(): string;

    public function isImage(): bool;
}

<?php

namespace BoldMinded\Dexter\Shared\Contracts;

interface TranslatorInterface
{
    public function get(string $key): string;
}

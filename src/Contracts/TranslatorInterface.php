<?php

namespace DexterCore\Contracts;

interface TranslatorInterface
{
    public function get(string $key): string;
}

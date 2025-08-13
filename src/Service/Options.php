<?php

namespace DexterCore\Service;

interface Options
{
    public static function fromArray(array $options): self;
}

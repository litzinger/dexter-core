<?php

namespace BoldMinded\Dexter\Shared\Service;

interface Options
{
    public static function fromArray(array $options): self;
}

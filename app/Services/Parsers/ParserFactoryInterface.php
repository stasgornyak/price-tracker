<?php

namespace App\Services\Parsers;

interface ParserFactoryInterface
{
    public function createParser(string $url): ParserInterface;
}

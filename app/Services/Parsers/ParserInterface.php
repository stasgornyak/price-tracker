<?php

namespace App\Services\Parsers;

interface ParserInterface
{
    public function parsePrice(string $url): float;
}

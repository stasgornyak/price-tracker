<?php

namespace App\Services\Parsers;

use App\Exceptions\ParserException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class OlxUaParser implements ParserInterface
{
    /**
     * @throws ParserException
     * @throws ConnectionException
     */
    public function parsePrice(string $url): float
    {
        $response = Http::get($url);

        if (! $response->successful()) {
            throw new ParserException('Failed to fetch OLX page: '.$response->status());
        }

        $html = $response->body();

        // 1) Prefer JSON-LD/schema price (e.g. "price":2460)
        if (preg_match('/"price"\s*:\s*([0-9]+(?:\.[0-9]+)?)/u', $html, $m) === 1) {
            return (float) $m[1];
        }

        // 2) Fallback to visible currency text like: 2 460 грн. (with normal or non-breaking spaces)
        if (preg_match('/([0-9\s\x{00A0}]+)\s*грн/iu', $html, $m) === 1) {
            $numeric = preg_replace('/[^0-9.]/', '', str_replace("\xC2\xA0", ' ', $m[1]));

            if ($numeric !== '') {
                return (float) str_replace(',', '.', $numeric);
            }
        }

        throw new ParserException('Unable to parse price from OLX page.');
    }
}

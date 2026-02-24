<?php

use App\Exceptions\ParserException;
use App\Services\Parsers\OlxUaParser;
use Illuminate\Support\Facades\Http;

it('parses price from OLX HTML and returns float value', function (): void {
    $url = 'https://www.olx.ua/d/uk/obyavlenie/bezdrotoviy-dzvnok-z-vdeokameroyu-hd-1080p-IDTkLs7.html';

    $html = file_get_contents(dirname(__FILE__).'/../../Fixtures/olx-page-source-example.html');

    Http::fake([
        $url => Http::response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']),
    ]);

    $parser = new OlxUaParser;

    $price = $parser->parsePrice($url);

    expect($price)->toBeFloat()->toBe(2460.0);
});

it('throws ParserException when HTTP request fails', function (): void {
    $url = 'https://www.olx.ua/invalid-page';

    Http::fake([
        $url => Http::response('Not Found', 404),
    ]);

    $parser = new OlxUaParser;

    expect(fn () => $parser->parsePrice($url))
        ->toThrow(ParserException::class, 'Failed to fetch OLX page: 404');
});

it('throws ParserException when price cannot be parsed', function (): void {
    $url = 'https://www.olx.ua/no-price';

    Http::fake([
        $url => Http::response('<html><body>No price here</body></html>', 200),
    ]);

    $parser = new OlxUaParser;

    expect(fn () => $parser->parsePrice($url))
        ->toThrow(ParserException::class, 'Unable to parse price from OLX page.');
});

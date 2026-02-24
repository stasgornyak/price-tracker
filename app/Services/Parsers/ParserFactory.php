<?php

namespace App\Services\Parsers;

use App\Exceptions\ParserException;
use Illuminate\Contracts\Container\BindingResolutionException;

class ParserFactory implements ParserFactoryInterface
{
    /**
     * @throws BindingResolutionException
     * @throws ParserException
     */
    public function createParser(string $url): ParserInterface
    {
        $parsers = config('subscriptions.parsers');

        foreach ($parsers as $parser) {
            if (str($url)->startsWith($parser['base_url'])) {
                return app()->make($parser['class']);
            }
        }

        throw new ParserException(__('Unable to find parser for url :url.', ['url' => $url]));
    }
}

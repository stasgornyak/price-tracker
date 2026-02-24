<?php

if (! function_exists('removeQueryFromUrl')) {
    function removeQueryFromUrl(string $url): string
    {
        $parts = parse_url($url);

        $newUrlParts = [];

        if (isset($parts['scheme'])) {
            $newUrlParts[] = $parts['scheme'].'://';
        }

        if (isset($parts['host'])) {
            $newUrlParts[] = $parts['host'];
        }

        if (isset($parts['path'])) {
            $newUrlParts[] = $parts['path'];
        }

        return implode('', $newUrlParts);
    }
}

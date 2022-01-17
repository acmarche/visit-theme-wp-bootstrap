<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Component\HttpFoundation\Request;

class Theme
{
    public const PAGE_INTRO = 115;

    public static function isHomePage(): bool
    {
        $request = Request::createFromGlobals();
        $uri = $request->getPathInfo();

        return '/' === $uri || '/fr/' === $uri || '/fr' === $uri || '/nl/' === $uri || '/nl' === $uri || '/en/' === $uri || '/en' === $uri;
    }
}

<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Component\HttpFoundation\Request;

class Theme
{
    public const PAGE_INTRO = 115;
    public const PAGE_DECOUVRIR = 828;
    public const CATEGORY_ARTS = 10;
    public const CATEGORY_BALADES = 11;
    public const CATEGORY_FETES = 12;
    public const CATEGORY_GOURMANDISES = 13;
    public const CATEGORY_PATRIMOINES = 9;

    public static function isHomePage(): bool
    {
        $request = Request::createFromGlobals();
        $uri = $request->getPathInfo();

        return '/' === $uri || '/fr/' === $uri || '/fr' === $uri || '/nl/' === $uri || '/nl' === $uri || '/en/' === $uri || '/en' === $uri;
    }
}

<?php


namespace VisitMarche\Theme\Inc;

use Symfony\Component\HttpFoundation\Request;

class Theme
{
    const PAGE_INTRO = 115;

    static function isHomePage(): bool
    {
        $request = Request::createFromGlobals();
        $uri = $request->getPathInfo();
        if ($uri === '/' || $uri === '/fr/' || $uri === '/fr' || $uri === '/nl/' || $uri === '/nl' || $uri === '/en/' || $uri === '/en') {
            return true;
        }

        return false;
    }
}

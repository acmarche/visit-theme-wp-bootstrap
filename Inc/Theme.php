<?php


namespace VisitMarche\Theme\Inc;

use Symfony\Component\HttpFoundation\Request;

class Theme
{
    const PAGE_ALERT = 9570;
    const PAGE_CARTO = 20644;//tourisme

    static function isHomePage(): bool
    {
        $request = Request::createFromGlobals();
        $uri     = $request->getRequestUri();
        if ($uri === '/eyjafjallajokull/') {
            return true;
        }
        if ($uri === '/') {
            return true;
        }
        return false;
    }
}

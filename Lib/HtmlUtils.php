<?php

namespace VisitMarche\Theme\Lib;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlUtils
{
    public static function cleanString(?string $description): ?string
    {
        if (!$description) {
            return null;
        }
        $description = trim(strip_tags($description));

        return preg_replace('#=#', '', $description);
    }

    public static function isHTML($string): bool
    {
        return $string != strip_tags($string);
    }

    public static function pureHtml(?string $html): ?string
    {
        if (!$html) {
            return $html;
        }
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Cache.SerializerPath', '/tmp');
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}

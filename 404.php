<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\Twig;

get_header();

$url = RouterPivot::getCurrentUrl();
Twig::rendPage(
    'errors/404.html.twig',
    [
        'title' => '404',
        'posts' => [],
        'url' => $url,
    ]
);

get_footer();

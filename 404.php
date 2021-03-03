<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

Twig::rendPage(
    'page/404.html.twig',
    [
        'title' => '404',
        'posts' => [],
    ]
);

get_footer();

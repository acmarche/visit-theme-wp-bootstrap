<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => '404',
        'posts' => [],
    ]
);

get_footer();

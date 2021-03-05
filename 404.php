<?php

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Theme\Lib\Twig;

get_header();
global $wp_rewrite;

//dump($wp_rewrite);
$url = Router::getCurrentUrl();
Twig::rendPage(
    'errors/404.html.twig',
    [
        'title' => '404',
        'posts' => [],
        'url' => $url,
    ]
);

get_footer();

<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Twig;

get_header();

Twig::rendPage(
    'errors/500.html.twig',
    [
        'message' => 'Page vide',
        'title' => 'Error 500',
        'tags' => [],
        'relations' => [],
    ]
);

get_footer();
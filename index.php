<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Mailer;
use VisitMarche\Theme\Lib\Twig;
use Symfony\Component\HttpFoundation\Request;

get_header();

Twig::rendPage(
    'errors/500.html.twig',
    [
        'message'   => 'Page vide',
        'title'     => 'Error 500',
        'tags'      => [],
        'relations' => [],
    ]
);

$request = Request::createFromGlobals();
$url = $request->getUri();
Mailer::sendError("Error page index.php", "url: $url");
get_footer();


<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use AcMarche\Common\Twig;
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


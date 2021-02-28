<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Repository\HadesRemoteRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use Psr\Cache\InvalidArgumentException;

//get_header();
$hadesRemoteRepository = new HadesRemoteRepository();
$hadesRepository = new HadesRepository();
try {
    //$events = $hadesRemoteRepository->getHebergements(['hotel']);
    $events = $hadesRepository->getHebergements();
} catch (InvalidArgumentException $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}
//dump($events);
/*
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
//Mailer::sendError("Error page index.php", "url: $url");
//get_footer();
*/

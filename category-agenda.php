<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Repository\HadesRepository;
use Psr\Cache\InvalidArgumentException;
use VisitMarche\Theme\Inc\RouterHades;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);

$hadesRepository = new HadesRepository();
try {
    $events = $hadesRepository->getEvents();
    array_map(
        function ($event) use ($cat_ID) {
            $event->url = RouterHades::getUrlOffre($event, $cat_ID);
        },
        $events
    );
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

Twig::rendPage(
    'agenda/index.html.twig',
    [
        'events' => $events,
    ]
);

get_footer();

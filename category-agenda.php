<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRepository;
use Psr\Cache\InvalidArgumentException;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);

$language = LocaleHelper::getSelectedLanguage();
$hadesRepository = new HadesRepository();
try {
    $events = $hadesRepository->getEvents();
    array_map(
        function ($event) use ($cat_ID, $language) {
            $event->url = RouterHades::getUrlOffre($event, $cat_ID);
            $event->titre = $event->getTitre($language);
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

<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);

$language = LocaleHelper::getSelectedLanguage();
$pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);

try {
    $events = $pivotRepository->getEvents(true);
    array_map(
        function ($event) use ($cat_ID, $language) {
            $event->url = RouterPivot::getUrlOffre($event, $cat_ID);
        },
        $events
    );
} catch (\Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'title' => 'Page non chargée',
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
        'category' => $category,
    ]
);

get_footer();

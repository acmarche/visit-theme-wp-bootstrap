<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use Exception;
use VisitMarche\Theme\Lib\RouterPivot;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$codeCgt = get_query_var(RouterPivot::PARAM_EVENT);

$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);
$nameBack = $currentCategory->name;

$pivotRepository = PivotContainer::getRepository();

$event = null;

if (!str_starts_with($codeCgt, "EVT")) {
    $event = $pivotRepository->getEventByIdHades($codeCgt);
    if (null === $event) {
        Twig::rendPage(
            'errors/404.html.twig',
            [
                'title' => 'Manifestation non trouvée',
                'url' => '',
            ]
        );

        get_footer();

        return;
    }
}

try {
    $event = $pivotRepository->getEvent($codeCgt);
} catch (Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'title' => $e->getMessage(),
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

if (null === $event) {
    Twig::rendPage(
        'errors/404.html.twig',
        [
            'title' => 'Manifestation non trouvée',
            'url' => '',
        ]
    );

    get_footer();

    return;
}
$language = LocaleHelper::getSelectedLanguage();
$tags = [];

$image = null;
$images = $event->images;
if (count($images) > 0) {
    $image = $images[0];
}
$tags = [];
foreach ($event->categories as $category) {
    $urlCat = RouterPivot::getUrlEventCategory($category);
    $tags[] = [
        'name' => $category->labelByLanguage($language),
        'url' => $category->id,
    ];
}

$categoryAgenda = get_category_by_slug('agenda');
$urlAgenda = get_category_link($categoryAgenda);
$event->url = RouterPivot::getUrlEvent($event, $categoryAgenda->cat_ID);
$offres = $pivotRepository->getSameEvents($event);

RouterPivot::setRouteEvents($offres, $categoryAgenda->cat_ID);

$recommandations = [];
foreach ($offres as $item) {
    if ($event->codeCgt === $item->codeCgt) {
        continue;
    }
    $url = RouterPivot::getUrlOffre($item, $currentCategory->cat_ID);
    $recommandations[] = [
        'title' => $item->nom,
        'url' => $url,
        'image' => $item->firstImage(),
        'categories' => $item->categories,
    ];
}

//$contact = $event->contactPrincipal();
//$communication = $event->communcationPrincipal();
$communication = $contact = '';
/*
array_map(
    function ($parent) use ($currentCategory) {
        $parent->url = RouterHades::getUrlOffre($parent, $currentCategory->cat_ID);
    },
    $event->parents
);

array_map(
    function ($enfant) use ($currentCategory) {
        $enfant->url = RouterHades::getUrlOffre($enfant, $currentCategory->cat_ID);
    },
    $event->enfants
);
*/
Twig::rendPage(
    'agenda/show.html.twig',
    [
        'title' => $event->nomByLanguage($language),
        'currentCategory' => $currentCategory,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offre' => $event,
        'contact' => $contact,
        'communication' => $communication,
        'recommandations' => $recommandations,
        'tags' => $tags,
        'images' => $event->images,
        'latitude' => $event->getAdresse()->latitude ?? null,
        'longitude' => $event->getAdresse()->longitude ?? null,
    ]
);
get_footer();

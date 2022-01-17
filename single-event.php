<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRepository;
use Exception;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$codeCgt = get_query_var(RouterHades::PARAM_EVENT);
$hadesRepository = new HadesRepository();

$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);
$nameBack = $currentCategory->name;

try {
    $offre = $hadesRepository->getOffreWithChildrenAndParents($codeCgt);
} catch (Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

if (null === $offre) {
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
foreach ($offre->categories as $category) {
    $tags[] = [
        'name' => $category->getLib($language),
        'url' => RouterHades::getUrlEventCategory($category),
    ];
}

$offres = $hadesRepository->getOffresSameCategories($offre);
$recommandations = [];
foreach ($offres as $item) {
    if ($offre->id === $item->id) {
        continue;
    }
    $url = RouterHades::getUrlOffre($item, $currentCategory->cat_ID);
    $recommandations[] = [
        'title' => $item->getTitre($language),
        'url' => $url,
        'image' => $item->firstImage(),
        'categories' => $item->categories,
    ];
}

$contact = $offre->contactPrincipal();
$communication = $offre->communcationPrincipal();

array_map(
    function ($parent) use ($currentCategory) {
        $parent->url = RouterHades::getUrlOffre($parent, $currentCategory->cat_ID);
    },
    $offre->parents
);

array_map(
    function ($enfant) use ($currentCategory) {
        $enfant->url = RouterHades::getUrlOffre($enfant, $currentCategory->cat_ID);
    },
    $offre->enfants
);

Twig::rendPage(
    'agenda/show.html.twig',
    [
        'title' => $offre->getTitre($language),
        'currentCategory' => $currentCategory,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offre' => $offre,
        'contact' => $contact,
        'communication' => $communication,
        'recommandations' => $recommandations,
        'tags' => $tags,
        'images' => $offre->medias,
        'latitude' => $offre->geocode->latitude() ?? null,
        'longitude' => $offre->geocode->longitude() ?? null,
    ]
);
get_footer();

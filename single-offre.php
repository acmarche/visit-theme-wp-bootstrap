<?php


namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use AcMarche\Pivot\Repository\HadesRepository;
use Exception;
use VisitMarche\Theme\Inc\RouterHades;

get_header();

$codeCgt = get_query_var(RouterHades::PARAM_OFFRE);

$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);
$nameBack = $currentCategory->name;

$hadesRepository = new HadesRepository();
try {
    $offre = $hadesRepository->getOffre($codeCgt);
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

if (!$offre) {
    Twig::rendPage(
        'errors/404.html.twig',
        [
            'url' => '',
        ]
    );

    get_footer();

    return;
}

$language = LocaleHelper::getSelectedLanguage();
$tags = [];
$categoryOffres = get_category_by_slug('offres');
$urlCat = get_category_link($categoryOffres);
foreach ($offre->categories as $category) {
    $tags[] = [
        'name' => $category->getLib($language),
        'url' => $urlCat.'?cgt='.$category->id,
    ];
}

$relations = $hadesRepository->getOffresSameCategories($offre, $currentCategory->cat_ID);
$contact = $offre->contactPrincipal();
$communication = $offre->communcationPrincipal();

Twig::rendPage(
    'offre/show.html.twig',
    [
        'title' => $offre->getTitre($language),
        'offre' => $offre,
        'currentCategory' => $currentCategory,
        'contact' => $contact,
        'communication' => $communication,
        'tags' => $tags,
        'images' => $offre->medias,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'relations' => $relations,
        'latitude' => $offre->geocode->latitude() ?? null,
        'longitude' => $offre->geocode->longitude() ?? null,
    ]
);
get_footer();

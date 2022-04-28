<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
use AcMarche\Pivot\Spec\UrnTypeList;
use Exception;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$codeCgt = get_query_var(RouterHades::PARAM_OFFRE);

$currentCategory = get_category_by_slug(get_query_var('category_name'));
$urlBack = get_category_link($currentCategory);
$nameBack = $currentCategory->name;

$pivotRepository = PivotContainer::getRepository();

$offre = null;

list($code, $rest) = explode("-", $codeCgt);

if (!in_array($code, UrnTypeList::getAllCode())) {
    $offre = $pivotRepository->getOffreByIdHades($codeCgt);
}

if (!$offre) {
    try {
        $offre = $pivotRepository->getOffreByCgtAndParse($codeCgt, Offre::class);
    } catch (Exception $e) {
        Twig::rendPage(
            'errors/500.html.twig',
            [
                'title' => 'Error',
                'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
            ]
        );
        get_footer();

        return;
    }
}

if (null === $offre) {
    Twig::rendPage(
        'errors/404.html.twig',
        [
            'url' => '',
            'title' => 'Page non trouvée',
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
        'name' => $category->labelByLanguage($language),
        'url' => $urlCat.'?cgt='.$category->id,
    ];
}
$recommandations = [];
$offres = $pivotRepository->getSameOffres($offre);

foreach ($offres as $item) {
    $url = RouterHades::getUrlOffre($item, $currentCategory->cat_ID);
    $tags2 = [$item->typeOffre->labelByLanguage($language)];

    $recommandations[] = [
        'title' => $item->nomByLanguage($language),
        'url' => $url,
        'image' => $item->firstImage(),
        'categories' => $tags2,
    ];
}
Twig::rendPage(
    'offre/show.html.twig',
    [
        'title' => $offre->nomByLanguage($language),
        'offre' => $offre,
        'currentCategory' => $currentCategory,
        'tags' => $tags,
        'images' => $offre->images,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'recommandations' => $recommandations,
        'latitude' => $offre->getAdresse()->latitude ?? null,
        'longitude' => $offre->getAdresse()->longitude ?? null,
    ]
);
get_footer();

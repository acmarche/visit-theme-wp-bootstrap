<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$filtres = $_GET['cgt'] ?? '';
$category = get_category_by_slug('show');

$pivotRepository = PivotContainer::getRepository();
$language = LocaleHelper::getSelectedLanguage();

$offres = $pivotRepository->getOffres([$filtres]);
//$categoryUtils = new HadesFiltres();
//$filtres = $categoryUtils->translateFiltres($filtres, $language);
$cat_ID = $category->cat_ID;

array_map(
    function ($offre) use ($cat_ID, $language) {
        $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
    },
    $offres
);

$urlBack = '/';
$nameBack = 'accueil';

Twig::rendPage(
    'category/test_index.html.twig',
    [
        'filtres' => $filtres,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offres' => $offres,
    ]
);

get_footer();

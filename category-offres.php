<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\Twig;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$hadesRefrubrique = $_GET['cgt'] ?? '';
$category = get_category_by_slug('show');
$hadesRepository = new HadesRepository();

$filtres = [$hadesRefrubrique];
$categoryUtils = new CategoryUtils();
$title = $categoryUtils->getNameByKey($hadesRefrubrique);

$offres = $hadesRepository->getOffres($filtres);
$cat_ID = $category->cat_ID;
array_map(
    function ($offre) use ($cat_ID) {
        $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
    },
    $offres
);

$urlBack = '/';
$nameBack = 'accueil';

Twig::rendPage(
    'category/test_index.html.twig',
    [
        'title' => $title,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offres' => $offres,
    ]
);

get_footer();

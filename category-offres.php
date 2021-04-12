<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\Twig;

get_header();

$hadesRefrubrique = $_GET['cgt'] ?? '';
$category = get_category_by_slug('show');
$hadesRepository = new HadesRepository();

$categoryUtils = new CategoryUtils();
$title = $categoryUtils->getNameByKey($hadesRefrubrique);
$filtres = explode(',', $hadesRefrubrique);
$categoryUtils = new CategoryUtils();
$titles = $categoryUtils->getNamesByKey($filtres);
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
        'titles' => $titles,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offres' => $offres,
    ]
);

get_footer();

<?php


namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Twig;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$hadesRefrubrique = $_GET['cgt'];
$cat_ID = 1;
$category = get_category($cat_ID);

$hadesRepository = new HadesRepository();

$filtres = [$hadesRefrubrique];

$offres = $hadesRepository->getOffres($filtres);
array_map(
    function ($offre) use ($cat_ID) {dump($offre);
        $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
    },
    $offres
);


$urlBack = '/';
$nameBack = 'accueil';

Twig::rendPage(
    'category/test_index.html.twig',
    [
        'title' => $hadesRefrubrique,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'offres' => $offres,
    ]
);

get_footer();

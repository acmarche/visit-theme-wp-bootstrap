<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;

get_header();

$filtresString = $_GET['cgt'] ?? '';
$category = get_category_by_slug('show');
$hadesRepository = new HadesRepository();

$filtres = explode(',', $filtresString);
$offres = $hadesRepository->getOffres($filtres);

$language = LocaleHelper::getSelectedLanguage();
$categoryUtils = new HadesFiltres();
$filtres = $categoryUtils->translateFiltres($filtres, $language);
$cat_ID = $category->cat_ID;

array_map(
    function ($offre) use ($cat_ID, $language) {
        $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
        $offre->titre = $offre->getTitre($language);
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

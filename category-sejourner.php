<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use Exception;
use VisitMarche\Theme\Inc\CategoryMetaBox;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title = single_cat_title('', false);
$permalink = get_category_link($cat_ID);
$hadesRefrubrique = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HADES, true);

$hadesRepository = new HadesRepository();

try {
    $hebergements = $hadesRepository->getRestaurations();
} catch (Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les hébergements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}


switch ($hadesRefrubrique) {
    case 'hebergements':
        $filtres = Hades::LOGEMENTS;
        $fiches = $hadesRepository->getHebergements();
        break;
    case 'restauration':
        $filtres = Hades::RESTAURATION;
        $fiches = $hadesRepository->getRestaurations();
        break;
    default:
        $filtres = [];
        $fiches = [];
        break;
}

Twig::rendPage(
    'category/hades/index.html.twig',
    [
        'filtres' => $filtres,
        'fiches' => $fiches,
        'title' => $title,
        'permalink' => $permalink,
    ]
);

get_footer();

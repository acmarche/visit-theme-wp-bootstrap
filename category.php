<?php


namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title = single_cat_title('', false);
$permalink = get_category_link($cat_ID);
$hadesRefrubrique = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HADES, true);

if ($hadesRefrubrique) {
    $hadesRepository = new HadesRepository();
    switch ($hadesRefrubrique) {
        case 'hebergements':
            $filtres = Hades::LOGEMENTS;
            $fiches = $hadesRepository->getHebergements();
            break;
        case 'restaurations':
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

    return;
}

$wpRepository = new WpRepository();

$children = $wpRepository->getChildrenOfCategory($cat_ID);
$posts = $wpRepository->getPostsByCatId($cat_ID);
$parent = $wpRepository->getParentCategory($cat_ID);

if ($parent) {
    $urlBack = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => $category->name,
        'posts' => $posts,
    ]
);

get_footer();

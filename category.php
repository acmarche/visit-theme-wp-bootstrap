<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();dump($cat_ID);
$category = get_category($cat_ID);
$description = category_description($cat_ID);
$title = single_cat_title('', false);
$permalink = get_category_link($cat_ID);

$wpRepository = new WpRepository();
$categoryUtils = new HadesFiltres();
$translator = LocaleHelper::iniTranslator();
$language = LocaleHelper::getSelectedLanguage();

$parent = $wpRepository->getParentCategory($cat_ID);

$urlBack = '/'.$language;
$nameBack = $translator->trans('menu.home');

if ($parent) {
    $urlBack = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

$children = $wpRepository->getChildrenOfCategory($cat_ID);dump($children);
if (count($children) > 0) {

    Twig::rendPage(
        'category/select_child.html.twig',
        [
            'title' => $category->name,
            'category' => $category,
            'urlBack' => $urlBack,
            'nameBack' => $nameBack,
            'children' => $children,
        ]
    );

    get_footer();

    return;
}

$filtres = $categoryUtils->getCategoryFilters($cat_ID);
dump($filtres);
if (count($filtres) > 0) {
    $hadesRepository = new HadesRepository();
    $offres = $hadesRepository->getOffres(array_keys($filtres));
    array_map(
        function ($offre) use ($cat_ID) {
            $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
        },
        $offres
    );

    wp_enqueue_script(
        'react-app',
        get_template_directory_uri().'/assets/js/build/offre.js',
        array('wp-element'),
        wp_get_theme()->get('Version'),
        true
    );

    Twig::rendPage(
        'category/index_hades.html.twig',
        [
            'urlBack' => $urlBack,
            'nameBack' => $nameBack,
            'category' => $category,
            'filtres' => $filtres,
            'offres' => $offres,
            'title' => $title,
            'permalink' => $permalink,
        ]
    );

    get_footer();

    return;
}

$posts = $wpRepository->getPostsByCatId($cat_ID);

Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => $category->name,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'posts' => $posts,
        'children' => $children,
    ]
);

get_footer();

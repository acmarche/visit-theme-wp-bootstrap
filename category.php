<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = category_description($cat_ID);
$title = single_cat_title('', false);
$permalink = get_category_link($cat_ID);
$filtresString = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HADES, true);
$language = LocaleHelper::getSelectedLanguage();

if ($filtresString) {

    $hadesRepository = new HadesRepository();
    $all = Hades::allCategories();
    $filtres = $all[$filtresString] ?? explode(',', $filtresString);

    $categoryUtils = new CategoryUtils();
    $filtres = $categoryUtils->translateFiltres($filtres, $language);

    if (count($filtres) > 0) {
        $offres = $hadesRepository->getOffres($filtres);
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
                'category' => $category,
                'referenceHades' => $filtresString,
                'filtres' => $filtres,
                'offres' => $offres,
                'title' => $title,
                'permalink' => $permalink,
            ]
        );

        get_footer();

        return;
    }
}

$wpRepository = new WpRepository();

$children = $wpRepository->getChildrenOfCategory($cat_ID);
$posts = $wpRepository->getPostsByCatId($cat_ID);
$parent = $wpRepository->getParentCategory($cat_ID);


$translator = LocaleHelper::iniTranslator();

$urlBack = '/'.$language;
$nameBack = $translator->trans('menu.home');
if ($parent) {
    $urlBack = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => $category->name,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'posts' => $posts,
        'url' => '',
    ]
);

get_footer();

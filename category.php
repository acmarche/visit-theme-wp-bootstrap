<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use AcSort;
use SortLink;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
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

$filtres = $categoryUtils->getCategoryFilters($cat_ID);

$posts = $wpRepository->getPostsByCatId($cat_ID);
$category_order = get_term_meta($cat_ID, 'acmarche_category_sort', true);
if ($category_order == 'manual') {
    $posts = AcSort::getSortedItems($cat_ID, $posts);
}
$header = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HEADER, true);
if ($header) {
    $header = '/wp-content/themes/visitmarche/assets/tartine/rsc/img/'.$header;
}
$icone = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_ICONE, true);
if ($icone) {
    $icone = '/wp-content/themes/visitmarche/assets/images/'.$icone;
}

if (count($filtres) > 0) {
    $hadesRepository = new HadesRepository();
    $offres = $hadesRepository->getOffres(array_keys($filtres));
    array_map(
        function ($offre) use ($cat_ID, $language) {
            $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
            $offre->titre = $offre->getTitre($language);
        },
        $offres
    );
    //fusion offres et articles
    $postUtils = new PostUtils();
    $posts = $postUtils->convert($posts);
    $offres = $postUtils->convertOffres($offres, $cat_ID, $language);
    $offres = array_merge($posts, $offres);

    $filtres = RouterHades::setRoutesToFilters($filtres);

    /*  wp_enqueue_script(
          'react-app',
          get_template_directory_uri().'/assets/js/build/offre.js',
          array('wp-element'),
          wp_get_theme()->get('Version'),
          true
      );*/


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
            'header' => $header,
            'icone' => $icone,
        ]
    );

    get_footer();

    return;
}

$sortLink = SortLink::linkSortArticles($cat_ID);
Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => $category->name,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'posts' => $posts,
        'sortLink' => $sortLink,
        'header' => $header,
        'icone' => $icone,
    ]
);

get_footer();

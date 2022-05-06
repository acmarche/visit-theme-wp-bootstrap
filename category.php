<?php

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcSort;
use Psr\Cache\InvalidArgumentException;
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
$categoryName = single_cat_title('', false);
$permalink = get_category_link($cat_ID);

$wpRepository = new WpRepository();
$translator = LocaleHelper::iniTranslator();
$language = LocaleHelper::getSelectedLanguage();

$parent = $wpRepository->getParentCategory($cat_ID);

$urlBack = '/'.$language;
$nameBack = $translator->trans('menu.home');

if ($parent) {
    $urlBack = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

$posts = $wpRepository->getPostsByCatId($cat_ID);
$category_order = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_ORDER, true);
if ('manual' === $category_order) {
    $posts = AcSort::getSortedItems($cat_ID, $posts);
}
$header = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HEADER, true);
$icone = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_ICONE, true);
$bgcat = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_COLOR, true);
if ($header) {
    $header = '/wp-content/themes/visitmarche/assets/tartine/rsc/img/'.$header;
}

if ($icone) {
    $icone = '/wp-content/themes/visitmarche/assets/images/'.$icone;
}
$filtreParam = $_GET['filtre'] ?? null;
$filterRepository = PivotContainer::getFiltreRepository();
if ($filtreParam) {
    $filtres = $filterRepository->findByReferencesOrUrns([$filtreParam]);

    if (count($filtres) > 0) {
        $categoryName = $filtres[0]->labelByLanguage($language);
    }
} else {
    $filtres = $wpRepository->getCategoryFilters($cat_ID, true);
}
if ([] !== $filtres) {
    $filtres = RouterHades::setRoutesToFilters($filtres, $cat_ID);
    $pivotRepository = PivotContainer::getRepository();
    $offres = [];
    try {
        $offres = $pivotRepository->getOffres($filtres);
        array_map(
            function ($offre) use ($cat_ID, $language) {
                $offre->url = RouterHades::getUrlOffre($offre, $cat_ID);
            },
            $offres
        );
    } catch (InvalidArgumentException $e) {
        dump($e->getMessage());
    }

    //fusion offres et articles
    $postUtils = new PostUtils();
    $posts = $postUtils->convertPostsToArray($posts);
    $offres = $postUtils->convertOffres($offres, $cat_ID, $language);
    $offres = array_merge($posts, $offres);

    wp_enqueue_script(
        'react-app',
        get_template_directory_uri().'/assets/js/build/offre.js',
        ['wp-element'],
        wp_get_theme()->get('Version'),
        true
    );

    Twig::rendPage(
        'category/index_hades.html.twig',
        [
            'title' => $categoryName,
            'urlBack' => $urlBack,
            'filterParam' => $filtreParam,
            'nameBack' => $nameBack,
            'category' => $category,
            'filtres' => $filtres,
            'offres' => $offres,
            'permalink' => $permalink,
            'header' => $header,
            'icone' => $icone,
            'bgcat' => $bgcat,
        ]
    );

    get_footer();

    return;
}

$sortLink = SortLink::linkSortArticles($cat_ID);
Twig::rendPage(
    'category/index.html.twig',
    [
        'title' => $categoryName,
        'category' => $category,
        'urlBack' => $urlBack,
        'nameBack' => $nameBack,
        'posts' => $posts,
        'sortLink' => $sortLink,
        'header' => $header,
        'icone' => $icone,
        'bgcat' => $bgcat,
    ]
);

get_footer();

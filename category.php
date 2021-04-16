<?php


namespace AcMarche\Theme;

use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = category_description($cat_ID);
$title = single_cat_title('', false);
$permalink = get_category_link($cat_ID);

$wpRepository = new WpRepository();
$categoryUtils = new HadesFiltres();

$children = $wpRepository->getChildrenOfCategory($cat_ID);
if(count($children) > 0) {

}



$posts = $wpRepository->getPostsByCatId($cat_ID);
$parent = $wpRepository->getParentCategory($cat_ID);

$translator = LocaleHelper::iniTranslator();

$language = LocaleHelper::getSelectedLanguage();
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

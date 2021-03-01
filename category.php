<?php


namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$cat_ID = get_queried_object_id();
$hadesRefrubrique = get_term_meta($cat_ID, CategoryMetaBox::KEY_NAME_HADES, true);
switch ($hadesRefrubrique) {
    case 'hebergements':

        break;
}

$wpRepository = new WpRepository();

$category = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title = single_cat_title('', false);

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

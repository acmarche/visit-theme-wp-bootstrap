<?php


namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Common\WpRepository;


get_header();

$wpRepository = new WpRepository();

$cat_ID      = get_queried_object_id();
$category    = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title       = single_cat_title('', false);


$children = $wpRepository->getChildrenOfCategory($cat_ID);
$posts    = $wpRepository->getPostsAndFiches($cat_ID);
$parent   = $wpRepository->getParentCategory($cat_ID);


if ($parent) {
    $urlBack  = get_category_link($parent->term_id);
    $nameBack = $parent->name;
}

wp_enqueue_script(
    'react-app',
    get_template_directory_uri().'/assets/js/build/category.js',
    array('wp-element'),
    wp_get_theme()->get('Version'),
    true
);

Twig::rendPage(
    'category/index.html.twig',
    [

    ]
);

get_footer();

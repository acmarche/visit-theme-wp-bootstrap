<?php


namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Common\WpRepository;
use AcMarche\Theme\Inc\Theme;

get_header();

$wpRepository = new WpRepository();

$cat_ID      = get_queried_object_id();
$category    = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title       = single_cat_title('', false);

$blodId   = get_current_blog_id();
$path     = Theme::getPathBlog($blodId);
$siteSlug = Theme::getTitleBlog($blodId);
$color    = Theme::getColorBlog($blodId);
$blogName = Theme::getTitleBlog($blodId);

$children = $wpRepository->getChildrenOfCategory($cat_ID);
$posts    = $wpRepository->getPostsAndFiches($cat_ID);
$parent   = $wpRepository->getParentCategory($cat_ID);
$urlBack  = $path;
$nameBack = $blogName;

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
        'title'       => $title,
        'category'    => $category,
        'siteSlug'    => $siteSlug,
        'color'       => $color,
        'blogName'    => $blogName,
        'path'        => $path,
        'subTitle'    => 'Tout',
        'description' => $description,
        'children'    => $children,
        'posts'       => $posts,
        'category_id' => $cat_ID,
        'urlBack'     => $urlBack,
        'nameBack'    => $nameBack,
    ]
);

get_footer();

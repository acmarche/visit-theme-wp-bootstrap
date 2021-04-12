<?php
/**
 * Template Name: Page-Offres
 */

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\Twig;

get_header();

$categoryUtils = new CategoryUtils();
$categoryUtils->setCounts();
$categories = $categoryUtils->categories;
$notEmpty = isset($_GET['notempty']) ?? false;
if ($notEmpty) {
    $categories = $categoryUtils->getCategoriesNotEmpty();
}

$currentUrl = Router::getCurrentUrl();
$category = get_category_by_slug('offres');
$categoryUrl = get_category_link($category);
Twig::rendPage(
    'offre/list.html.twig',
    [
        'url' => '',
        'currentUrl' => $currentUrl,
        'categories' => $categories,
        'categoryUrl' => $categoryUrl,
    ]
);

get_footer();

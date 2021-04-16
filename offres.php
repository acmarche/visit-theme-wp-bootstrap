<?php
/**
 * Template Name: Page-Offres
 */

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Pivot\Filtre\HadesFiltres;
use VisitMarche\Theme\Lib\Twig;

get_header();

$categoryUtils = new HadesFiltres();
$categoryUtils->setCounts();
$categories = isset($_GET['notempty']) ? $categoryUtils->getFiltresNotEmpty() : $categoryUtils->filtres;

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

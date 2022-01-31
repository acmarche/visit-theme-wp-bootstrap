<?php
/**
 * Template Name: Page-Offres.
 */

namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Lib\HadesFiltresTheme;
use VisitMarche\Theme\Lib\Router;
use VisitMarche\Theme\Lib\Twig;

get_header();

$categoryUtils = new HadesFiltresTheme();
$hadesRepository = new HadesRepository();
//$tree = $hadesRepository->getTree();
//$categoryUtils->setCounts($tree);
$filters = isset($_GET['notempty']) ? $categoryUtils->getFiltresNotEmpty() : $hadesRepository->getTree();

$currentUrl = Router::getCurrentUrl();
$category = get_category_by_slug('offres');
$categoryUrl = get_category_link($category);

Twig::rendPage(
    'offre/list.html.twig',
    [
        'url' => '',
        'currentUrl' => $currentUrl,
        'filters' => $filters,
        'categoryUrl' => $categoryUrl,
    ]
);

get_footer();

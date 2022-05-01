<?php
/**
 * Template Name: Page-Offres.
 */

namespace AcMarche\Theme;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Lib\HadesFiltresListing;
use VisitMarche\Theme\Lib\Router;
use VisitMarche\Theme\Lib\Twig;

get_header('tailwind');

$pivotRepository = PivotContainer::getFiltreRepository();
$filters = $pivotRepository->findWithChildren();

$categoryUtils = new HadesFiltresListing();
//$categoryUtils->setCounts($filters);
if (isset($_GET['notempty'])) {
    $categoryUtils->getFiltresNotEmpty($filters);
}

$currentUrl = Router::getCurrentUrl();
$category = get_category_by_slug('offres');
dump($category);
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

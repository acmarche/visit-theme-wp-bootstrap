<?php
/**
 * Template Name: Page-Offres
 */

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Pivot\Repository\HadesRemoteRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\Twig;

get_header();

$hadesRemoteRepository = new HadesRemoteRepository();
$categoryUtils = new CategoryUtils();
$hadesRepository = new HadesRepository();
$notEmpty = isset($_GET['notempty']) ?? false;
$categories = $categoryUtils->categories;
$i = 0;
foreach ($categories as $category) {
    if ($category->category_id) {
        $count = $hadesRepository->countOffres($category->category_id);
        $category->count = $count;
    }
    if ($notEmpty) {
        if (isset($count) && $count == 0) {
            unset($categories[$i]);
        }
    }
    if (preg_match("#Economie#", $category->lvl1)) {
        //break;
    }
    $i++;
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

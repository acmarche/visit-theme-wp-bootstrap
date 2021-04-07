<?php
/**
 * Template Name: Page-Offres
 */
namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRemoteRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\Twig;

get_header();

$hadesRemoteRepository = new HadesRemoteRepository();
$categoryUtils = new CategoryUtils();
$hadesRepository = new HadesRepository();

$closeLvl1 = $closeLvl2 = $closeLvl3 = false;
$categoryUtils->initLvl();
$categories = $categoryUtils->categories;
foreach ($categories as $category) {

    if ($category->category_id) {
        $count = $hadesRepository->countOffres($category->category_id);
        dump($count);
        $category->count = $count;
    }

    if (preg_match("#Economie#", $category->lvl1)) {
        break;
    }
}

Twig::rendPage(
    'offre/list.html.twig',
    [
        'url' => '',
        'categories' => $categories,
    ]
);

//$offres = $hadesRepository->getOffres();
//dump($offres);


get_footer();

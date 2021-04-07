<?php

namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Pivot\Repository\HadesRemoteRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$hadesRemoteRepository = new HadesRemoteRepository();
$categoryUtils = new CategoryUtils();
$tree = [];
$first = true;
$lvl2 = $lvl3 = false;
$categoryUtils->initLvl();
foreach ($categoryUtils->categories as $category) {

    if ($category->lvl1) {
        if ($lvl2) {
            $categoryUtils->finishLvl();
            dump($categoryUtils->lvl);
            $categoryUtils->initLvl();
            $lvl2 = false;
        }
        $categoryUtils->lvl['name'] = $category->lvl1;
    }

    if ($category->lvl2) {
        $lvl2 = true;
        if ($lvl3) {
            $categoryUtils->finishLvl3();
            $lvl3 = false;
        }
        $categoryUtils->addLevel2($category);
    }

    if ($category->lvl3) {
        $lvl3 = true;
        $categoryUtils->addLevel3($category);
    }

    if (preg_match("#Economie#", $category->lvl1)) {
        break;
    }
}


//$offres = $hadesRepository->getOffres();
//dump($offres);


get_footer();

<?php


namespace AcMarche\Theme;

use AcMarche\Common\Router;
use AcMarche\Common\Twig;
use AcMarche\Common\WpRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use Exception;
use VisitMarche\Theme\Inc\RouterHades;

get_header();

global $wp_query;
$codeCgt = $wp_query->get(RouterHades::PARAM_OFFRE);
dump($codeCgt);

$hadesRepository = new HadesRepository();
try {
    $offre = $hadesRepository->getOffre($codeCgt);
    dump($offre);
} catch (Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

Twig::rendPage(
    'offre/show.html.twig',
    [
        'offre' => $offre,
    ]
);
get_footer();

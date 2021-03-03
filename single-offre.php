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

$hadesRepository = new HadesRepository();
try {
    $offre = $hadesRepository->getOffre($codeCgt);
    // dump($offre);
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

$image = null;
$images = $offre->medias;
if (count($images) > 0) {
    $image = $images[0]->url;
}
$tags = [];
foreach ($offre->categories as $category) {
    $tags[] = ['name' => $category->lib, 'url' => RouterHades::getUrlEventCategory($category)];
}

$contact = $offre->contactPrincipal();
$communication = $offre->communcationPrincipal();
//dump($offre->medias);

Twig::rendPage(
    'offre/show.html.twig',
    [
        'title'=>$offre->titre,
        'offre' => $offre,
        'image' => $image,
        'contact' => $contact,
        'communication' => $communication,
        'tags' => $tags,
        'images' => $images,
        'latitude' => $offre->geocode->latitude() ?? null,
        'longitude' => $offre->geocode->longitude() ?? null,
    ]
);
get_footer();

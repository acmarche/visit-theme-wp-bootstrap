<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use AcMarche\Common\Twig;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\WpRepository;
use AcMarche\Pivot\Repository\HadesRepository;

/**
 * Template Name: Home-Page-Principal
 */
get_header();

$wpRepository = new WpRepository();
$hadesRepository = new HadesRepository();

$inspirationCat = $wpRepository->getCategoryBySlug('inspirations');
$inspirations = [];
$urlInspiration = '';
if ($inspirationCat) {
    $urlInspiration = get_category_link($inspirationCat);
    $inspirations = $wpRepository->getPostsByCatId($inspirationCat->cat_ID);
}

try {
    $events = $hadesRepository->getEvents();
    array_map(
        function ($event) {
            $event->url = RouterHades::getUrlOffre($event, RouterHades::EVENT_URL);
        },
        $events
    );
} catch (\Exception $exception) {
    $events = [];
    Mailer::sendError("Erreur de chargement de l'agenda", $exception->getMessage());
}

$urlAgenda = '';
$agendaCat = $wpRepository->getCategoryBySlug('agenda');
if ($agendaCat) {
    $urlAgenda = get_category_link($agendaCat);
}

Twig::rendPage(
    'homepage/index.html.twig',
    [
        'events' => $events,
        'inspirations' => $inspirations,
        'urlAgenda' => $urlAgenda,'urlInspiration'=>$urlInspiration
    ]
);

get_footer();

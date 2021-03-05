<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use VisitMarche\Theme\Lib\Twig;
use Exception;
use VisitMarche\Theme\Inc\Menu;
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

$categoryAgenda = get_category_by_slug('agenda');
$urlAgenda = '/';

try {
    $events = $hadesRepository->getEvents($categoryAgenda->cat_ID);
    if ($categoryAgenda) {
        $urlAgenda = get_category_link($categoryAgenda);
        array_map(
            function ($event) use ($categoryAgenda) {

                $event->url = RouterHades::getUrlEvent($event, $categoryAgenda->cat_ID);
            },
            $events
        );
    }
} catch (Exception $exception) {
    $events = [];
    Mailer::sendError("Erreur de chargement de l'agenda", $exception->getMessage());
}

$menu = new Menu();
$items = $menu->getMenuTop();

Twig::rendPage(
    'homepage/index.html.twig',
    [
        'events' => $events,
        'inspirations' => $inspirations,
        'urlAgenda' => $urlAgenda,
        'urlInspiration' => $urlInspiration,
        'items' => $items,
        'icones' => $menu->getIcones(),
    ]
);

get_footer();

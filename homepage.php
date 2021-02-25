<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use AcMarche\Common\Twig;
use VisitMarche\Theme\Lib\WpRepository;
use AcMarche\Pivot\Repository\HadesRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Template Name: Home-Page-Principal
 */
get_header();

$wpRepository = new WpRepository();
$hadesRepository = new HadesRepository();

$inspirationCat = $wpRepository->getCategoryBySlug('inspirations');
$inspirations = [];
if ($inspirationCat) {
    $inspirations = $wpRepository->getPostsByCatId($inspirationCat->cat_ID);
}

try {
    $events = $hadesRepository->getEvents();
} catch (\Exception $exception) {
    $events = [];
    Mailer::sendError("Erreur de chargement de l'agenda", $exception->getMessage());
}

Twig::rendPage(
    'homepage/index.html.twig',
    [
        'events' => $events,
        'inspirations' => $inspirations,
    ]
);

get_footer();

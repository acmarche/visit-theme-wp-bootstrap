<?php
/**
 * Template Name: Home-Page-Principal.
 */

namespace AcMarche\Theme;

use AcMarche\Pivot\Repository\HadesRepository;
use AcSort;
use Exception;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\Menu;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

get_header();

$language = LocaleHelper::getSelectedLanguage();
$wpRepository = new WpRepository();
$hadesRepository = new HadesRepository();

$inspirationCat = $wpRepository->getCategoryBySlug('inspirations');
$inspirations = [];
$urlInspiration = '';
if ($inspirationCat) {
    $urlInspiration = get_category_link($inspirationCat);
    $inspirations = $wpRepository->getPostsByCatId($inspirationCat->cat_ID);
    $category_order = get_term_meta($inspirationCat->cat_ID, CategoryMetaBox::KEY_NAME_ORDER, true);
    if ('manual' === $category_order) {
        $inspirations = AcSort::getSortedItems($inspirationCat->cat_ID, $inspirations);
    }
}

$categoryAgenda = get_category_by_slug('agenda');
$urlAgenda = '/';

try {
    $events = $hadesRepository->getEvents();
    if ($categoryAgenda) {
        $urlAgenda = get_category_link($categoryAgenda);
        array_map(
            function ($event) use ($categoryAgenda) {
                $event->url = RouterHades::getUrlEvent($event, $categoryAgenda->cat_ID);
            },
            $events
        );
    }
} catch (Exception) {
    $events = [];
    //  Mailer::sendError("Erreur de chargement de l'agenda", $exception->getMessage());
}

$intro = $wpRepository->getIntro();
$menu = new Menu();
$icones = $menu->getIcones();

Twig::rendPage(
    'homepage/index.html.twig',
    [
        'events' => $events,
        'inspirations' => $inspirations,
        'urlAgenda' => $urlAgenda,
        'urlInspiration' => $urlInspiration,
        'icones' => $icones,
        'language' => $language,
        'intro' => $intro,
    ]
);

get_footer();

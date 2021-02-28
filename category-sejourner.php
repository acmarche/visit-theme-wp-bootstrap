<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Repository\HadesRepository;
use Psr\Cache\InvalidArgumentException;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title = single_cat_title('', false);

$hadesRepository = new HadesRepository();
try {
    $hotels = $hadesRepository->getHotels();
} catch (InvalidArgumentException $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les hébergements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

//dump($hotels);

Twig::rendPage(
    'hebergement/index.html.twig',
    [
        'hebergements' => $hotels,
        'titre' => $title,
    ]
);

get_footer();

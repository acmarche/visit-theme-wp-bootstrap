<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Repository\HadesRepository;
use Psr\Cache\InvalidArgumentException;

get_header();

$hadesRepository = new HadesRepository();
try {
    $hotels = $hadesRepository->getHotels();
} catch (InvalidArgumentException $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les évènements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

dump($hotels);

Twig::rendPage(
    'hebergement/index.html.twig',
    [
        'hebergements' => $hotels,
    ]
);

get_footer();

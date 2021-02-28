<?php

namespace AcMarche\Theme;

use AcMarche\Common\Twig;
use AcMarche\Pivot\Repository\HadesRepository;
use Exception;

get_header();

$cat_ID = get_queried_object_id();
$category = get_category($cat_ID);
$description = $description = category_description($cat_ID);
$title = single_cat_title('', false);

$hadesRepository = new HadesRepository();
try {
    $hebergements = $hadesRepository->getHebergements();
} catch (Exception $e) {
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => 'Impossible de charger les hébergements: '.$e->getMessage(),
        ]
    );
    get_footer();

    return;
}

dump($hebergements);

Twig::rendPage(
    'hebergement/index.html.twig',
    [
        'hebergements' => $hebergements,
        'title' => $title,
    ]
);

get_footer();

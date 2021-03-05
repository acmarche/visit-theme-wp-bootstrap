<?php

namespace AcMarche\Theme;

use AcMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Inc\Menu;

$menu = new Menu();
$items = $menu->getMenuTop();

Twig::rendPage(
    'footer/footer.html.twig',
    [
        'items' => $items,
        'icones' => $menu->getIcones(),
    ]
);
wp_footer();
Twig::rendPage(
    'footer/_closte_tags.html.twig'
);

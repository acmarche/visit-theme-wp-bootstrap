<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Inc\Menu;
use VisitMarche\Theme\Inc\Theme;

$locale = LocaleHelper::getSelectedLanguage();
?>
    <!DOCTYPE html>
<html lang="<?php echo $locale ?>">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Nucleïd">
        <meta name="author" content="Cst">
        <?php
        Twig::rendPage('header/_favicons.html.twig');
        Twig::rendPage('header/_facebook_pub.html.twig');
        wp_head();
        require_once '_analytics.php';
        ?>
    </head>
    <body class="bg-white">
    <?php
wp_body_open();
$menu = new Menu();
$items = $menu->getMenuTop();
$icones = $menu->getIcones();

if (Theme::isHomePage()) {
    Twig::rendPage(
        'header/_header_home.html.twig',
        [
            'items' => $items,
            'icones' => $icones,
        ]
    );
} elseif (get_query_var(RouterHades::PARAM_OFFRE)) {
    Twig::rendPage(
        'header/_header_offre.html.twig',
        [
            'items' => $items,
            'icones' => $icones,
        ]
    );
} else {
    Twig::rendPage(
        'header/_header.html.twig',
        [
            'items' => $items,
            'icones' => $icones,
        ]
    );
}

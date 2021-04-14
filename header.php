<?php

namespace AcMarche\Theme;

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
        wp_head();
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
} else {
    Twig::rendPage(
        'header/_header.html.twig',
        [
            'items' => $items,
            'icones' => $icones,
        ]
    );
}

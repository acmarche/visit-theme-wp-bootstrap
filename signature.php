<?php
/**
 * Template Name: Signature.
 */

namespace AcMarche\Theme;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use VisitMarche\Theme\Lib\Twig;

// create instance
$cssToInlineStyles = new CssToInlineStyles();
$twig = new Twig();

$html = $twig->contentPage('signatures/heidi.html.twig');
$css = file_get_contents('https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css');
// output
echo $cssToInlineStyles->convert(
    $html,
    $css
);

/*Twig::rendPage(
    'signatures/heidi.html.twig',
    [

    ]
);*/

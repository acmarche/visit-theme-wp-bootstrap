<?php

namespace AcMarche\Signature;

require_once 'vendor/autoload.php';

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

// create instance
$cssToInlineStyles = new CssToInlineStyles();

$html = file_get_contents('signature.html');
$css = file_get_contents('dist/tailwind.css');
// output
echo $cssToInlineStyles->convert(
    $html,
    $css
);

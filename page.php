<?php

namespace AcMarche\Theme;


use VisitMarche\Theme\Lib\Twig;

get_header();
global $post;

$categories = get_the_category($post->ID);
$url        = get_permalink($post->ID);
$image      = null;
$blodId     = get_current_blog_id();

if (has_post_thumbnail()) {
    $images = wp_get_attachment_image_src(get_post_thumbnail_id(), 'original');
    if ($images) {
        $image = $images[0];
    }
}

$content   = get_the_content(null, null, $post);
$content   = apply_filters('the_content', $content);
$content   = str_replace(']]>', ']]&gt;', $content);
$relations = [];
$urlBack   = '/';

Twig::rendPage(
    'article/page.html.twig',
    [

    ]
);
get_footer();

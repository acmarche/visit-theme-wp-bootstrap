<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Twig;

get_header();
global $post;

if(!$post) {

    Twig::rendPage(
        'errors/404.html.twig',
        [
            'url' => '',
            'title'=>'Page non trouvée'
        ]
    );

    get_footer();

    return;
}

$image = null;
if (has_post_thumbnail()) {
    $images = wp_get_attachment_image_src(get_post_thumbnail_id(), 'original');
    if ($images) {
        $image = $images[0];
    }
}

$tags = [];
$next = null;

$content = get_the_content(null, null, $post);
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);
$recommandations = [];

Twig::rendPage(
    'article/page.html.twig',
    [
        'post' => $post,
        'tags' => $tags,
        'image' => $image,
        'title' => $post->post_title,
        'recommandations' => $recommandations,
        'content' => $content,
        'next' => $next,
    ]
);
get_footer();

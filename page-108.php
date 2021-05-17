<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use VisitMarche\Theme\Lib\Twig;

try {
    return  new RedirectResponse('/fr');
} catch (\InvalidArgumentException $exception) {
    Mailer::sendError('redirect fr', 'visit marche');
    get_header();
    global $post;

    if (!$post) {

        Twig::rendPage(
            'errors/404.html.twig',
            [
                'url' => '',
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
    $relations = [];

    Twig::rendPage(
        'article/page.html.twig',
        [
            'post' => $post,
            'tags' => $tags,
            'image' => $image,
            'title' => $post->post_title,
            'relations' => $relations,
            'content' => $content,
            'next' => $next,
        ]
    );
    get_footer();
}


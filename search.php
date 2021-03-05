<?php

namespace AcMarche\Theme;

use AcMarche\Theme\Lib\Twig;

get_header();

global $wp_query;

$keyword = get_search_query();

$count = (int)$wp_query->found_posts;
$query = esc_html(get_search_query());
$posts = $wp_query->posts;
array_map(
    function ($post) {
        $post->url = get_permalink($post);
    },
    $posts
);

Twig::rendPage(
    'search/index.html.twig',
    [
        'query' => $keyword,
        'posts' => $posts,
        'count' => $count,
    ]
);

get_footer();

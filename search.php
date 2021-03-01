<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use AcMarche\Common\Twig;
use AcMarche\Elasticsearch\Searcher;
use \Exception;

get_header();

global $wp_query;

$keyword = get_search_query();

$count = (int)$wp_query->found_posts;
$query = esc_html(get_search_query());

Twig::rendPage(
    'search/index.html.twig',
    [
        'query' => $keyword,
        'posts' => $wp_query->posts,
        'count' => $count,
    ]
);

get_footer();

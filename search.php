<?php

namespace AcMarche\Theme;

use VisitMarche\Theme\Lib\Mailer;
use VisitMarche\Theme\Lib\Elasticsearch\Searcher;
use VisitMarche\Theme\Lib\Twig;

get_header();

$searcher = new Searcher();
$keyword = get_search_query();
$results = $searcher->searchFromWww($keyword);
$hits = json_decode($results);

if (isset($hits['error'])) {
    Mailer::sendError("wp error search", $hits['error']);
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => $hits['error'],
            'title' => 'Erreur lors de la recherche',
            'tags' => [],
            'relations' => [],
        ]
    );
    get_footer();

    return;
}

Twig::rendPage(
    'search/index.html.twig',
    [
        'keyword' => $keyword,
        'hits' => $hits,
        'count' => count($hits),
    ]
);

get_footer();

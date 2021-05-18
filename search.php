<?php

namespace AcMarche\Theme;

use AcMarche\Common\Mailer;
use Exception;
use VisitMarche\Theme\Lib\Elasticsearch\Searcher;
use VisitMarche\Theme\Lib\Twig;

get_header();

$searcher = new Searcher();
$keyword = get_search_query();
$hits = [];
try {
    $searching = $searcher->searchFromWww($keyword);
    dump($searching);
    $results = $searching->getResults();
    $count = $searching->count();
    foreach ($results as $result) {
        $hit = $result->getHit();
        $hits[] = $hit['_source'];
    }
} catch (Exception $e) {
    Mailer::sendError("wp error search", $e->getMessage());
    Twig::rendPage(
        'errors/500.html.twig',
        [
            'message' => $e->getMessage(),
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
        'count' => $count,
    ]
);

get_footer();

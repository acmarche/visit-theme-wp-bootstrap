<?php

namespace VisitMarche\Theme\Lib\Elasticsearch;

use Elastica\Exception\InvalidException;
use Elastica\ResultSet;

/**
 * https://github.com/ruflin/Elastica/tree/master/tests
 * Class Searcher
 *
 */
class Searcher
{
    public function searchFromWww(string $keyword)
    {
        $content = file_get_contents(
            'https://www.marche.be/visit-elasticsearch/search.php?keyword='.urlencode($keyword)
        );

        return $content;
    }

    /**
     * @param string $keywords
     *
     * @return ResultSet
     * @throws  InvalidException
     */
    public function searchRecommandations(\WP_Query $wp_query): array
    {
        $hits = [];

        $queries = $wp_query->query;
        $queryString = join(' ', $queries);
        $queryString = preg_replace("#-#", " ", $queryString);
        $queryString = preg_replace("#/#", " ", $queryString);
        $queryString = strip_tags($queryString);
        if ($queryString != '') {
            $results = $this->searchFromWww($queryString);
            $hits = json_decode($results);
        }

        dump($hits);
        $recommandations = array_map(
            function ($recommandation) {
                $recommandation['title'] = $recommandation['name'];
                $recommandation['tags'] = [];

                return $recommandation;
            },
            $hits
        );

        return $recommandations;
    }
}

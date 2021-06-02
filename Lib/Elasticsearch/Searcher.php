<?php

namespace VisitMarche\Theme\Lib\Elasticsearch;

use AcMarche\Common\Mailer;
use Elastica\Exception\InvalidException;
use Elastica\Query\MultiMatch;
use Elastica\ResultSet;

/**
 * https://github.com/ruflin/Elastica/tree/master/tests
 * Class Searcher
 *
 */
class Searcher
{
    use ElasticClientTrait;

    public function __construct()
    {
        $this->connect();
    }

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
        $resultat = [];
        $queries = $wp_query->query;
        $queryString = join(' ', $queries);
        $queryString = preg_replace("#-#", " ", $queryString);
        $queryString = preg_replace("#/#", " ", $queryString);
        $queryString = strip_tags($queryString);
        if ($queryString != '') {
            try {
                $searching = $this->search($queryString);
                $results = $searching->getResults();
                foreach ($results as $result) {
                    $hit = $result->getHit();
                    $resultat[] = $hit['_source'];
                }
            } catch (\Exception $e) {
                Mailer::sendError("wp error search query 404", $e->getMessage());
            }
        }
        $recommandations = array_map(
            function ($recommandation) {
                $recommandation['title'] = $recommandation['name'];
                $recommandation['tags'] = [];

                return $recommandation;
            },
            $resultat
        );

        return $recommandations;
    }

    /**
     * @param string $keywords
     * @param int $limit
     *
     * @return ResultSet
     */
    public function search(string $keywords, int $limit = 50): ResultSet
    {
        $options = ['limit' => $limit];
        $query = new MultiMatch();
        $query->setFields(
            [
                'name^1.2',
                'name.stemmed',
                'content',
                'content.stemmed',
                'excerpt',
                'tags',
            ]
        );
        $query->setQuery($keywords);
        $query->setType(MultiMatch::TYPE_MOST_FIELDS);

        $result = $this->index->search($query, $options);

        return $result;
    }
}

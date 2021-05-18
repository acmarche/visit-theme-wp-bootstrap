<?php

namespace VisitMarche\Theme\Lib\Elasticsearch;

use AcMarche\Common\Env;
use Elastica\Client;
use Elastica\Index;

trait ElasticClientTrait
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var Index
     */
    private $index;

    public function connect(string $host = 'localhost', int $port = 9200)
    {
        Env::loadEnv();
        $username = $_ENV['ELASTIC_USER'];
        $password = $_ENV['ELASTIC_PASSWORD'];
        $ds= $username.':'.$password.'@'.$host;
        $this->client = new Client(
            [
                'host' => $ds,
                'port' => $port,
            ]
        );
        //$this->client->setLogger(); todo
        $this->setIndex(ElasticServer::INDEX_NAME_VISIT_FR);
    }

    public function setIndex(string $name)
    {
        $this->index = $this->client->getIndex($name);
    }
}

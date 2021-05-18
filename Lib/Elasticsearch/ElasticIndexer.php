<?php

namespace VisitMarche\Theme\Lib\Elasticsearch;

use AcMarche\Common\AcSerializer;
use Elastica\Document;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use VisitMarche\Theme\Lib\Elasticsearch\Data\DocumentElastic;
use VisitMarche\Theme\Lib\Elasticsearch\Data\ElasticData;
use WP_Post;

class ElasticIndexer
{
    use ElasticClientTrait;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ElasticData
     */
    private $elasticData;
    /**
     * @var SymfonyStyle|null
     */
    private $outPut;

    public function __construct(?SymfonyStyle $outPut = null)
    {
        $this->connect();
        $this->serializer = (new AcSerializer())->create();
        $this->elasticData = new ElasticData();
        $this->outPut = $outPut;
    }

    public function indexAllPosts()
    {
        $documentElastics = $this->elasticData->getPosts();
        foreach ($documentElastics as $documentElastic) {
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
           // $this->addPost($documentElastic);
        }
    }

    public function indexPost(WP_Post $post)
    {
        $documentElactic = $this->elasticData->postToDocumentElastic($post);
        if ($documentElactic) {
            if ($this->outPut) {
                $this->outPut->writeln($post->name);
            }
            $this->addPost($documentElactic);
        }
    }

    public function addPost(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = $this->createIdPost($documentElastic->id);
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

    public function deletePost(int $postId)
    {
        $id = $this->createIdPost($postId);
        $this->index->deleteById($id);
    }

    protected function createIdPost(int $postId): string
    {
        return 'post_'.$postId;
    }

    public function indexAllCategories()
    {
        $categories = $this->elasticData->getCategories();
        foreach ($categories as $documentElastic) {
            $this->addCategory($documentElastic);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
        }
    }

    public function indexAllOffres()
    {
        $offres = $this->elasticData->getOffres();
        foreach ($offres as $documentElastic) {
            $this->addOffre($documentElastic);
            if ($this->outPut) {
                $this->outPut->writeln($documentElastic->name);
            }
        }
    }

    private function addCategory(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'category_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

    private function addOffre(DocumentElastic $documentElastic)
    {
        $content = $this->serializer->serialize($documentElastic, 'json');
        $id = 'offre_'.$documentElastic->id;
        $doc = new Document($id, $content);
        $this->index->addDocument($doc);
    }

}

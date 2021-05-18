<?php

namespace VisitMarche\Theme\Lib\Elasticsearch\Data;

use AcMarche\Common\Mailer;
use AcMarche\Pivot\Entities\OffreInterface;
use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\WpRepository;
use WP_Post;

class ElasticData
{
    /**
     * @var WpRepository
     */
    private $wpRepository;

    public function __construct()
    {
        $this->wpRepository = new WpRepository();
    }

    /**
     * @param int $siteId
     *
     * @return DocumentElastic[]
     */
    public function getCategories(string $language = 'fr'): array
    {

        $datas = [];
        $today = new \DateTime();

        foreach ($this->wpRepository->getCategories() as $category) {

            $description = '';
            if ($category->description) {
                $description = Cleaner::cleandata($category->description);
            }

            $date = $today->format('Y-m-d');
            $content = $description;

            foreach ($this->getPosts($category->cat_ID) as $documentElastic) {
                $content .= $documentElastic->name;
                $content .= $documentElastic->excerpt;
                $content .= $documentElastic->content;
            }

            $content .= $this->getContentHades($category, $language);

            $children = $this->wpRepository->getChildrenOfCategory($category->cat_ID);
            $tags = [];
            foreach ($children as $child) {
                $tags[] = $child->name;
            }
            $parent = $this->wpRepository->getParentCategory($category->cat_ID);
            if ($parent) {
                $tags[] = $parent->name;
            }

            $document = new DocumentElastic();
            $document->id = $category->cat_ID;
            $document->name = Cleaner::cleandata($category->name);
            $document->excerpt = $description;
            $document->content = $content;
            $document->tags = $tags;
            $document->date = $date;
            $document->url = get_category_link($category->cat_ID);

            $datas[] = $document;
        }

        return $datas;
    }

    /**
     * @param int|null $categoryId
     *
     * @return DocumentElastic[]
     */
    public function getPosts(int $categoryId = null): array
    {
        $args = array(
            'numberposts' => 5000,
            'orderby' => 'post_title',
            'order' => 'ASC',
            'post_status' => 'publish',
        );

        if ($categoryId) {
            $args ['category'] = $categoryId;
        }

        $posts = get_posts($args);
        $datas = [];

        foreach ($posts as $post) {
            if ($document = $this->postToDocumentElastic($post)) {
                $datas[] = $document;
            } else {
                Mailer::sendError(
                    "update elastic error ",
                    "create document ".$post->post_title
                );
                //  var_dump($post);
            }
        }

        return $datas;
    }

    /**
     * @param int $siteId
     *
     * @return DocumentElastic[]
     */
    public function getOffres(string $language = 'fr'): array
    {
        $datas = [];

        foreach ($this->wpRepository->getCategories() as $category) {

            $categoryUtils = new HadesFiltres();
            $filtres = $categoryUtils->getCategoryFilters($category->cat_ID);

            if (count($filtres) > 0) {
                $hadesRepository = new HadesRepository();
                $offres = $hadesRepository->getOffres(array_keys($filtres));
                array_map(
                    function ($offre) use ($category, $language) {
                        $offre->url = RouterHades::getUrlOffre($offre, $category->cat_ID);
                        $offre->titre = $offre->getTitre($language);
                    },
                    $offres
                );
            }

            foreach ($offres as $offre) {
                $datas[] = $this->createDocumentElasticFromOffre($offre, $language);
            }

        }

        return $datas;
    }

    public function postToDocumentElastic(WP_Post $post): ?DocumentElastic
    {
        try {
            return $this->createDocumentElastic($post);
        } catch (\Exception $exception) {
            Mailer::sendError("update elastic", "create document ".$post->post_title.' => '.$exception->getMessage());
        }

        return null;
    }

    private function createDocumentElastic(WP_Post $post): DocumentElastic
    {
        list($date, $time) = explode(" ", $post->post_date);
        $categories = array();
        foreach (get_the_category($post->ID) as $category) {
            $categories[] = $category->cat_name;
        }

        $content = get_the_content(null, null, $post);
        $content = apply_filters('the_content', $content);

        $document = new DocumentElastic();
        $document->id = $post->ID;
        $document->name = Cleaner::cleandata($post->post_title);
        $document->excerpt = Cleaner::cleandata($post->post_excerpt);
        $document->content = Cleaner::cleandata($content);
        $document->tags = $categories;
        $document->date = $date;
        $document->url = get_permalink($post->ID);

        return $document;
    }

    private function createDocumentElasticFromOffre(OffreInterface $offre, string $language): DocumentElastic
    {
        $categories = array();
        foreach ($offre->categories as $category) {
            $categories[] .= ' '.$category->getLib($language);
        }

        $content = '';
        $offre->description ='';
        if (count($offre->descriptions) > 0) {
            $offre->description = $offre->descriptions[0]->getTexte($language);
            foreach ($offre->descriptions as $description) {
                $content .= ' '.$description->getTexte($language);
            }
        }

        $today = new \DateTime();
        $document = new DocumentElastic();
        $document->id = $offre->id;
        $document->name = Cleaner::cleandata($offre->titre);
        $document->excerpt = Cleaner::cleandata($offre->description);
        $document->content = Cleaner::cleandata($content);
        $document->tags = $categories;
        $document->date = $today->format('Y-m-d');
        $document->url = $offre->url;

        return $document;
    }

    private function getContentHades(\WP_Term $category, string $language): string
    {
        $content = '';
        $categoryUtils = new HadesFiltres();
        $filtres = $categoryUtils->getCategoryFilters($category->cat_ID);

        if (count($filtres) > 0) {
            $hadesRepository = new HadesRepository();
            $offres = $hadesRepository->getOffres(array_keys($filtres));
            array_map(
                function ($offre) use ($category, $language) {
                    $offre->url = RouterHades::getUrlOffre($offre, $category->cat_ID);
                    $offre->titre = $offre->getTitre($language);
                },
                $offres
            );
            foreach ($offres as $offre) {
                $content .= $offre->getTitre($language);
                if (count($offre->descriptions) > 0) {
                    foreach ($offre->descriptions as $description) {
                        $content .= ' '.$description->getTexte($language);
                    }
                }
                foreach ($offre->categories as $category) {
                    $content .= ' '.$category->getLib($language);
                }
            }
        }

        return $content;
    }

}

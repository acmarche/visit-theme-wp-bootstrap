<?php


namespace VisitMarche\Theme\Lib;

use VisitMarche\Theme\Inc\RouterHades;

class PostUtils
{
    /**
     * @var \VisitMarche\Theme\Lib\WpRepository
     */
    private $wpRepository;

    public function __construct()
    {
        $this->wpRepository = new WpRepository();
    }

    /**
     * @param array $posts
     * @return array|array[]
     */
    public function convert(array $posts)
    {
        return array_map(
            function ($post) {
                return $this->postToArray($post);
            },
            $posts
        );

    }

    public function postToArray(\WP_Post $post): array
    {
        $tags = $this->wpRepository->getTags($post->ID);
        $tags = array_map(
            function ($category) {
                return $category['name'];
            },
            $tags
        );

        return [
            'url' => get_permalink($post),
            'titre' => $post->post_title,
            'description' => $post->post_excerpt,
            'tags' => $tags,
            'image' => $this->getImage($post),
        ];
    }

    public static function getImage(\WP_Post $post): ?string
    {
        if (has_post_thumbnail($post)) {
            $images = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'original');
            if ($images) {
                return $images[0];
            }
        }

        return null;
    }

    public function convertOffres(array $offres, int $categoryId, string $language): array
    {
        array_map(
            function ($offre) use ($categoryId, $language) {
                $offre->url = RouterHades::getUrlOffre($offre, $categoryId);
                $offre->titre = $offre->getTitre($language);
                $description = null;
                if (count($offre->descriptions) > 0) {
                    $description = $offre->descriptions[0]->getTexte($language);
                }
                $offre->description = $description;
                $tags = [];
                foreach ($offre->categories as $category) {
                    $tags[] = $category->getLib($language);
                }
                $offre->tags = $tags;
                array_map(
                    function ($category) use ($language) {
                        $category->titre = $category->getLib($language);
                    },
                    $offre->categories
                );
                $offre->image = $offre->firstImage();
            },
            $offres
        );

        return $offres;
    }

}

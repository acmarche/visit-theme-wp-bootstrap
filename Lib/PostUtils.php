<?php

namespace VisitMarche\Theme\Lib;

use VisitMarche\Theme\Inc\RouterHades;
use WP_Post;

class PostUtils
{
    private WpRepository $wpRepository;

    public function __construct()
    {
        $this->wpRepository = new WpRepository();
    }

    /**
     * @param WP_Post[] $posts
     * @return array
     */
    public function convertPostsToArray(array $posts): array
    {
        return array_map(
            fn ($post) => $this->postToArray($post),
            $posts
        );
    }

    public function postToArray(WP_Post $post): array
    {
        $tags = $this->wpRepository->getTags($post->ID);
        $tags = array_map(
            fn ($category) => $category['name'],
            $tags
        );

        return [
            'url' => $post->permalink,
            'titre' => $post->post_title,
            'description' => $post->post_excerpt,
            'tags' => $tags,
            'image' => $post->thumbnail_url,
        ];
    }

    public static function getImage(WP_Post $post): ?string
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
                if ((is_countable($offre->descriptions) ? \count($offre->descriptions) : 0) > 0) {
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

<?php

namespace VisitMarche\Theme\Lib;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entity\TypeOffre;
use AcMarche\Pivot\Spec\UrnList;
use AcMarche\Pivot\Spec\UrnTypeList;
use Doctrine\ORM\NonUniqueResultException;
use VisitMarche\Theme\Inc\PivotMetaBox;
use VisitMarche\Theme\Inc\Theme;
use WP_Post;
use WP_Query;
use WP_Term;

class WpRepository
{
    public function getCategoryBySlug(string $slug)
    {
        return get_category_by_slug($slug);
    }

    public function getTags(int $postId): array
    {
        $tags = [];
        foreach (get_the_category($postId) as $category) {
            $tags[] = [
                'name' => $category->name,
                'url' => get_category_link($category),
            ];
        }

        return $tags;
    }

    /**
     * @param int $catId
     * @return WP_Post[]
     */
    public function getPostsByCatId(int $catId): array
    {
        $args = [
            'cat' => $catId,
            'numberposts' => 5000,
            'orderby' => 'post_title',
            'order' => 'ASC',
            'post_status' => 'publish',
        ];

        $querynews = new WP_Query($args);
        $posts = [];
        while ($querynews->have_posts()) {
            $post = $querynews->next_post();
            $post->excerpt = $post->post_excerpt;
            $post->permalink = get_permalink($post->ID);
            $post->thumbnail_url = $this->getPostThumbnail($post->ID);
            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * @return array|WP_Term|object|\WP_Error|null
     */
    public function getParentCategory(int $cat_ID)
    {
        $category = get_category($cat_ID);

        if ($category) {
            if ($category->parent < 1) {
                return null;
            }

            return get_category($category->parent);
        }

        return null;
    }

    /**
     * @param int $cat_ID
     * @return WP_Term[]
     */
    public function getChildrenOfCategory(int $cat_ID): array
    {
        $args = [
            'parent' => $cat_ID,
            'hide_empty' => false,
        ];
        $children = get_categories($args);
        array_map(
            function ($category) {
                $category->url = get_category_link($category->term_id);
                $category->id = $category->term_id;
            },
            $children
        );

        return $children;
    }

    public function getSamePosts(int $postId, int $max = 3): array
    {
        $categories = get_the_category($postId);
        $args = [
            'category__in' => array_map(
                fn($category) => $category->cat_ID,
                $categories
            ),
            'post__not_in' => [$postId],
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        $query = new \WP_Query($args);
        $recommandations = [];
        foreach ($query->posts as $post) {
            $image = null;
            if (has_post_thumbnail($post)) {
                $images = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'original');
                if ($images) {
                    $image = $images[0];
                }
            }
            $recommandations[] = [
                'title' => $post->post_title,
                'excerpt' => $post->post_excerpt,
                'url' => get_permalink($post->ID),
                'image' => $image,
                'tags' => self::getTags($post->ID),
            ];
        }

        return $recommandations;
    }

    public function getPostThumbnail(int $id): string
    {
        if (has_post_thumbnail($id)) {
            $attachment_id = get_post_thumbnail_id($id);
            $images = wp_get_attachment_image_src($attachment_id, 'original');
            $post_thumbnail_url = $images[0];
        } else {
            $post_thumbnail_url = get_template_directory_uri().'/assets/images/404.jpg';
        }

        return $post_thumbnail_url;
    }

    public function getIntro(): array|string
    {
        $intro = '<p>Intro vide</p>';
        $introId = apply_filters('wpml_object_id', Theme::PAGE_INTRO, 'page', true);
        $pageIntro = get_post($introId);

        if ($pageIntro) {
            $intro = get_the_content(null, null, $pageIntro);
            $intro = apply_filters('the_content', $intro);
            $intro = str_replace(']]>', ']]&gt;', $intro);
            $intro = str_replace('<p>', '', $intro);
            $intro = str_replace('</p>', '', $intro);
        }

        return $intro;
    }

    /**
     * @return array|\WP_Term[]
     */
    public function getCategoriesFromWp(): array
    {
        $args = [
            'type' => 'post',
            'child_of' => 0,
            'parent' => '',
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0,
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'number' => '',
            'taxonomy' => 'category',
            'pad_counts' => true,
        ];

        return get_categories($args);
    }

    /**
     * @param int $categoryWpId
     * @param bool $flatWithChildren pour admin ne pas etendre enfants
     * @param bool $filterCount
     * @return TypeOffre[]|array
     * @throws NonUniqueResultException
     */
    public static function getCategoryFilters(
        int $categoryWpId,
        bool $flatWithChildren = false,
        bool $filterCount = true
    ): array {
        if (in_array($categoryWpId, Theme::CATEGORIES_HEBERGEMENT)) {
            return WpRepository::getChildrenHebergements($filterCount);
        }
        if (in_array($categoryWpId, Theme::CATEGORIES_AGENDA)) {
            return WpRepository::getChildrenEvents($filterCount);
        }
        if (in_array($categoryWpId, Theme::CATEGORIES_RESTAURATION)) {
            return WpRepository::getChildrenRestauration($filterCount);
        }

        $categoryFiltres = PivotMetaBox::getMetaPivotTypesOffre($categoryWpId);
        $typeOffreRepository = PivotContainer::getTypeOffreRepository(WP_DEBUG);
        $allFiltres = [];

        foreach ($categoryFiltres as $dataFiltre) {

            if (!isset($dataFiltre['urn'])) {
                continue;
            }

            $typeOffre = $typeOffreRepository->findOneByUrn($dataFiltre['urn']);
            if (!$typeOffre) {
                continue;
            }

            //bug parent is a proxy
            unset($typeOffre->parent);

            $typeOffre->withChildren = $dataFiltre['withChildren'];
            $allFiltres[] = $typeOffre;

            /**
             * Force a pas prendre enfant
             */
            if ($flatWithChildren) {
                continue;
            }

            if ($dataFiltre['withChildren']) {
                $children = $typeOffreRepository->findByParent($typeOffre->id);
                foreach ($children as $typeOffreChild) {
                    //bug parent is a proxy
                    unset($typeOffreChild->parent);
                    $allFiltres[] = $typeOffreChild;
                }
            }
        }

        if ($filterCount) {
            $filtres = [];
            foreach ($allFiltres as $filtre) {
                if ($filtre->countOffres > 0) {
                    $filtres[] = $filtre;
                }
            }

            return $filtres;
        }

        return $allFiltres;
    }

    /**
     * @return TypeOffre[]
     * @throws NonUniqueResultException|\Exception
     */
    public static function getChildrenEvents(bool $filterCount): array
    {
        $allFiltres = [];
        $pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);
        $filtreRepository = PivotContainer::getTypeOffreRepository(WP_DEBUG);

        $families = $pivotRepository->thesaurusChildren(
            UrnTypeList::evenement()->typeId,
            UrnList::CATEGORIE_EVENT->value
        );

        foreach ($families as $family) {
            $filtres = $filtreRepository->findByUrn($family->urn);
            if (count($filtres) > 0) {
                $filtre = $filtres[0];
                $filtre->children = [];//bug loop infinit
                $allFiltres[] = $filtre;
            }
        }
        if ($filterCount) {
            return self::filterCount($allFiltres);
        }

        return $allFiltres;
    }

    /**
     * @return TypeOffre[]
     * @throws NonUniqueResultException|\Exception
     */
    public static function getChildrenRestauration(bool $filterCount): array
    {
        $allFiltres = [];
        $pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);
        $filtreRepository = PivotContainer::getTypeOffreRepository(WP_DEBUG);

        $families = $pivotRepository->thesaurusChildren(
            UrnTypeList::restauration()->typeId,
            UrnList::CATEGORIE->value
        );

        foreach ($families as $family) {
            $filtres = $filtreRepository->findByUrn($family->urn);
            if (count($filtres) > 0) {
                $filtre = $filtres[0];
                $filtre->children = [];//bug loop infinit
                $allFiltres[] = $filtre;
            }
        }
        if ($filterCount) {
            return self::filterCount($allFiltres);
        }

        return $allFiltres;
    }

    /**
     * @return TypeOffre[]
     * @throws NonUniqueResultException
     */
    public static function getChildrenHebergements(bool $filterCount): array
    {
        $filtreRepository = PivotContainer::getTypeOffreRepository(WP_DEBUG);

        $filtre = $filtreRepository->findOneByUrn(UrnList::HERGEMENT->value);

        $allFiltres = $filtreRepository->findByParent($filtre->id);
        if ($filterCount) {
            return self::filterCount($allFiltres);
        }

        return $allFiltres;
    }

    /**
     * @return TypeOffre[]
     * @throws NonUniqueResultException
     */
    public static function getChildrenTest(): array
    {
        $allFiltres = [];
        $pivotRepository = PivotContainer::getPivotRepository(WP_DEBUG);
        $filtreRepository = PivotContainer::getTypeOffreRepository(WP_DEBUG);

        $families = $pivotRepository->thesaurusChildren(
            UrnTypeList::decouverteEtDivertissement()->typeId,
            UrnList::CATEGORIE_PATRIMOINE_BATI->value
        );

        $filtre = $filtreRepository->findOneByUrn("urn:fld:catdec:patrbati");

        $filtre->children = [];
        $allFiltres[] = $filtre;

        return $allFiltres;
        foreach ($families as $family) {
            $filtres = $filtreRepository->findsByUrn($family->urn);
            if (count($filtres) > 0) {
                $filtre = $filtres[0];
                $filtre->children = [];//bug loop infinit
                $allFiltres[] = $filtre;
            }
        }

        return $allFiltres;
    }

    /**
     * @param array|TypeOffre[] $allFiltres
     * @return array|TypeOffre[]
     */
    private static function filterCount(array $allFiltres): array
    {
        $allFiltres = array_filter($allFiltres, function ($typeOffre) {
            return $typeOffre->countOffres > 0;
        });

        return array_values($allFiltres);//reset keys for js
    }

}

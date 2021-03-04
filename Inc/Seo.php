<?php


namespace VisitMarche\Theme\Inc;


use AcMarche\Pivot\Repository\HadesRepository;

class Seo
{
    static private $metas = ['title' => '', 'keywords' => '', 'description' => ''];

    public function __construct()
    {
        add_action('wp_head', [$this, 'assignMetaInfo']);
    }

    static function assignMetaInfo(): void
    {
        if (Theme::isHomePage()) {
            self::metaHomePage();
        }

        $cat_id = get_query_var('cat');
        if ($cat_id) {
            self::metaCategory($cat_id);
        }

        $postId = get_query_var('p');
        if ($postId) {
            self::metaPost($postId);
        }

        $codeCgt = get_query_var(RouterHades::PARAM_EVENT);
        if ($codeCgt) {
            self::metaBottinEvent($codeCgt);
        }

        echo '<title>'.self::$metas['title'].'</title>';

        if (self::$metas['description'] != '') {
            echo '<meta name="description" content="'.self::$metas['description'].'" />';
        }

        if (self::$metas['keywords'] != '') {
            echo '<meta name="keywords" content="'.self::$metas['keywords'].'" />';
        }
    }

    private static function metaBottinEvent(string $codeCgt)
    {
        $hadesRepository = new HadesRepository();
        $event = $hadesRepository->getOffre($codeCgt);
        if ($event) {
            self::$metas['title'] = $event->titre.' | Agenda des manifestations ';
            self::$metas['description'] = join(
                ',',
                array_map(
                    function ($description) {
                        return $description->texte;
                    },
                    $event->descriptions
                )
            );
            $keywords = array_map(
                function ($category) {
                    return $category->lib;
                },
                $event->categories
            );
            $keywords = array_merge(
                $keywords,
                array_map(
                    function ($category) {
                        return $category->lib;
                    },
                    $event->selections
                )
            );
            self::$metas['keywords'] = join(",", $keywords);
        }
    }

    private static function metaHomePage()
    {
        self::$metas['title'] = self::baseTitle("Page d'accueil");
        self::$metas['description'] = get_bloginfo('description', 'display');
        self::$metas['keywords'] = 'Commune, Ville, Marche, Marche-en-Famenne, Famenne, Tourisme, Horeca';
    }

    private static function metaCategory(int $cat_id)
    {
        $category = get_category($cat_id);
        self::$metas['title'] = self::baseTitle("");
        self::$metas['description'] = $category->description;
        self::$metas['keywords'] = '';
    }

    private static function metaPost(int $postId)
    {
        $post = get_post($postId);
        self::$metas['title'] = self::baseTitle("");
        self::$metas['description'] = $post->post_excerpt;
        $tags = get_the_category($post->ID);
        self::$metas['keywords'] = join(
            ',',
            array_map(
                function ($tag) {
                    return $tag->name;
                },
                $tags
            )
        );
    }

    private static function metaCartographie()
    {
        //todo

    }

    public function isGoole()
    {
        global $is_lynx;
    }

    public static function baseTitle(string $begin)
    {
        $base = wp_title('|', false, 'right');

        $nameSousSite = get_bloginfo('name', 'display');
        if ($nameSousSite != 'Citoyen') {
            $base .= $nameSousSite.' | ';
        }
        $base .= ' Ville de Marche-en-Famenne';

        return $begin.' '.$base;
    }

}

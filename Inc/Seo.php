<?php


namespace VisitMarche\Theme\Inc;


use AcMarche\Pivot\Repository\HadesRepository;
use VisitMarche\Theme\Lib\LocaleHelper;

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
            self::renderMetas();

            return;
        }
        $cat_id = get_query_var('cat');
        if ($cat_id) {
            self::metaCategory($cat_id);
            self::renderMetas();

            return;
        }

        global $post;
        if ($post) {
            self::metaPost($post);
            self::renderMetas();

            return;
        }

        $codeCgt = get_query_var(RouterHades::PARAM_EVENT);
        if ($codeCgt) {
            self::metaHadesOffre($codeCgt);
            self::renderMetas();

            return;
        }

        $codeCgt = get_query_var(RouterHades::PARAM_OFFRE);
        if ($codeCgt) {
            self::metaHadesOffre($codeCgt);
        }

        self::renderMetas();
    }

    private static function renderMetas()
    {
        self::$metas['title'] = self::cleanString(self::$metas['title']);
        echo '<title>'.self::$metas['title'].'</title>';

        if (self::$metas['description'] != '') {
            self::$metas['description'] = self::cleanString(self::$metas['description']);
            echo '<meta name="description" content="'.self::$metas['description'].'" />';
        }

        if (self::$metas['keywords'] != '') {
            echo '<meta name="keywords" content="'.self::$metas['keywords'].'" />';
        }
    }

    private static function metaHadesOffre(string $codeCgt)
    {
        $language = LocaleHelper::getSelectedLanguage();
        $hadesRepository = new HadesRepository();
        $offre = $hadesRepository->getOffre($codeCgt);
        if ($offre) {
            $base = self::baseTitle("");
            self::$metas['title'] = $offre->getTitre($language).$base;
            self::$metas['description'] = join(
                ',',
                array_map(
                    function ($description) use ($language) {
                        return $description->getTexte($language);
                    },
                    $offre->descriptions
                )
            );
            $keywords = array_map(
                function ($category) use ($language) {
                    return $category->getLib($language);
                },
                $offre->categories
            );
            $keywords = array_merge(
                $keywords,
                array_map(
                    function ($selection) {
                        return $selection->lib;
                    },
                    $offre->selections
                )
            );
            self::$metas['keywords'] = join(",", $keywords);
        }
    }

    private static function metaHomePage()
    {
        $home = self::translate('homepage.title');
        self::$metas['title'] = self::baseTitle($home);
        self::$metas['description'] = get_bloginfo('description', 'display');
        self::$metas['keywords'] = 'Commune, Ville, Marche, Marche-en-Famenne, Famenne, Tourisme, Horeca';
    }

    private static function metaCategory(int $cat_id)
    {
        $category = get_category($cat_id);
        self::$metas['title'] = self::baseTitle("");
        self::$metas['description'] = self::cleanString($category->description);
        self::$metas['keywords'] = '';
    }

    private static function metaPost(\WP_Post $post)
    {
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

        $tourisme = self::translate('page.tourisme');

        return $begin.' '.$tourisme.' '.$base.' '.$nameSousSite;
    }

    private static function cleanString(string $description): string
    {
        $description = trim(strip_tags($description));
        $description = preg_replace("#\"#", "", $description);

        return $description;
    }

    private static function translate(string $text): string
    {
        $translator = LocaleHelper::iniTranslator();
        $language = LocaleHelper::getSelectedLanguage();

        return $translator->trans($text, [], null, $language);
    }

}

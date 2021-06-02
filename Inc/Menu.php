<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Cache;
use Symfony\Contracts\Cache\CacheInterface;
use VisitMarche\Theme\Lib\LocaleHelper;

class Menu
{
    const MENU_NAME = 'menu-top';

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct()
    {
        $this->cache = Cache::instance();
    }

    public function getItems(): array
    {
        $menu = wp_get_nav_menu_object(self::MENU_NAME);

        $args = array(
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'post_type' => 'nav_menu_item',
            'post_status' => 'publish',
            'output' => ARRAY_A,
            'output_key' => 'menu_order',
            'nopaging' => true,
            'update_post_term_cache' => false,
        );

        return wp_get_nav_menu_items($menu, $args);
    }

    public function getIcones(): array
    {
        $language = LocaleHelper::getSelectedLanguage();

        return $this->cache->get(
            'icones_home_'.$language,
            function () {
                $icones = [
                    'arts' => get_category_by_slug('arts'),
                    'balades' => get_category_by_slug('balades'),
                    'fetes' => get_category_by_slug('fetes'),
                    'gourmandises' => get_category_by_slug('gourmandises'),
                    'patrimoine' => get_category_by_slug('patrimoine'),
                ];
                $icones = array_map(
                    function ($icone) {
                        if ($icone) {
                            $icone->url = get_category_link($icone);
                        }

                        return $icone;
                    },
                    $icones
                );

                return $icones;
            }
        );
    }

    public function getMenuTop(): array
    {
        $language = LocaleHelper::getSelectedLanguage();

        return $this->cache->get(
            'menu_top_3'.$language,
            function () {
                $menu = [
                    'sorganiser' => get_category_by_slug('sorganiser'),
                    'sejourner' => get_category_by_slug('sejourner'),
                    'savourer' => get_category_by_slug('savourer'),
                    'bouger' => get_category_by_slug('bouger'),
                    'mice' => get_category_by_slug('mice'),
                    'inspirations' => get_category_by_slug('inspirations'),
                    'pratique' => get_category_by_slug('pratique'),
                    'agenda' => get_category_by_slug('agenda'),
                ];
                $menu = array_map(
                    function ($item) {
                        $item->url = get_category_link($item);

                        return $item;
                    },
                    $menu
                );

                $decouvrir = get_post(828);
                $decouvrir->name = $decouvrir->post_title;
                $decouvrir->url = get_permalink($decouvrir);
                $menu['decouvrir'] = $decouvrir;

                return $menu;
            }
        );
    }
}

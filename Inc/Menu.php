<?php

namespace VisitMarche\Theme\Inc;

use Symfony\Contracts\Cache\CacheInterface;
use VisitMarche\Theme\Lib\Cache;
use VisitMarche\Theme\Lib\LocaleHelper;

class Menu
{
    public const MENU_NAME = 'menu-top';

    private CacheInterface $cache;

    public function __construct()
    {
        $this->cache = Cache::instance();
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

                return array_map(
                    function ($icone) {
                        if ($icone) {
                            $icone->url = get_category_link($icone);
                        }

                        return $icone;
                    },
                    $icones
                );
            }
        );
    }

    public function getMenuTop(): array
    {
        $language = LocaleHelper::getSelectedLanguage();

        return $this->cache->get(
            'menu_top_7'.$language,
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
                $decouvrir->name = LocaleHelper::translate($decouvrir->post_title);
                $decouvrir->url = get_permalink($decouvrir);
                $menu['decouvrir'] = $decouvrir;

                return $menu;
            }
        );
    }
}

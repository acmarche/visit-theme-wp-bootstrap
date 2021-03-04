<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Cache;
use Symfony\Contracts\Cache\CacheInterface;

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

    public function getAllItems(): array
    {
        return $this->cache->get(
            Cache::MENU_CACHE_NAME.time(),
            function (): array {
                return $this->getItems();
            }
        );
    }

    public function getIcones(): array
    {
        return [
            'arts' => [
                'url' => get_category_link(get_category_by_slug('arts')),
                'name' => get_category_by_slug('arts'),
            ],
            'balades' => [
                'url' => get_category_link(get_category_by_slug('balades')),
                'name' => get_category_by_slug('balades'),
            ],
            'fetes' => [
                'url' => get_category_link(get_category_by_slug('fetes')),
                'name' => get_category_by_slug('fetes'),
            ],
            'gourmandises' => [
                'url' => get_category_link(get_category_by_slug('gourmandises')),
                'name' => get_category_by_slug('gourmandises'),
            ],
            'patrimoine' => [
                'url' => get_category_link(get_category_by_slug('patrimoine')),
                'name' => get_category_by_slug('patrimoine'),
            ],
        ];

    }

    public function getMenuTop(): array
    {
        $menu = [];
        $menu['sorganiser'] = [
            'url' => get_category_link(get_category_by_slug('sorganiser')),
            'name' => get_category_by_slug('sorganiser'),
            'sejourner' => [
                'url' => get_category_link(get_category_by_slug('sejourner')),
                'name' => get_category_by_slug('sejourner')->name,
            ],
            'savourer' => [
                'url' => get_category_link(get_category_by_slug('savourer')),
                'name' => get_category_by_slug('savourer')->name,
            ],
            'bouger' => [
                'url' => get_category_link(get_category_by_slug('bouger')),
                'name' => get_category_by_slug('bouger')->name,
            ],
        ];
        $menu['inspirations'] = [
            'url' => get_category_link(get_category_by_slug('inspirations')),
            'name' => get_category_by_slug('inspirations'),
        ];
        $menu['pratique'] = [
            'url' => get_category_link(get_category_by_slug('pratique')),
            'name' => get_category_by_slug('pratique'),
        ];
        $menu['agenda'] = [
            'url' => get_category_link(get_category_by_slug('agenda')),
            'name' => get_category_by_slug('agenda'),
        ];

        return $menu;
    }

    private function searchItem(string $key)
    {
        foreach ($this->getAllItems() as $item) {

        }
    }


}

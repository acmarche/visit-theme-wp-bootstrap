<?php

namespace VisitMarche\Theme\Inc;

class AssetsLoad
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', fn() => $this->visitmarcheAssets());

        if (Theme::isHomePage()) {
            add_action('wp_enqueue_scripts', fn() => $this->visitmarcheHome());
            //  add_action('wp_enqueue_scripts', [$this, 'loadSearchScreenHome']);
        }

        if (!is_category() && !is_search() && !is_front_page()) {
            add_action('wp_enqueue_scripts', fn() => $this->visitmarcheLeaft());
            add_action('wp_enqueue_scripts', fn() => $this->visitmarcheLightGallery());
            //  add_action('wp_enqueue_scripts', [$this, 'visitmarcheGpx']);
        }

        add_filter('script_loader_tag', fn($tag, $handle, $src) => $this->addAsModule($tag, $handle, $src), 10, 3);
    }

    public function visitmarcheAssets(): void
    {
        wp_enqueue_style(
            'visitmarche-bootstrap',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_style(
            'visitmarche-fontawesome',
            'https://use.fontawesome.com/releases/v5.15.2/css/all.css',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_style(
            'visitmarche-base-style',
            get_template_directory_uri().'/assets/tartine/css/base.css',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',
            ['jquery'],
            wp_get_theme()->get('Version'),
            true
        );

        wp_enqueue_style(
            'visitmarche-jf-style',
            get_template_directory_uri().'/assets/css/visit.css',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-close-js',
            get_template_directory_uri().'/assets/js/utils/navigation.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
    }

    public function visitmarcheHome(): void
    {
        wp_enqueue_style(
            'visitmarche-home-style',
            get_template_directory_uri().'/assets/tartine/css/home.css',
            []
        );

        /*     wp_enqueue_style(
                 'visitmarche-lightSlider-style',
                 get_template_directory_uri().'/assets/js/lightslider/css/lightslider.css',
                 array()
             );

             wp_enqueue_script(
                 'visitmarche-lightSlider-js',
                 get_template_directory_uri().'/assets/js/lightslider/js/lightslider.js',
                 array('jquery'),
                 wp_get_theme()->get('Version'),
                 true
             );*/
    }

    public function visitmarcheLightGallery(): void
    {
        wp_enqueue_style(
            'visitmarche-lightSlider-style',
            get_template_directory_uri().'/assets/js/lightGallery/dist/css/lightgallery.min.css',
            []
        );

        wp_enqueue_script(
            'visitmarche-lightGallery-js',
            get_template_directory_uri().'/assets/js/lightGallery/dist/js/lightgallery.min.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-zoom-js',
            get_template_directory_uri().'/assets/js/lightGallery/modules/lg-zoom.min.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-mouse-js',
            get_template_directory_uri().'/assets/js/lightGallery/lib/jquery.mousewheel.min.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-full-js',
            get_template_directory_uri().'/assets/js/lightGallery/modules/lg-fullscreen.min.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
    }

    public function visitmarcheLeaft(): void
    {
        wp_enqueue_style(
            'visitmarche-leaflet',
            'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',
            [],
            wp_get_theme()->get('Version')
        );
        wp_enqueue_script(
            'visitmarche-leaflet-js',
            'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',
            [],
            wp_get_theme()->get('Version')
        );
        wp_enqueue_script(
            'visitmarche-zoom-js',
            get_template_directory_uri().'/assets/js/utils/L.KML.js',
            [],
            wp_get_theme()->get('Version')
        );

        /* elevation */

        wp_enqueue_style(
            'visitmarche-leaflet-elevation-css',
            'https://unpkg.com/@raruto/leaflet-elevation@1.6.7/dist/leaflet-elevation.min.css',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-leaflet-ui-js',
            'https://unpkg.com/leaflet-ui@0.2.0/dist/leaflet-ui.js',
            [],
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-leaflet-elevation-js',
            'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.js',
            [],
            wp_get_theme()->get('Version')
        );
    }

    /**
     * Pour vue
     * @param $tag
     * @param $handle
     * @param $src
     * @return mixed|string
     */
    function addAsModule($tag, $handle, $src)
    {
        if ('vue-app' !== $handle) {
            return $tag;
        }

        return '<script type="module" src="'.esc_url($src).'"></script>';
    }

}

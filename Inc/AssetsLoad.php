<?php

namespace VisitMarche\Theme\Inc;

class AssetsLoad
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'visitmarcheAssets']);

        if (Theme::isHomePage()) {
            add_action('wp_enqueue_scripts', [$this, 'visitmarcheHome']);
            //  add_action('wp_enqueue_scripts', [$this, 'loadSearchScreenHome']);
        }

        if (!is_category() && !is_search() && !is_front_page()) {
            add_action('wp_enqueue_scripts', [$this, 'visitmarcheLeaft']);
            add_action('wp_enqueue_scripts', [$this, 'visitmarcheLightGallery']);
            //  add_action('wp_enqueue_scripts', [$this, 'visitmarcheGpx']);
        }

        //    add_action('wp_enqueue_scripts', [$this, 'loadSearchScreen']);
    }

    function visitmarcheAssets()
    {
        wp_enqueue_style(
            'visitmarche-bootstrap',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_style(
            'visitmarche-fontawesome',
            'https://use.fontawesome.com/releases/v5.15.2/css/all.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_style(
            'visitmarche-base-style',
            get_template_directory_uri().'/assets/tartine/css/base.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-bootstrap-js',
            'https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',
            array('jquery'),
            wp_get_theme()->get('Version'),
            true
        );

        wp_enqueue_style(
            'visitmarche-jf-style',
            get_template_directory_uri().'/assets/css/visit.css',
            array(),
            wp_get_theme()->get('Version')
        );

        /*    wp_enqueue_script(
                'visitmarche-close-js',
                get_template_directory_uri().'/assets/js/utils/navigation.js',
                array(),
                wp_get_theme()->get('Version'),
                true
            );*/
    }

    function visitmarcheHome()
    {
        wp_enqueue_style(
            'visitmarche-home-style',
            get_template_directory_uri().'/assets/tartine/css/home.css',
            array()
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

    function visitmarcheLightGallery()
    {
        wp_enqueue_style(
            'visitmarche-lightSlider-style',
            get_template_directory_uri().'/assets/js/lightGallery/dist/css/lightgallery.min.css',
            array()
        );

        wp_enqueue_script(
            'visitmarche-lightGallery-js',
            get_template_directory_uri().'/assets/js/lightGallery/dist/js/lightgallery.min.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-zoom-js',
            get_template_directory_uri().'/assets/js/lightGallery/modules/lg-zoom.min.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-mouse-js',
            get_template_directory_uri().'/assets/js/lightGallery/lib/jquery.mousewheel.min.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_script(
            'visitmarche-lightGallery-full-js',
            get_template_directory_uri().'/assets/js/lightGallery/modules/lg-fullscreen.min.js',
            array(),
            wp_get_theme()->get('Version'),
            true
        );
    }

    function visitmarcheLeaft()
    {
        wp_enqueue_style(
            'visitmarche-leaflet',
            'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',
            array(),
            wp_get_theme()->get('Version')
        );
        wp_enqueue_script(
            'visitmarche-leaflet-js',
            'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js',
            array(),
            wp_get_theme()->get('Version')
        );
        wp_enqueue_script(
            'visitmarche-zoom-js',
            get_template_directory_uri().'/assets/js/utils/L.KML.js',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-d3-js',
            'https://d3js.org/d3.v6.min.js',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-gpx-js',
            get_template_directory_uri().'/assets/elevation/gpx.js',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_enqueue_script(
            'visitmarche-gpx-ele-js',
            get_template_directory_uri().'/assets/elevation/leaflet.elevation-0.0.4-d3v4.min.js',
            array(),
            wp_get_theme()->get('Version')
        );

    }

    public function visitmarcheGpx()
    {
        wp_register_style(
            'visitmarche-gpx-css',
            'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.css',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_register_script(
            'visitmarche-leaflet1-js',
            'https://unpkg.com/leaflet@1.3.2/dist/leaflet.js',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_register_script(
            'visitmarche-leaflet-ui-js',
            'https://unpkg.com/leaflet-ui@0.2.0/dist/leaflet-ui.js',
            array(),
            wp_get_theme()->get('Version')
        );

        wp_register_script(
            'visitmarche-leaflet-elevation-js',
            'https://unpkg.com/@raruto/leaflet-elevation/dist/leaflet-elevation.js',
            array(),
            wp_get_theme()->get('Version')
        );
    }

}

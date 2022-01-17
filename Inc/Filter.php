<?php

namespace VisitMarche\Theme\Inc;

class Filter
{
    public function __construct()
    {
        //add_filter('get_the_archive_title', [Setup::get_instance(), 'removeCategoryPrefixTitle']);
        // Stop WP adding extra <p> </p> to your pages' content
        //  remove_filter('the_content', 'wpautop');
        //  remove_filter('the_excerpt', 'wpautop');
        add_filter('the_content', fn (string $content) => $this->filterContent($content));
        add_filter('upload_mimes', fn (array $mimes) => $this->gpxTypes($mimes));
    }

    /**
     * Remove word "Category".
     *
     * @param $title
     *
     * @return string|void
     */
    public function removeCategoryPrefixTitle($title)
    {
        if (is_category()) {
            $title = single_cat_title('', false);
        }

        return $title;
    }

    public function filterContent(string $content): ?string
    {
        $content = preg_replace('#<ul>#', '<ul class="list-group">', $content);
        $content = preg_replace('#<li>#', '<li class="list-group-item">', $content);

        return preg_replace('#<table#', '<table class="table table-bordered table-hover"', $content);
    }

    public function gpxTypes(array $mimes): array
    {
        $mimes['kml'] = 'application/vnd.google-earth.kml+xml';
        $mimes['gpx'] = 'text/xml';

        return $mimes;
    }
}

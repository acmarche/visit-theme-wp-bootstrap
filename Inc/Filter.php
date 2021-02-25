<?php


namespace VisitMarche\Theme\Inc;


class Filter
{
    public function __construct()
    {
        //add_filter('get_the_archive_title', [Setup::get_instance(), 'removeCategoryPrefixTitle']);
        // Stop WP adding extra <p> </p> to your pages' content
        remove_filter('the_content', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
    }

    /**
     * Remove word "Category"
     *
     * @param $title
     *
     * @return string|void
     */
    function removeCategoryPrefixTitle($title)
    {
        if (is_category()) {
            $title = single_cat_title('', false);
        }

        return $title;
    }
}

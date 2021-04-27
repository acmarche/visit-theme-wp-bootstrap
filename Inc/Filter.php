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
        add_filter('the_content', [$this, 'FilterContent']);
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

    function FilterContent(string $content)
    {
        $content = preg_replace("#<ul>#", '<ul class="list-group">', $content);
        $content = preg_replace("#<li>#", '<li class="list-group-item">', $content);
        $content = preg_replace("#<table#", '<table class="table table-bordered table-hover"', $content);

        return $content;
    }
}

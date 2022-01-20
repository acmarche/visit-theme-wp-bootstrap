<?php

namespace VisitMarche\Theme\Inc;

use WP_Query;

class QueryAlter
{
    public function __construct()
    {
        add_action('pre_get_posts', fn (\WP_Query $query) => $this->modifyWhereCategory($query));
    }

    /**
     * Oblige wp a afficher que les articles de la catégorie en cours
     * et pas ceux des catégories enfants.
     */
    public function modifyWhereCategory(WP_Query $query): void
    {
        if (! is_admin() && $query->is_category()) :

            $object = get_queried_object();

        if (null !== $object && $object->cat_ID && $query->is_main_query()) {
            $ID_cat = $object->cat_ID;
            //sinon prend enfant
            $query->set('category__in', $ID_cat);
        }
        endif;
    }
}

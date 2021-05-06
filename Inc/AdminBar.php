<?php

namespace VisitMarche\Theme\Inc;

class AdminBar
{
    public function __construct()
    {
        add_action('admin_bar_menu', [$this, 'customize_my_wp_admin_bar'], 100);
    }

    function customize_my_wp_admin_bar($wp_admin_bar)
    {
        $codeCgt = get_query_var(RouterHades::PARAM_OFFRE);
        if ($codeCgt) {
            $wp_admin_bar->add_menu(
                array(
                    'id' => 'edit',
                    'title' => 'Modifier l\'offre',
                    'href' => 'https://w3.ftlb.be/interface/',
                )
            );
        }
    }
}

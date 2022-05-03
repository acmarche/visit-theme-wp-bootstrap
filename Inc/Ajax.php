<?php

namespace VisitMarche\Theme\Inc;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_my_action', fn() => $this::actionDeleteFiltre());
    }

    function actionDeleteFiltre()
    {
        $categoryId = intval($_POST['categoryId']);
        $reference = intval($_POST['reference']);
        if ($categoryId && $reference) {
            $categoryFiltres = get_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
            if (is_array($categoryFiltres)) {
                $key = array_search($reference, $categoryFiltres);
                if ($key) {
                    unset($categoryFiltres[$key]);
                    update_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
                    echo json_encode($categoryFiltres);
                }
            }
        }
        wp_die();
    }

    function my_action_javascript()
    { ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                var data = {
                    'action': 'my_action',
                    'whatever': 1234
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function (response) {
                    console.log(response);
                    alert('Got this from the server: ' + response);
                });
            });
        </script> <?php
    }

    function capitaine_load_comments()
    {
        $post_id = $_POST['post_id'];

        $comments = get_comments(array(
            'post_id' => $post_id,
            'status' => 'approve',
        ));

        wp_list_comments(array(
            'per_page' => -1,
            'avatar_size' => 76,
        ), $comments);

        wp_die();
    }
}
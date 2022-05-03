<?php

namespace VisitMarche\Theme\Inc;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_action_delete_filtre', fn() => $this::actionDeleteFiltre());
        add_action('wp_ajax_action_add_filtre', fn() => $this::actionAddFiltre());
    }

    function actionDeleteFiltre()
    {
        $categoryId = (int)$_POST['categoryId'];
        $reference = (int)$_POST['reference'];
        $categoryFiltres = [];
        if ($categoryId && $reference) {
            $categoryFiltres = get_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
            if (is_array($categoryFiltres)) {
                $key = array_search($reference, $categoryFiltres);
                if ($key) {
                    unset($categoryFiltres[$key]);
                    update_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
                }
            }
        }
        echo json_encode($categoryFiltres);
        wp_die();
    }

    function actionAddFiltre()
    {
        $categoryFiltres = [];
        $categoryId = (int)$_POST['categoryId'];
        $parentId = (int)$_POST['parentId'];
        $childId = (int)$_POST['childId'];
        if ($categoryId && ($parentId || $childId)) {
            $categoryFiltres = get_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
            if (!is_array($categoryFiltres)) {
                $categoryFiltres = [];
            }
            if ($childId) {
                $categoryFiltres[] = $childId;
            } else {
                $categoryFiltres[] = $parentId;
            }
            update_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
        }
        echo json_encode($categoryFiltres);
        wp_die();
    }
}
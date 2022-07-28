<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;

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
        $id = $_POST['id'];
        $categoryFiltres = [];
        if ($categoryId && $id) {
            $filtreRepository = PivotContainer::getTypeOffreRepository();
            if ($filtre = $filtreRepository->find($id)) {
                $urn = $filtre->urn;
                $categoryFiltres = get_term_meta($categoryId, PivotMetaBox::PIVOT_REFRUBRIQUE, true);
                if (is_array($categoryFiltres)) {
                    $key = array_search($urn, $categoryFiltres);
                    if (is_int($key)) {
                        unset($categoryFiltres[$key]);
                        update_term_meta($categoryId, PivotMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
                    }
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
        $typeOffreId = (int)$_POST['typeOffreId'];
        $filtreRepository = PivotContainer::getTypeOffreRepository();
        if ($categoryId > 0 && $typeOffreId > 0) {
            $categoryFiltres = get_term_meta($categoryId, PivotMetaBox::PIVOT_REFRUBRIQUE, true);
            if (!is_array($categoryFiltres)) {
                $categoryFiltres = [];
            }
            $filtre = $filtreRepository->find($typeOffreId);
            if ($filtre) {
                $categoryFiltres[] = $filtre->urn;
            }
            update_term_meta($categoryId, PivotMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
        }
        echo json_encode($categoryFiltres);
        wp_die();
    }
}
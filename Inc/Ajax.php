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
                $reference = $filtre->getIdentifiant();
                $categoryFiltres = get_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
                if (is_array($categoryFiltres)) {
                    $key = array_search($reference, $categoryFiltres);
                    if ($key) {
                        unset($categoryFiltres[$key]);
                        update_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
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
        $filtreRepository = PivotContainer::getTypeOffreRepository();
        if ($categoryId > 0) {
            $categoryFiltres = get_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
            if (!is_array($categoryFiltres)) {
                $categoryFiltres = [];
            }
            $filtre = $filtreRepository->find($categoryId);
            if($filtre){
                $categoryFiltres[] = $filtre->getIdentifiant();
            }
            update_term_meta($categoryId, FiltreMetaBox::PIVOT_REFRUBRIQUE, $categoryFiltres);
        }
        echo json_encode($categoryFiltres);
        wp_die();
    }
}
<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Mailer;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use AcMarche\Pivot\Utils\CategoryUtils;
use VisitMarche\Theme\Lib\HadesUtils;
use VisitMarche\Theme\Lib\LocaleHelper;
use WP_Error;
use WP_REST_Request;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api
 * @package VisitMarche\Theme\Inc
 */
class ApiData
{
    public static function hadesFiltres(WP_REST_Request $request)
    {
        $categoryId = $request->get_param('keyword');
        if (!$categoryId) {
            Mailer::sendError("error cat id filtres", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }
        $categoryUtils = new CategoryUtils();
        $filtres = $categoryUtils->getFiltresCategory($categoryId);
        $translator = LocaleHelper::iniTranslator();
        $filtres[0] = $translator->trans('filter.all');

        return rest_ensure_response($filtres);
    }

    public static function hadesOffres(WP_REST_Request $request)
    {
        $filtreString = $request->get_param('filtre');
        $categoryId = (int)$request->get_param('category');
        if (!$categoryId) {
            Mailer::sendError("error hades offre", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }
        if (!$filtreString) {
            $categoryUtils = new CategoryUtils();
            $filtres = $categoryUtils->getFiltresCategory($categoryId);
            $filtres = array_keys($filtres);
        }
        else {
            $filtres =[$filtreString];
        }

        $language = LocaleHelper::getSelectedLanguage();
        $hadesRepository = new HadesRepository();
        $offres = $hadesRepository->getOffres($filtres);
        array_map(
            function ($offre) use ($categoryId, $language) {
                $offre->url = RouterHades::getUrlOffre($offre, $categoryId);
                $offre->titre = $offre->getTitre($language);
                $description = null;
                if (count($offre->descriptions) > 0) {
                    $description = $offre->descriptions[0]->getTexte($language);
                }
                $offre->description = $description;
                array_map(
                    function ($category) use ($language) {
                        $category->titre = $category->getLib($language);
                    },
                    $offre->categories
                );
                $offre->image = $offre->firstImage();
            },
            $offres
        );

        return rest_ensure_response($offres);
    }
}

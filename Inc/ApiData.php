<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Mailer;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
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
        $hadesRefrubrique = $request->get_param('keyword');
        if (!$hadesRefrubrique) {
            Mailer::sendError("error carto", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }

        $all = Hades::allCategories();
        $filtres = isset($all[$hadesRefrubrique]) ? $all[$hadesRefrubrique] : [];

        $filtres[0] = 'Tout';

        return rest_ensure_response($filtres);
    }

    public static function hadesOffres(WP_REST_Request $request)
    {
        $keyword = $request->get_param('keyword');
        $category = (int)$request->get_param('category');
        if (!$keyword or !$category) {
            Mailer::sendError("error hades offre", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }

        $all = Hades::allCategories();
        $filtres = isset($all[$keyword]) ? array_keys($all[$keyword]) : [$keyword];

        $hadesRepository = new HadesRepository();
        $offres = $hadesRepository->getOffres($filtres);
        $language = LocaleHelper::getSelectedLanguage();
        array_map(
            function ($offre) use ($category, $language) {
                $offre->url = RouterHades::getUrlOffre($offre, $category);
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

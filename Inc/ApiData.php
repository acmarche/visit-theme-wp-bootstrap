<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Mailer;
use AcMarche\Pivot\Hades;
use AcMarche\Pivot\Repository\HadesRepository;
use Elastica\Exception\InvalidException;
use WP_Error;
use WP_HTTP_Response;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api
 * @package VisitMarche\Theme\Inc
 */
class ApiData
{
    public static function ca_events()
    {
        $hadesRepository = new HadesRepository();
        $events = $hadesRepository->getEvents();

        return rest_ensure_response($events);
    }

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
        array_map(
            function ($offre) use ($category) {
                $offre->url = RouterHades::getUrlOffre($offre, $category);
            },
            $offres
        );

        return rest_ensure_response($offres);
    }
}

<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Mailer;
use AcMarche\Pivot\Filtre\HadesFiltres;
use AcMarche\Pivot\Repository\HadesRepository;
use AcSort;
use VisitMarche\Theme\Lib\Elasticsearch\Data\ElasticData;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\WpRepository;
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
        $categoryId = $request->get_param('categoryId');
        if (!$categoryId) {
            Mailer::sendError("error cat id filtres", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }
        $categoryUtils = new HadesFiltres();
        $filtres = $categoryUtils->getCategoryFilters($categoryId);

        /**
         * Ajout de "Tout"
         */
        $translator = LocaleHelper::iniTranslator();
        $filtres[0] = $translator->trans('filter.all');

        return rest_ensure_response($filtres);
    }

    public static function hadesOffres(WP_REST_Request $request)
    {
        $filtreSelected = $request->get_param('filtre');//element selected
        $currentCategoryId = (int)$request->get_param('category');//current category
        if (!$currentCategoryId) {
            Mailer::sendError("error hades offre", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }

        $categoryUtils = new HadesFiltres();
        $wpRepository = new WpRepository();
        $language = LocaleHelper::getSelectedLanguage();
        $postUtils = new PostUtils();

        /**
         * Si pas de filtre selectionne, on affiche tout
         */
        if (!$filtreSelected) {
            $filtres = $categoryUtils->getCategoryFilters($currentCategoryId);
            $offres = [];
            if (count($filtres) > 0) {
                $offres = self::getOffres($filtres, $currentCategoryId, $language);
            }
            $posts = $wpRepository->getPostsByCatId($currentCategoryId);
            //fusion offres et articles
            $posts = $postUtils->convert($posts);
            $offres = array_merge($posts, $offres);

            return rest_ensure_response($offres);
        }

        /**
         * si filtre selectionne est int donc c'est une cat wp
         * je vais chercher les filtres hades sur celui ci
         */
        $filtreSelectedToInt = (int)$filtreSelected;

        if ($filtreSelectedToInt) {
            $filtres = $categoryUtils->getCategoryFilters($filtreSelectedToInt);
            $offres = [];
            if (count($filtres) > 0) {
                $offres = self::getOffres($filtres, $currentCategoryId, $language);
            }
            $posts = $wpRepository->getPostsByCatId($filtreSelectedToInt);
            $posts = $postUtils->convert($posts);
            $offres = array_merge($posts, $offres);

            return rest_ensure_response($offres);
        }

        $offres = self::getOffres([$filtreSelected => $filtreSelected], $currentCategoryId, $language);

        return rest_ensure_response($offres);
    }

    private static function getOffres(array $filtres, int $currentCategoryId, string $language)
    {
        $hadesRepository = new HadesRepository();
        $postUtils = new PostUtils();
        $filtres = array_keys($filtres);
        $offres = $hadesRepository->getOffres($filtres);
        $offres = $postUtils->convertOffres($offres, $currentCategoryId, $language);

        return $offres;
    }

    public static function getAll()
    {
        $data = [];
        $elasticData = new ElasticData();
        $data['posts'] = $elasticData->getPosts();
        $data['categories'] = $elasticData->getCategories();
        $data['offres'] = $elasticData->getOffres();

        return rest_ensure_response($data);

    }
}

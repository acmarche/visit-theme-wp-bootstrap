<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Lib\Elasticsearch\Data\ElasticData;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\Mailer;
use VisitMarche\Theme\Lib\PostUtils;
use VisitMarche\Theme\Lib\WpRepository;
use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api.
 */
class ApiData
{
    public static function pivotAllFiltres(WP_REST_Request $request)
    {
        $parent = (int)$request->get_param('parent');
        $pivotRepository = PivotContainer::getFiltreRepository();
        $filtres = $pivotRepository->findByParent($parent);

        return rest_ensure_response($filtres);
    }

    public static function pivotFiltres(WP_REST_Request $request)
    {
        $categoryId = $request->get_param('categoryId');
        if (!$categoryId) {
            Mailer::sendError('error cat id filtres', 'missing param keyword');

            return new WP_Error(500, 'missing param keyword');
        }
        $categoryUtils = new WpRepository();
        $language = LocaleHelper::getSelectedLanguage();
        //$filtres = $categoryUtils->getCategoryFilters($categoryId, $language);

        $pivotRepository = PivotContainer::getFiltreRepository();
        $types = $pivotRepository->findWithChildren();

        /**
         * Ajout de "Tout".
         */
        $translator = LocaleHelper::iniTranslator();
        $filtres[0] = $translator->trans('filter.all');

        return rest_ensure_response($filtres);
    }

    public static function hadesFiltres(WP_REST_Request $request)
    {
        $categoryId = $request->get_param('categoryId');
        if (!$categoryId) {
            Mailer::sendError('error cat id filtres', 'missing param keyword');

            return new WP_Error(500, 'missing param keyword');
        }
        $categoryUtils = new WpRepository();
        $language = LocaleHelper::getSelectedLanguage();
        $filtres = $categoryUtils->getCategoryFilters($categoryId, $language);

        /**
         * Ajout de "Tout".
         */
        $translator = LocaleHelper::iniTranslator();
        $filtres[0] = $translator->trans('filter.all');

        return rest_ensure_response($filtres);
    }

    public static function hadesOffres(WP_REST_Request $request)
    {
        $data = [];
        $filtreSelected = $request->get_param('filtre'); //element selected
        $currentCategoryId = (int)$request->get_param('category'); //current category
        if (0 === $currentCategoryId) {
            Mailer::sendError('error hades offre', 'missing param keyword');

            return new WP_Error(500, 'missing param keyword');
        }

        $wpRepository = new WpRepository();
        $language = LocaleHelper::getSelectedLanguage();
        $postUtils = new PostUtils();

        /*
         * Si pas de filtre selectionne, on affiche tout
         */
        if (!$filtreSelected) {
            $filtres = $wpRepository->getCategoryFilters($currentCategoryId, $language);
            $offres = [];
            if ([] !== $filtres) {
                $offres = self::getOffres($filtres, $currentCategoryId, $language);
            }
            $posts = $wpRepository->getPostsByCatId($currentCategoryId);
            //fusion offres et articles
            $posts = $postUtils->convertPostsToArray($posts);
            $offres = array_merge($posts, $offres);

            return rest_ensure_response($offres);
        }

        /**
         * si filtre selectionne est int donc c'est une cat wp
         * je vais chercher les filtres hades sur celui ci.
         */
        $filtreSelectedToInt = (int)$filtreSelected;

        if (0 !== $filtreSelectedToInt) {
            $filtres = $wpRepository->getCategoryFilters($filtreSelectedToInt, $language);
            $offres = [];
            if ([] !== $filtres) {
                $offres = self::getOffres($filtres, $currentCategoryId, $language);
            }
            $posts = $wpRepository->getPostsByCatId($filtreSelectedToInt);
            $posts = $postUtils->convertPostsToArray($posts);
            $offres = array_merge($posts, $offres);

            return rest_ensure_response($offres);
        }

        $offres = self::getOffres([
            $filtreSelected => $filtreSelected,
        ], $currentCategoryId, $language);

        return rest_ensure_response($offres);
    }

    /**
     * Pour alimenter le moteur de recherche depuis l'exterieur.
     */
    public static function getAll(): \WP_Error|WP_HTTP_Response|WP_REST_Response
    {
        $data = [];
        $elasticData = new ElasticData();
        $data['posts'] = $elasticData->getPosts();
        $data['categories'] = $elasticData->getCategories();
        $data['offres'] = $elasticData->getOffres();

        return rest_ensure_response($data);
    }

    private static function getOffres(array $filtres, int $currentCategoryId, string $language): array
    {
        $hadesRepository = PivotContainer::getRepository();
        $postUtils = new PostUtils();
        $filtres = array_keys($filtres);
        $offres = $hadesRepository->getOffres($filtres);

        return $postUtils->convertOffres($offres, $currentCategoryId, $language);
    }
}

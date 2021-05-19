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

        if (!$filtreSelected) {
            $categoryUtils = new HadesFiltres();
            $filtres = $categoryUtils->getCategoryFilters($currentCategoryId);
            $filtres = array_keys($filtres);
        } else {
            $filtres = [$filtreSelected];
        }

        $hadesRepository = new HadesRepository();
        $offres = $hadesRepository->getOffres($filtres);

        $language = LocaleHelper::getSelectedLanguage();
        $postUtils = new PostUtils();
        $offres = $postUtils->convertOffres($offres, $currentCategoryId, $language);

        $wpRepository = new WpRepository();
        $posts = $wpRepository->getPostsByCatId($currentCategoryId);
        $category_order = get_term_meta($currentCategoryId, 'acmarche_category_sort', true);
        if ($category_order == 'manual') {
            $posts = AcSort::getSortedItems($currentCategoryId, $posts);
        }

        //fusion offres et articles
        $postUtils = new PostUtils();
        $posts = $postUtils->convert($posts);
        $offres = $postUtils->convertOffres($offres, $currentCategoryId, $language);
        $offres = array_merge($posts, $offres);

        return rest_ensure_response($offres);
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

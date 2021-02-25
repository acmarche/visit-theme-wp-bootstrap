<?php


namespace VisitMarche\Theme\Inc;

use VisitMarche\Bottin\Bottin;
use VisitMarche\Bottin\Repository\BottinRepository;
use VisitMarche\Common\Mailer;
use VisitMarche\Common\SortUtil;
use VisitMarche\Elasticsearch\Searcher;
use VisitMarche\Pivot\Repository\HadesRepository;
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
    /**
     * @param array $parameter
     *
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public static function findPosts(WP_REST_Request $request)
    {
        $catParent = $request->get_param('catParent');
        if ( ! $catParent) {
            return new WP_Error(500, 'missing param catParent');
        }

        $ids = self::getIdsWpAndBottinCategories($catParent);

        // retrieve all fiches and add the wp category ids to them
        $bottinRepository = new BottinRepository();
        $fiches           = $bottinRepository->getFichesByCategories($ids['bottin']);

        $data = [];
        // Formats all fiches to fit front end requirements
        foreach ($fiches as $fiche) {
            $data[$fiche->id]['ID']         = $fiche->id;
            $data[$fiche->id]['excerpt']    = Bottin::getExcerpt($fiche);
            $data[$fiche->id]['post_title'] = $fiche->societe;
            $data[$fiche->id]['link']       = Router::getUrlFicheBottin($fiche);
        }

        //retrieves all posts and add the wp category ids to them
        $query = new WP_Query(['category__in' => $ids['wp']]);
        $posts = $query->get_posts();
        foreach ($posts as $post) {
            $post->link = get_permalink($post->ID);
        }
        //combines formatted fiches (data) and posts
        $all = array_merge($data, $posts);
        $all = SortUtil::sortPosts($all);

        // returns all posts and fiches with their respective wp category
        return rest_ensure_response($all);
    }

    /**
     * Based on MainCategoyId returns an associative and multidimensional array
     * ids['wp'] = MainCategoryId and ChildrenCagegoryIds used to retrieve posts in ca_all()
     * ids['bottin'] = BottinCategoryIds used to retrieve fiches in ca_all()
     * ids['association_wp_bottin'] = the wp category is pushed in this array when a bottin id is added for filtering purposes
     *
     * @param $catParent
     *
     * @return array[]
     */
    public static function getIdsWpAndBottinCategories($catParent)
    {
        $idsWp = $idsBottin = [];

        $args         = ['parent' => $catParent, 'hide_empty' => false];
        $children_cat = get_categories($args);

        $idsWp[] = $catParent; //adds the main category to the list of ids
        foreach ($children_cat as $cat) {
            $idsWp[]          = $cat->cat_ID; //adds the children from main category to the list of ids
            $categoryBottinId = get_term_meta(
                $cat->cat_ID,
                \BottinCategoryMetaBox::KEY_NAME,
                true
            ); //checks if meta bottinID metadata contains and ID
            if ($categoryBottinId) {
                $idsBottin[] = $categoryBottinId;
            }
        }
        // We also need to check if the main_cat has a bottin ID
        $categoryBottinId = get_term_meta(
            $catParent,
            \BottinCategoryMetaBox::KEY_NAME,
            true
        ); //ici on recupere l'id du BOTTIN c'est encodé en méta donné de la categorie
        if ($categoryBottinId) {
            $idsBottin[] = $categoryBottinId;
        }

        return ['wp' => $idsWp, 'bottin' => $idsBottin]; //wp/bottin/association_wp_bottin ids
    }

    public static function ca_events()
    {
        $hadesRepository = new HadesRepository();
        $events          = $hadesRepository->getEvents();

        return rest_ensure_response($events);
    }

    /**
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public static function search(WP_REST_Request $request)
    {
        $keyword = $request->get_param('keyword');
        if ( ! $keyword) {
            return new WP_Error(500, 'missing param keyword');
        }

        $searcher = new Searcher();

        try {
            $results = $searcher->search($keyword);
            $data    = ['count' => $results->count()];
        } catch (InvalidException $e) {
            Mailer::sendError("wp error search".$keyword, $e->getMessage());

            return rest_ensure_response([]);
        }

        /**
         * Je nettoie le resultat car je n'arrive pas avec react
         */
        $resultat = [];
        foreach ($results->getResults() as $result) {
            $hit        = $result->getHit();
            $resultat[] = $hit['_source'];
        }
        $data['hits'] = $resultat;

        return rest_ensure_response($data);
    }

    public static function suggest(WP_REST_Request $request)
    {
        $keyword = $request->get_param('keyword');
        if ( ! $keyword) {
            return new WP_Error(500, 'missing param keyword');
        }

        $searcher = new Searcher();

        try {
            $results = $searcher->suggest($keyword);
        } catch (InvalidException $e) {
            Mailer::sendError("wp error suggest: ".$keyword, $e->getMessage());

            return rest_ensure_response([]);
        }

        /**
         * Je nettoie le resultat car je n'arrive pas avec react
         */
        $data = [];
        foreach ($results->getSuggests() as $suggest) {
            foreach ($suggest as $suggest2) {
                foreach ($suggest2['options'] as $option) {
                    $data[] = $option['text'];
                }
            }
        }

        return rest_ensure_response($data);

    }

    static function ca_bottinAllCategories()
    {
        $bottinRepository = new BottinRepository();
        $allCategories    = $bottinRepository->getAllCategories();

        return rest_ensure_response($allCategories);
    }

    // This plugin returns the societe and the id of all companies in the bottin to retrieve them in the gutenberg block
    static function ca_bottinSocieteId()
    {
        $bottinRepository = new BottinRepository();
        $allfiches        = $bottinRepository->getFiches();
        $fichesSocieteId  = [];

        foreach ($allfiches as $fiche) {
            $fichesSociete             = $fiche->societe;
            $fichesId                  = $fiche->id;
            $formattedFiche            = [];
            $formattedFiche['societe'] = $fichesSociete;
            $formattedFiche['id']      = $fichesId;
            $formattedFiche['slug']    = $fiche->slug;

            $fichesSocieteId[] = $formattedFiche;
        }

        return rest_ensure_response($fichesSocieteId);
    }

    // This plugin also adds a custom endpoint that returns all sheets of the bottin based on their id
    static function ca_bottin($parameter)
    {
        $bottinRepository = new BottinRepository();
        $fiches           = $bottinRepository->getFicheById($parameter['ficheId']);

        return rest_ensure_response($fiches);
    }

    public static function mapKml(WP_REST_Request $request)
    {
        $keyword = $request->get_param('keyword');
        if ( ! $keyword) {
            return new WP_Error(500, 'missing param keyword');
        }

        $carto = new Carto();
        $data  = $carto->loadKml($keyword);

        return rest_ensure_response(['kmlText' => $data]);
    }

    public static function filtres()
    {
        $carto = new Carto();

        return rest_ensure_response($carto->filtres());
    }

    static function mapData(WP_REST_Request $request)
    {
        $keyword = $request->get_param('keyword');
        if ( ! $keyword) {
            Mailer::sendError("error carto", "missing param keyword");

            return new WP_Error(500, 'missing param keyword');
        }

        $carto   = new Carto();
        $element = $carto->foundSource($keyword);
        if (count($element) === 0) {
            $data = ['error' => true];
            Mailer::sendError("error carto", "missing source for keyword ".$keyword);

            return rest_ensure_response($data);
        }

        $source = $element['source'];

        if ($source == 'bottin') {

            $fiches = $carto->getFichesBottin($element['id']);
            $data   = ['data' => $fiches, 'kml' => false];

            return rest_ensure_response($data);
        }

        if ($source == 'kml') {
            $data = ['data' => $element['id'], 'kml' => true];

            return rest_ensure_response($data);
        }

        $data = ['error' => true];

        return rest_ensure_response($data);
    }
}

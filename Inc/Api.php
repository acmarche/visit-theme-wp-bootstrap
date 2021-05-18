<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\Filtre\HadesFiltres;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api
 * @package VisitMarche\Theme\Inc
 */
class Api
{
    public function __construct()
    {
        if (!is_admin()) {
            $this->registerHades();
        }
        $this->registerFields();
    }

    public function registerFields()
    {
        $categoryUtils = new HadesFiltres();

        register_rest_field(
            'category',
            'metadata',
            array(
                'get_callback' => function ($data) use ($categoryUtils) {
                    return $categoryUtils->getCategoryFilters($data['id']);
                },
            )
        );
    }

    public function registerHades()
    {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'hades',
                    'filtres/(?P<categoryId>.*+)',
                    [
                        'methods' => 'GET',
                        'callback' => function ($args) {
                            return ApiData::hadesFiltres($args);
                        },
                    ]
                );
            }
        );

        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'hades',
                    'offres/(?P<category>[\d]+)(/?)(?P<filtre>[\w-]*)',
                    [
                        'methods' => 'GET',
                        'callback' => function ($args) {
                            return ApiData::hadesOffres($args);
                        },
                    ]
                );
            }
        );

        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'visit',
                    'all',
                    [
                        'methods' => 'GET',
                        'callback' => function () {
                            return ApiData::getAll();
                        },
                    ]
                );
            }
        );
    }

    /**
     * Todo pour list/users !!
     */
    function secureApi()
    {
        add_filter(
            'rest_authentication_errors',
            function ($result) {
                // If a previous authentication check was applied,
                // pass that result along without modification.
                if (true === $result || is_wp_error($result)) {
                    return $result;
                }

                // No authentication has been performed yet.
                // Return an error if user is not logged in.
                if (!is_user_logged_in()) {
                    return new \WP_Error(
                        'rest_not_logged_in',
                        __('You are not currently logged in.'),
                        array('status' => 401)
                    );
                }

                // Our custom authentication check should have no effect
                // on logged-in requests
                return $result;
            }
        );
    }
}

<?php


namespace VisitMarche\Theme\Inc;

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
            //  $this->registerCateogry();
            $this->registerEvent();

        }
    }

    public function registerEvent()
    {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'hades',
                    'filtres/(?P<keyword>.*+)',
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
                    'offres/(?P<category>[\d]+)/(?P<keyword>[\w]+)',
                    [
                        'methods' => 'GET',
                        'callback' => function ($args) {
                            return ApiData::hadesOffres($args);
                        },
                    ]
                );
            }
        );
    }

    public function registerCateogry()
    {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'jfs/v1',
                    'all/(?P<catParent>.*+)',
                    [
                        'methods' => 'GET',
                        'callback' => function ($args) {
                            return ApiData::findPosts($args);
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

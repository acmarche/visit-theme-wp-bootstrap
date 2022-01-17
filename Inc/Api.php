<?php

namespace VisitMarche\Theme\Inc;

use WP_Error;

/**
 * Enregistrement des routes pour les api pour les composants react
 * Class Api.
 */
class Api
{
    public function __construct()
    {
        if (! is_admin()) {
            $this->registerHades();
        }
    }

    public function registerHades(): void
    {
        add_action(
            'rest_api_init',
            function () {
                register_rest_route(
                    'hades',
                    'filtres/(?P<categoryId>.*+)',
                    [
                        'methods' => 'GET',
                        'callback' => fn ($args) => ApiData::hadesFiltres($args),
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
                        'callback' => fn ($args) => ApiData::hadesOffres($args),
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
                        'callback' => fn () => ApiData::getAll(),
                    ]
                );
            }
        );
    }

    /**
     * Todo pour list/users !!
     */
    public function secureApi(): void
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
                if (! is_user_logged_in()) {
                    return new WP_Error(
                        'rest_not_logged_in',
                        __('You are not currently logged in.'),
                        [
                            'status' => 401,
                        ]
                    );
                }

                // Our custom authentication check should have no effect
                // on logged-in requests
                return $result;
            }
        );
    }
}

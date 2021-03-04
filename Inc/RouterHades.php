<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Common\Router;
use AcMarche\Pivot\Entities\Categorie;
use AcMarche\Pivot\Entities\OffreInterface;

/**
 * Ajouts des routes pour les articles virtuels du bottin et de l'agenda
 * https://roots.io/routing-wp-requests/
 * https://developer.wordpress.org/reference/functions/add_rewrite_rule/#user-contributed-notes
 * Class Router
 * @package AcMarche\Theme\Inc
 */
class RouterHades extends Router
{
    const PARAM_EVENT = 'codecgt';
    const EVENT_URL = 'manifestation/';
    const PARAM_OFFRE = 'codeoffre';
    const OFFRE_URL = 'offre/';

    public function __construct()
    {
        $this->addRouteEvent();
        $this->addRouteOffre();
       //    $this->flushRoutes();
    }

    public static function getUrlEventCategory(Categorie $categorie): string
    {
        return self::getBaseUrlSite().self::EVENT_URL.$categorie->id;
    }

    public static function getUrlOffre(OffreInterface $offre, string $prefix): string
    {
        return self::getBaseUrlSite().$prefix.$offre->id;
    }

    public function addRouteEvent()
    {
        add_action(
            'init',
            function () {
                add_rewrite_rule(
                    self::EVENT_URL.'([a-zA-Z0-9-]+)[/]?$',
                    'index.php?'.self::PARAM_EVENT.'=$matches[1]',
                    'top'
                );
            }
        );
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_EVENT;

                return $query_vars;
            }
        );
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || !$wp_query->is_main_query()) {
                    return $template;
                }

                if (get_query_var(self::PARAM_EVENT) == false ||
                    get_query_var(self::PARAM_EVENT) == '') {
                    return $template;
                }

                return get_template_directory().'/single-event.php';
            }
        );
    }

    public function addRouteOffre()
    {
        add_action(
            'init',
            function () {
                add_rewrite_rule(
                    self::OFFRE_URL.'([a-zA-Z0-9-]+)[/]?$',
                    'index.php?'.self::PARAM_OFFRE.'=$matches[1]',
                    'top'
                );
            }
        );
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_OFFRE;

                return $query_vars;
            }
        );
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || !$wp_query->is_main_query()) {
                    return $template;
                }

                if (get_query_var(self::PARAM_OFFRE) == false ||
                    get_query_var(self::PARAM_OFFRE) == '') {
                    return $template;
                }

                return get_template_directory().'/single-offre.php';
            }
        );
    }
}
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
    const OFFRE_URL = 'offre';

    public function __construct()
    {
        $this->addRouteEvent();
        $this->addRouteOffre();
        add_action('init', [$this, 'custom_rewrite_tag'], 10, 0);
        //    $this->flushRoutes();
    }

    public static function getUrlEventCategory(Categorie $categorie): string
    {
        return self::getBaseUrlSite().self::EVENT_URL.$categorie->id;
    }

    public static function getUrlOffre(OffreInterface $offre, string $prefix): string
    {
        $url = get_category_link(get_category_by_slug('sejourner'));
        $prefix = 'codeoffre';

        return '/?cat=7&'.$prefix.'='.$offre->id;

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

    public function custom_rewrite_tag()
    {
        add_rewrite_tag('%food%', '([^&]+)');
        add_rewrite_tag('%variety%', '([^&]+)');
        add_rewrite_tag('%offre%', '([^&]+)');
    }

    public function addRouteOffre()
    {

        global $wp_rewrite;
        $extra = $wp_rewrite->extra_permastructs;
        $categoryBase = preg_replace("#%category%#","",$extra['category']['struct']);///category/%category%

        $url = get_category_link(get_category_by_slug('sejourner'));
        //dump($url);
        //Setup a rule
        add_action(
            'init',
            function () {
                //add_rewrite_rule('^nutrition/([^/]*)/([^/]*)/?','index.php?page_id=12&food=$matches[1]&variety=$matches[2]','top');

                /*   add_rewrite_rule(
                       '^category/sorganiser/sejourner/([^/]*)/?',
                       'index.php?category_name=sorganiser/sejourner&codeoffre=$matches[1]',
                       'top'
                   );*/
                //'^category/([^/]*)/'.self::OFFRE_URL.'([a-zA-Z0-9-]+)[/]?$',
                //^= depart, $ fin string, + one or more, * zero or more, ? zero or one, () capture
                //https://regex101.com/r/guhLuX/1
                add_rewrite_rule(
                    '^category/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?',
                    'index.php?category_name=$matches[1]/$matches[1]&'.self::PARAM_OFFRE.'=$matches[4]',
                    'top'
                );
            }
        );
        //^category/([^/]*)/([^/]*)/([^/]*)/([^/]*)/?
        //Whitelist the query param
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_OFFRE;

                return $query_vars;
            }
        );
        //Add a handler to send it off to a template file
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || !$wp_query->is_main_query()) {
                    return $template;
                }
                dump($wp_query->query);
                dump(get_query_var(self::PARAM_OFFRE));
                if (get_query_var(self::PARAM_OFFRE) == false ||
                    get_query_var(self::PARAM_OFFRE) == '') {
                    return $template;
                }

                return get_template_directory().'/single-offre.php';
            }
        );
    }
}

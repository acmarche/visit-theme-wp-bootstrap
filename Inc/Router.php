<?php


namespace VisitMarche\Theme\Inc;


use VisitMarche\Pivot\Event\Entity\Categorie;
use VisitMarche\Pivot\Event\Entity\Event;

/**
 * Ajouts des routes pour les articles virtuels du bottin et de l'agenda
 * https://roots.io/routing-wp-requests/
 * https://developer.wordpress.org/reference/functions/add_rewrite_rule/#user-contributed-notes
 * Class Router
 * @package VisitMarche\Theme\Inc
 */
class Router
{
    const PARAM_EVENT = 'codecgt';
    const PARAM_BOTTIN_FICHE = 'slugfiche';
    const PARAM_BOTTIN_CATEGORY = 'slugcategory';
    const BOTTIN_FICHE_URL = 'bottin/fiche/';
    const BOTTIN_CATEGORY_URL = 'bwp/categorie/';
    const EVENT_URL = 'manifestation/';

    public function __construct()
    {
        //   $this->flushRoutes();
        $this->addRouteEvent();
        $this->addRouteBottin();
        $this->addRouteBottinCategory();
    }

    /**
     * Retourne la base du blog (/economie/, /sante/, /culture/...
     *
     * @param int|null $blodId
     *
     * @return string
     */
    public static function getBaseUrlSite(?int $blodId = null): string
    {
        if ( ! $blodId) {
            $blodId = get_current_blog_id();
        }

        return get_blog_details($blodId)->path;
    }

    public static function getUrlCategoryBottin(\stdClass $category): string
    {
        return self::getBaseUrlSite().Router::BOTTIN_CATEGORY_URL.$category->slug;
    }

    public static function getUrlFicheBottin(\stdClass $fiche): string
    {
        return self::getBaseUrlSite().Router::BOTTIN_FICHE_URL.$fiche->slug;
    }

    public static function getUrlEvent(Event $event): string
    {
        return self::getBaseUrlSite().Router::EVENT_URL.$event->id;
    }

    public static function getUrlEventCategory(Categorie $categorie): string
    {
        return self::getBaseUrlSite().Router::EVENT_URL.$categorie->id;
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
                if (is_admin() || ! $wp_query->is_main_query()) {
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

    public function addRouteBottin()
    {
        add_action(
            'init',
            function () {
                add_rewrite_rule(
                    self::BOTTIN_FICHE_URL.'([a-zA-Z0-9-]+)[/]?$',
                    'index.php?'.self::PARAM_BOTTIN_FICHE.'=$matches[1]',
                    'top'
                );
            }
        );
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_BOTTIN_FICHE;

                return $query_vars;
            }
        );
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || ! $wp_query->is_main_query()) {
                    return $template;
                }

                if (get_query_var(self::PARAM_BOTTIN_FICHE) == false ||
                    get_query_var(self::PARAM_BOTTIN_FICHE) == '') {
                    return $template;
                }

                return get_template_directory().'/single-bottin_fiche.php';
            }
        );
    }

    public function addRouteBottinCategory()
    {
        add_action(
            'init',
            function () {
                add_rewrite_rule(
                    self::BOTTIN_CATEGORY_URL.'([a-zA-Z0-9-]+)[/]?$',
                    'index.php?'.self::PARAM_BOTTIN_CATEGORY.'=$matches[1]',
                    'top'
                );
            }
        );
        add_filter(
            'query_vars',
            function ($query_vars) {
                $query_vars[] = self::PARAM_BOTTIN_CATEGORY;

                return $query_vars;
            }
        );
        add_action(
            'template_include',
            function ($template) {
                global $wp_query;
                if (is_admin() || ! $wp_query->is_main_query()) {
                    return $template;
                }

                if (get_query_var(self::PARAM_BOTTIN_CATEGORY) == false ||
                    get_query_var(self::PARAM_BOTTIN_CATEGORY) == '') {
                    return $template;
                }

                return get_template_directory().'/category_bottin.php';
            }
        );
    }

    public function flushRoutes()
    {
        $current = get_current_blog_id();
        foreach (get_sites(['fields' => 'ids']) as $site) {
            switch_to_blog($site);
            flush_rewrite_rules();
        }
        switch_to_blog($current);
    }

    public static function getCurrentUrl():string {
        return get_site_url().esc_url_raw(add_query_arg([]));
    }

    public static function getUrlWww():string {
        $current = preg_replace("#new.marche.be#","www.marche.be",Router::getCurrentUrl());
        return $current;
    }
}

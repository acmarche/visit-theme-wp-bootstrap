<?php

namespace VisitMarche\Theme;

use VisitMarche\Theme\Inc\AdminBar;
use VisitMarche\Theme\Inc\AdminPage;
use VisitMarche\Theme\Inc\Ajax;
use VisitMarche\Theme\Inc\Api;
use VisitMarche\Theme\Inc\AssetsLoad;
use VisitMarche\Theme\Inc\CategoryMetaBox;
use VisitMarche\Theme\Inc\Filter;
use VisitMarche\Theme\Inc\PivotMetaBox;
use VisitMarche\Theme\Inc\QueryAlter;
use VisitMarche\Theme\Inc\RouterHades;
use VisitMarche\Theme\Inc\SecurityConfig;
use VisitMarche\Theme\Inc\Seo;
use VisitMarche\Theme\Inc\SetupTheme;
use VisitMarche\Theme\Inc\ShortCodes;
use VisitMarche\Theme\Inc\WidgetLoad;

/*
 * Initialisation du thème
 */
new SetupTheme();
/*
 * Chargement css, js
 */
new AssetsLoad();
/*
 * Déclaration des zones a widgets
 */
new WidgetLoad();
/*
 * Altération de la requete principale
 */
new QueryAlter();
/*
 * Un peu de sécurité
 */
new SecurityConfig();
/*
 * Ajout de routage pour pivot et bottin
 */
new RouterHades();
/*
 * Pour hades
 */
new PivotMetaBox();
/*
 * Balises pour le référencement
 */
new Seo();
/*
 * Actions sur les filtres de wp
 */
new Filter();
/*
 * Add routes for api
 */
new Api();
/*
 * Gpx viewer
 */
new ShortCodes();
/*
 * Image categories
 */
new CategoryMetaBox();
/*
 * Admin bar edit
 */
new AdminBar();
/*
 * Admin pages
 */
new AdminPage();
/*
 * Ajax for admin
 */
new Ajax();

<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Lib\HadesFiltresListing;
use VisitMarche\Theme\Lib\Pivot_List_Table;
use VisitMarche\Theme\Lib\Router;
use VisitMarche\Theme\Lib\Twig;

class AdminPage
{
    public function __construct()
    {
        add_action('admin_menu', fn($args) => $this::my_add_menu_items());
    }

    function my_add_menu_items()
    {
        add_menu_page(
            'pivot_home',
            'Pivot',
            'activate_plugins',
            'pivot_menu',
            fn($args) => $this::homepageRender(),
            '/AcMarche/Pivot/public/Icone_Pivot_Small.png'
        );
        add_submenu_page(
            'pivot_menu',
            'pivot_filtres',
            'Filtres',
            'manage_options',
            'pivot_filtre_menu',
            fn($args) => $this::filtresRender(),
        );
    }

    function homepageRender()
    {
        $myListTable = new Pivot_List_Table();
        echo '<div class="wrap">
<h2>My List Table Test</h2>';
        $myListTable->prepare_items();
        $myListTable->display();
        echo '</div>';
    }

    function filtresRender()
    {
        $pivotRepository = PivotContainer::getFiltreRepository();
        $filters = $pivotRepository->findWithChildren();

        $categoryUtils = new HadesFiltresListing();
        if (isset($_GET['notempty'])) {
            $categoryUtils->getFiltresNotEmpty($filters);
        }

        $currentUrl = Router::getCurrentUrl();
        $category = get_category_by_slug('offres');
        $categoryUrl = get_category_link($category);

        Twig::rendPage(
            'offre/list.html.twig',
            [
                'url' => '',
                'currentUrl' => $currentUrl,
                'filters' => $filters,
                'categoryUrl' => $categoryUrl,
            ]
        );
    }

    /**
     * Pour aller chercher le css
     * @return void
     */
    function testTable()
    {
        $myListTable = new Pivot_List_Table();
        echo '<div class="wrap"><h2>My List Table Test</h2>';
        $myListTable->prepare_items();
        $myListTable->display();
        echo '</div>';
    }

}

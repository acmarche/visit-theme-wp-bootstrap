<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use VisitMarche\Theme\Lib\HadesFiltresListing;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PivotOffresTable;
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
        add_submenu_page(
            'pivot_menu',
            'pivot_offres',
            'Offres',
            'manage_options',
            'pivot_offre_menu',
            fn($args) => $this::offresRender(),
        );
    }

    function homepageRender()
    {
        Twig::rendPage(
            'admin/home.html.twig',
            [

            ]
        );

    }

    function filtresRender()
    {
        $pivotRepository = PivotContainer::getFiltreRepository();
        $filters = $pivotRepository->findWithChildren();

        $categoryUtils = new HadesFiltresListing();
        if (isset($_GET['notempty'])) {
            $categoryUtils->getFiltresNotEmpty($filters);
        }

        $category = get_category_by_slug('offres');
        $categoryUrl = get_category_link($category);
        $urlAdmin = admin_url('admin.php?page=pivot_offre_menu&filtreId=');

        Twig::rendPage(
            'admin/filtres_list.html.twig',
            [
                'filters' => $filters,
                'urlAdmin' => $urlAdmin,
                'categoryUrl' => $categoryUrl,
            ]
        );
    }

    function offresRender()
    {
        $filtreId = (int)$_GET['filtreId'] ?? 0;
        if ($filtreId < 1) {
            Twig::rendPage(
                'admin/error.html.twig',
                [
                    'message' => 'Choisissez un filtre dans le menu',
                ]
            );

            return;
        }
        $language = LocaleHelper::getSelectedLanguage();
        $filtreRepository = PivotContainer::getFiltreRepository();
        $filtres = $filtreRepository->findByReferences([$filtreId]);
        if (count($filtres) == 0) {
            Twig::rendPage(
                'admin/error.html.twig',
                [
                    'message' => 'Le filtre n\'a pas été trouvé dans la base de donnée',
                ]
            );

            return;
        }
        $pivotRepository = PivotContainer::getRepository();
        $offres = $pivotRepository->getOffres([$filtreId]);
        $pivotOffresTable = new PivotOffresTable();
        $pivotOffresTable->data = $offres;
        $pivotOffresTable->categoryId = 14;
        ?>
        <div class="wrap">
            <h2>Les offres pour <?php echo $filtres[0]->nom; ?></h2>
            <?php $pivotOffresTable->prepare_items();
            $pivotOffresTable->display();
            ?>
        </div>
        <?php
    }

    /**
     * Pour aller chercher le css
     * @return void
     */
    function testTable()
    {
        $myListTable = new PivotOffresTable();
        echo '<div class="wrap"><h2>My List Table Test</h2>';
        $myListTable->prepare_items();
        $myListTable->display();
        echo '</div>';
    }

}

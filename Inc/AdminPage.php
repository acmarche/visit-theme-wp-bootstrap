<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
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
            'pivot_home',
            fn($args) => $this::homepageRender(),
            '/AcMarche/Pivot/public/Icone_Pivot_Small.png'
        );
        add_submenu_page(
            'pivot_home',
            'pivot_filtres',
            'Filtres',
            'manage_options',
            'pivot_filtres',
            fn($args) => $this::filtresRender(),
        );
        add_submenu_page(
            'pivot_home',
            'pivot_offres',
            'Liste des offres',
            'manage_options',
            'pivot_offres',
            fn($args) => $this::offresRender(),
        );
        add_submenu_page(
            'pivot_home',
            'pivot_offre',
            'Détail d\'une Offre',
            'manage_options',
            'pivot_offre',
            fn($args) => $this::offreRender(),
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
        $urlAdmin = admin_url('admin.php?page=pivot_offres&filtreId=');

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

    function offreRender()
    {
        $codeCgt = $_GET['codeCgt'] ?? null;
        if (!$codeCgt) {
            Twig::rendPage(
                'admin/error.html.twig',
                [
                    'message' => 'Choisissez une offre dans la liste par filtre',
                ]
            );

            return;
        }
        $pivotRepository = PivotContainer::getRepository();
        $offre = $pivotRepository->getOffreByCgtAndParse($codeCgt, Offre::class);
        if (!$offre) {
            Twig::rendPage(
                'admin/error.html.twig',
                [
                    'message' => 'Offre non trouvée',
                ]
            );

            return;
        }
        Twig::rendPage(
            'admin/offre.html.twig',
            [
                'offre' => $offre,
            ]
        );
    }
}

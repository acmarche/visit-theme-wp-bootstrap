<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;
use AcMarche\Pivot\Entities\Offre\Offre;
use VisitMarche\Theme\Lib\LocaleHelper;
use VisitMarche\Theme\Lib\PivotCategoriesTable;
use VisitMarche\Theme\Lib\PivotOffresTable;
use VisitMarche\Theme\Lib\Twig;
use VisitMarche\Theme\Lib\WpRepository;

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
            'edit_posts',
            'pivot_home',
            fn() => $this::homepageRender(),
            get_template_directory_uri().'/assets/images/Icone_Pivot_Small.png'
        );
        add_submenu_page(
            'pivot_home',
            'Pivot filtres',
            'Filtres',
            'edit_posts',
            'pivot_filtres',
            fn() => $this::filtresRender(),
        );
        add_submenu_page(
            'pivot_home',
            'Pivot offres',
            'Liste des offres',
            'edit_posts',
            'pivot_offres',
            fn() => $this::offresRender(),
        );
        add_submenu_page(
            'pivot_home',
            'Pivot offre',
            'Détail d\'une Offre',
            'edit_posts',
            'pivot_offre',
            fn() => $this::offreRender(),
        );
        add_submenu_page(
            'pivot_home',
            'Catégories avec filtres',
            'Catégories avec filtres',
            'edit_posts',
            'pivot_categories_filtre',
            fn() => $this::categoriesFiltresRender(),
        );
    }

    private static function homepageRender()
    {
        Twig::rendPage(
            'admin/home.html.twig',
            [

            ]
        );

    }

    private static function filtresRender()
    {
        $pivotRepository = PivotContainer::getFiltreRepository();
        $filters = $pivotRepository->findWithChildren();

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

    private static function offresRender()
    {
        $filtre = $_GET['filtreId'] ?? null;
        if (!$filtre) {
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
        $filtres = $filtreRepository->findByReferencesOrUrns([$filtre]);
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
        $pivotRemoteRepository = PivotContainer::getRemoteRepository();
        var_dump(11111111111);
        try {
            $pivotRemoteRepository->query();
        } catch (\Exception $exception) {
            var_dump($exception);
        }
var_dump(11111111111);
        $offres = $pivotRepository->getOffres($filtres);
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

    private static function offreRender()
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

    private static function categoriesFiltresRender()
    {
        $categories = [];
        $wpRepository = new WpRepository();
        foreach ($wpRepository->getCategoriesFromWp() as $category) {
            $filtres = $wpRepository->getCategoryFilters($category->term_id);
            if (count($filtres) > 0) {
                $categories[] = $category;
            } else {
                $categoryFiltres = get_term_meta($category->term_id, FiltreMetaBox::HADES_REFRUBRIQUE, true);
                if ($categoryFiltres != '') {
                    $categories[] = $category;
                }
            }
        }
        $pivotOffresTable = new PivotCategoriesTable();
        $pivotOffresTable->data = $categories;
        ?>
        <div class="wrap">
            <h2>Les catégories avec des filtres</h2>
            <?php $pivotOffresTable->prepare_items();
            $pivotOffresTable->display();
            ?>
        </div>
        <?php
    }
}

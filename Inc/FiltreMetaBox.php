<?php

namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\DependencyInjection\PivotContainer;

class FiltreMetaBox
{
    public const PIVOT_REFRUBRIQUE = 'pivot_refrubrique';

    public function __construct()
    {
        add_action(
            'category_edit_form_fields',
            fn($tag) => $this::hades_metabox_edit($tag),
            10,
            1
        );
        add_action(
            'edited_category',
            fn($term_id) => $this::save_hades_metadata($term_id),
            10,
            1
        );

    }

    public static function hades_metabox_edit($tag): void
    {
        wp_enqueue_script(
            'react-app',
            get_template_directory_uri().'/assets/js/build/filtre.js',
            ['wp-element'],
            wp_get_theme()->get('Version'),
            true
        );

        $pivotRepository = PivotContainer::getFiltreRepository();
        $term_id = $tag->term_id;
        $types = $pivotRepository->findWithChildren();
        $hades_refrubrique = get_term_meta($term_id, self::PIVOT_REFRUBRIQUE, true);
        if (!is_array($hades_refrubrique)) {
            $hades_refrubrique = [];
        }
        ?>

        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="bottin_refrubrique">Référence pivot</label>
                </th>
                <td>
                    <div id="filtres-box" data-category-id="<?php echo $term_id ?>">

                    </div>
                    <p class="description">Indiquer les références hades, séparées par une virgule</p>
                    <p class="description"><a href="/index-des-offres" target="_blank">Liste des références</a></p>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_hades_metadata($categoryId): void
    {
        $meta_key = self::PIVOT_REFRUBRIQUE;
        if (isset($_POST[$meta_key]) && [] !== $_POST[$meta_key]) {
            $filtres = $_POST[$meta_key];
            update_term_meta($categoryId, $meta_key, $filtres);
        } else {
            delete_term_meta($categoryId, $meta_key);
        }
    }
}

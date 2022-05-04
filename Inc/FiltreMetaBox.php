<?php

namespace VisitMarche\Theme\Inc;

class FiltreMetaBox
{
    public const PIVOT_REFRUBRIQUE = 'pivot_refrubrique';
    public const HADES_REFRUBRIQUE = 'hades_refrubrique';

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

    public static function hades_metabox_edit(\WP_Term $term): void
    {
        wp_enqueue_script(
            'react-app',
            get_template_directory_uri().'/assets/js/build/filtre.js',
            ['wp-element'],
            wp_get_theme()->get('Version'),
            true
        );
        $oldFiltres = get_term_meta($term->term_id, FiltreMetaBox::HADES_REFRUBRIQUE, true);
        //  delete_term_meta($term->term_id, self::PIVOT_REFRUBRIQUE);
        //  $filtres = get_term_meta($term->term_id, FiltreMetaBox::PIVOT_REFRUBRIQUE, true);
        //  dump($filtres);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="bottin_refrubrique">Références pivot</label>
                </th>
                <td>
                    <p class="description">
                        <a href="<?php echo admin_url('admin.php?page=pivot_filtre_menu') ?>" target="_blank">
                            Liste des filtres</a>
                    </p>
                    <br/>
                    <p>Anciens filtres hades: <?php echo $oldFiltres ?></p>
                    <br/>
                    <div id="filtres-box" data-category-id="<?php echo $term->term_id ?>">

                    </div>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_hades_metadata($categoryId): void
    {
        /*  $meta_key = self::PIVOT_REFRUBRIQUE;
          if (isset($_POST[$meta_key]) && [] !== $_POST[$meta_key]) {
              $filtres = $_POST[$meta_key];
              update_term_meta($categoryId, $meta_key, $filtres);
          } else {
              delete_term_meta($categoryId, $meta_key);
          }*/
    }
}

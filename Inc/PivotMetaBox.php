<?php

namespace VisitMarche\Theme\Inc;

class PivotMetaBox
{
    public const PIVOT_REFRUBRIQUE = 'pivot_refrubrique';

    public function __construct()
    {
        add_action(
            'category_edit_form_fields',
            fn($tag) => $this::pivot_metabox_edit($tag),
            10,
            1
        );
    }

    public static function pivot_metabox_edit(\WP_Term $term): void
    {
        wp_enqueue_script(
            'vue-admin-js',
            get_template_directory_uri().'/assets/js/dist/js/appFiltreAdmin-vuejf.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
        wp_enqueue_style(
            'vue-admin-css',
            get_template_directory_uri().'/assets/js/dist/js/appFiltreAdmin-vuejf.css',
            [],
            wp_get_theme()->get('Version'),
        );
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="bottin_refrubrique">Références pivot</label>
                </th>
                <td>
                    <p class="description">
                        <a href="<?php echo admin_url('admin.php?page=pivot_filtres') ?>" target="_blank">
                            Liste des filtres</a>
                    </p>
                    <br/>
                    <div id="filtres-box" data-category-id="<?php echo $term->term_id ?>">

                    </div>
                </td>
            </tr>
        </table>
        <?php
    }
}
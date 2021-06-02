<?php

namespace VisitMarche\Theme\Inc;

class CategoryMetaBox
{
    const KEY_NAME_HEADER = 'visit_category_header';
    const KEY_NAME_ICONE = 'visit_category_icone';
    const KEY_NAME_COLOR = 'visit_category_color';

    public function __construct()
    {
        add_action(
            'category_edit_form_fields',
            [$this, 'visit_metabox_cat'],
            10,
            1
        );
        add_action(
            'edited_category',
            [$this, 'save_metadata'],
            10,
            1
        );
        register_term_meta('category', self::KEY_NAME_COLOR, ['show_in_rest' => true]);
        register_term_meta('category', self::KEY_NAME_ICONE, ['show_in_rest' => true]);
        register_term_meta('category', self::KEY_NAME_HEADER, ['show_in_rest' => true]);
        add_filter(
            'rest_prepare_category',
            function ($response, $item, $request) {

                $header = get_term_meta($item->term_id, CategoryMetaBox::KEY_NAME_HEADER, true);
                $icone = get_term_meta($item->term_id, CategoryMetaBox::KEY_NAME_ICONE, true);
                $bgcat = get_term_meta($item->term_id, CategoryMetaBox::KEY_NAME_COLOR, true);

                $response->data['bgcat'] = $bgcat ?: null;
                $response->data['icone'] = $icone ?: null;
                $response->data['header'] = $header ?: null;

                return $response;
            },
            10,
            3
        );
    }

    public static function visit_metabox_cat($tag)
    {
        $single = true;
        $term_id = $tag->term_id;
        $hades_name_header = get_term_meta($term_id, self::KEY_NAME_HEADER, $single);
        $hades_name_icone = get_term_meta($term_id, self::KEY_NAME_ICONE, $single);
        $hades_name_color = get_term_meta($term_id, self::KEY_NAME_COLOR, $single);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top"><label for="bottin_refrubrique">Image de fond de la catégorie</label></th>
                <td>
                    <label>
                        <input type="text" name="<?php echo self::KEY_NAME_HEADER ?>" style="width: 100%;"
                               autocomplete="off"
                               value="<?php echo $hades_name_header ?>">
                    </label>
                    <p class="description">Jf uniquement</p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="bottin_refrubrique">Icone pour les 5 thèmes</label></th>
                <td>
                    <label>
                        <input type="text" name="<?php echo self::KEY_NAME_ICONE ?>" style="width: 100%;"
                               autocomplete="off"
                               value="<?php echo $hades_name_icone ?>">
                    </label>
                    <p class="description">Jf uniquement</p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="bottin_refrubrique">Icone pour les 5 thèmes</label></th>
                <td>
                    <label>
                        <input type="text" name="<?php echo self::KEY_NAME_COLOR ?>" style="width: 100%;"
                               autocomplete="off"
                               value="<?php echo $hades_name_color ?>">
                    </label>
                    <p class="description">Jf uniquement</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_metadata($term_id)
    {
        $meta_key_header = self::KEY_NAME_HEADER;
        $meta_key_icone = self::KEY_NAME_ICONE;
        $meta_key_color = self::KEY_NAME_COLOR;

        if (isset($_POST[$meta_key_header]) && $_POST[$meta_key_header] != '') {
            $value = $_POST[$meta_key_header];
            update_term_meta($term_id, $meta_key_header, $value);
        } else {
            delete_term_meta($term_id, $meta_key_header);
        }

        if (isset($_POST[$meta_key_icone]) && $_POST[$meta_key_icone] != '') {
            $value = $_POST[$meta_key_icone];
            update_term_meta($term_id, $meta_key_icone, $value);
        } else {
            delete_term_meta($term_id, $meta_key_icone);
        }
        if (isset($_POST[$meta_key_color]) && $_POST[$meta_key_color] != '') {
            $value = $_POST[$meta_key_color];
            update_term_meta($term_id, $meta_key_color, $value);
        } else {
            delete_term_meta($term_id, $meta_key_color);
        }
    }


}

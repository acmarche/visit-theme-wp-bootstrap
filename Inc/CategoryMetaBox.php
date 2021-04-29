<?php

namespace VisitMarche\Theme\Inc;

class CategoryMetaBox
{
    const KEY_NAME = 'visit_category_header';

    public function __construct()
    {
        add_action(
            'category_edit_form_fields',
            [$this, 'visit_metabox_edit'],
            10,
            1
        );
        add_action(
            'edited_category',
            [$this, 'save_metadata'],
            10,
            1
        );
    }

    public static function visit_metabox_edit($tag)
    {
        $single = true;
        $term_id = $tag->term_id;
        $hades_refrubrique = get_term_meta($term_id, self::KEY_NAME, $single);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top"><label for="bottin_refrubrique">Image catégorie</label></th>
                <td>
                    <label>
                        <input type="text" name="<?php echo self::KEY_NAME?>" style="width: 100%;" autocomplete="off"
                               value="<?php echo $hades_refrubrique ?>">
                    </label>
                    <p class="description">...</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_metadata($term_id)
    {
        $meta_key = self::KEY_NAME;

        if (isset($_POST[$meta_key]) && $_POST[$meta_key] != '') {
            $value = $_POST[$meta_key];
            update_term_meta($term_id, $meta_key, $value);
        } else {
            delete_term_meta($term_id, $meta_key);
        }
    }

}

<?php


namespace VisitMarche\Theme\Inc;

use AcMarche\Pivot\Hades;

class CategoryMetaBox
{
    const KEY_NAME_BOTTIN = 'bottin_refrubrique';
    const KEY_NAME_HADES = 'hades_refrubrique';

    public function __construct()
    {
        add_action(
            'category_edit_form_fields',
            [$this, 'hades_metabox_edit'],
            10,
            1
        );
        add_action(
            'edited_category',
            [$this, 'save_hades_metadata'],
            10,
            1
        );
    }

    public static function hades_metabox_edit($tag)
    {
        $single = true;
        $term_id = $tag->term_id;
        $hades_refrubrique = get_term_meta($term_id, self::KEY_NAME_HADES, $single);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top"><label for="bottin_refrubrique">Référence hades</label></th>
                <td>
                    <label>
                        <select name="<?php echo self::KEY_NAME_HADES ?>">
                            <option value="0"></option>
                            <option value="<?php echo Hades::EVENEMENTS_KEY ?>"<?php if ($hades_refrubrique == Hades::EVENEMENTS_KEY) {
                                echo 'selected = "selected"';
                            } ?>>
                                Evènements
                            </option>
                            <option value="<?php echo Hades::HEBERGEMENTS_KEY ?>"<?php if ($hades_refrubrique == Hades::HEBERGEMENTS_KEY) {
                                echo 'selected = "selected"';
                            } ?>>Hébergements
                            </option>
                            <option value="<?php echo Hades::RESTAURATIONS_KEY ?>"<?php if ($hades_refrubrique == Hades::RESTAURATIONS_KEY) {
                                echo 'selected = "selected"';
                            } ?>>Restaurations
                            </option>
                        </select>
                    </label>
                    <p class="description">Indiquer la référence correspondant à la rubrique.</p>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_hades_metadata($term_id)
    {
        $meta_key = self::KEY_NAME_HADES;

        if (isset($_POST[$meta_key]) && $_POST[$meta_key] != '') {
            $meta_value = $_POST[$meta_key];
            update_term_meta($term_id, $meta_key, $meta_value);
        } else {
            delete_term_meta($term_id, $meta_key);
        }
    }

}

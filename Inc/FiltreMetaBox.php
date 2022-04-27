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
        $pivotRepository = PivotContainer::getRepository();
        $term_id = $tag->term_id;
        $types = $pivotRepository->getTypesOffre();
        $hades_refrubrique = get_term_meta($term_id, self::PIVOT_REFRUBRIQUE, true);
        ?>
        <table class="form-table">
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="bottin_refrubrique">Référence pivot</label>
                </th>
                <td>
                        <select name="<?php echo self::PIVOT_REFRUBRIQUE ?>[]" multiple style="height: 250px;" id="bottin_refrubrique">
                            <option value="0">Choisissez une ou plusieurs</option>
                            <?php foreach ($types as $key => $type) { ?>
                                <option value="<?php echo $key ?>"
                                    <?php if (in_array($key, $hades_refrubrique)) {
                                        echo "selected";
                                    } ?>>
                                    <?php echo $type ?>
                                </option>
                            <?php } ?>
                        </select>
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

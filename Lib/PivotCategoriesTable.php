<?php

namespace VisitMarche\Theme\Lib;

use VisitMarche\Theme\Inc\PivotMetaBox;
use WP_List_Table;

class PivotCategoriesTable extends WP_List_Table
{
    /**
     * @var \WP_Term[] $data
     */
    public array $data;

    function get_columns()
    {
        $columns = array(
            'nom' => 'Nom',
            'filtres' => 'Urn',
        );

        return $columns;
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->data;
    }

    /**
     * @param \WP_Term $item
     * @param string $column_name
     * @return string|void
     */
    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'nom':
                $url = get_category_link($item->term_id);

                return '<a href="'.$url.'">'.$item->name.'</a>';
            case 'filtres':
                return $this->getFiltres($item);
            default:
                return '';
        }
    }

    function getFiltres(\WP_Term $item): string
    {
        $categoryFiltres = get_term_meta($item->term_id, PivotMetaBox::PIVOT_REFRUBRIQUE, true);
        if (is_array($categoryFiltres)) {
            return join(',', $categoryFiltres);
        }

        return '';
    }
}
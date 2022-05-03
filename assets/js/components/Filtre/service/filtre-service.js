import axios from '../../Axios';

/**
 * @param {string} language
 * @param {string} categoryId
 * @returns {Promise}
 */
export function fetchFiltresByCategory( language, categoryId ) {
    const params = {};
    const url = `${language}/wp-json/pivot/filtres_category/${categoryId}`;
    return axios.get( url, {
        params
    });
}

/**
 * @param {int} parentId
 * @returns {Promise}
 */
export function fetchFiltresByParent( parentId ) {
    const params = {};
    const url = `wp-json/pivot/filtres_parent/${parentId}`;
    return axios.get( url, {
        params
    });
}

/**
 * @param {int} categoryId
 * @param {int} reference
 * @returns {Promise}
 */
export function deleteFiltreRequest( categoryId, reference ) {
    const url = 'wp-admin/admin-ajax.php';
    const formData = new FormData();
    formData.append( 'action', 'my_action' );
    formData.append( 'categoryId', categoryId );
    formData.append( 'reference', reference );
    return axios.post( url, formData );
}

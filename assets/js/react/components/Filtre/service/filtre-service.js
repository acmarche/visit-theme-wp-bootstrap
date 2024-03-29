import axios from '../../Axios';

/**
 * @param {string} language
 * @param {int} categoryId
 * @param {int} flatWithChildren
 * @returns {Promise}
 */
export function fetchFiltresByCategoryRequest(
    language,
    categoryId,
    flatWithChildren = 0
) {
    const params = {};
    const url = `${language}/wp-json/pivot/filtres_category/${categoryId}/${flatWithChildren}`;
    return axios.get( url, {
        params
    });
}

/**
 * @param {int} parentId
 * @returns {Promise}
 */
export function fetchFiltresByParentRequest( parentId ) {
    const params = {};
    const url = `wp-json/pivot/filtres_parent/${parentId}`;
    return axios.get( url, {
        params
    });
}

/**
 * @param {int} categoryId
 * @param {int} id
 * @returns {Promise}
 */
export function deleteFiltreRequest( categoryId, id ) {
    const url = 'wp-admin/admin-ajax.php';
    const formData = new FormData();
    formData.append( 'action', 'action_delete_filtre' );
    formData.append( 'categoryId', categoryId );
    formData.append( 'id', id );
    return axios.post( url, formData );
}

/**
 * @param {int} categoryId
 * @param {int} parentId
 * @param {int} childId
 * @returns {Promise}
 */
export function addFiltreRequest( categoryId, parentId, childId ) {
    const url = 'wp-admin/admin-ajax.php';
    const formData = new FormData();
    formData.append( 'action', 'action_add_filtre' );
    formData.append( 'categoryId', categoryId );
    formData.append( 'parentId', parentId );
    formData.append( 'childId', childId );
    return axios.post( url, formData );
}

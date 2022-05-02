import axios from '../../Axios';

/**
 * @param {string} language
 * @param {string} categoryId
 * @returns {Promise}
 */
export function fetchFiltresByCategory( language, categoryId ) {
    const params = {};
    const url = `${language}/wp-json/pivot/filtres/${categoryId}`;
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
    const url = `wp-json/pivot/allfiltres/${parentId}`;
    return axios.get( url, {
        params
    });
}

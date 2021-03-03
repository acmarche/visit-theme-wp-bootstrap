import axios from '../../Axios';

/**
 * @param {string|null} category
 * @returns {Promise}
 */
export function fetchFiltres( category ) {
    const params = {};
    const url = `wp-json/hades/filtres/${category}`;

    return axios.get( url, {
        params
    });
}

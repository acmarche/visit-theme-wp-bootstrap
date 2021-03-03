import axios from '../../Axios';

/**
 * @param {string|null} category
 * @returns {Promise}
 */
export function fetchOffres( category ) {
    const params = {};
    const url = `wp-json/hades/offres/${category}`;

    return axios.get( url, {
        params
    });
}

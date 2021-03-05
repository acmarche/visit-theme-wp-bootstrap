import axios from '../../Axios';

/**
 * @param {int} categoryId
 * @param {string} codeHades
 * @returns {Promise}
 */
export function fetchOffres( categoryId, codeHades ) {
    const params = {};
    const url = `wp-json/hades/offres/${categoryId}/${codeHades}`;

    return axios.get( url, {
        params
    });
}

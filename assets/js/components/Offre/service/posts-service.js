import axios from '../../Axios';

/**
 * @param {int} categoryId
 * @param {string|null} filtre
 * @returns {Promise}
 */
export function fetchOffres( categoryId, filtre ) {
    const params = {};

    if ( filtre ) {
        params.filtre = filtre;
    }

    const url = `wp-json/hades/offres/${categoryId}`;

    return axios.get( url, {
        params
    });
}

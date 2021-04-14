import axios from '../../Axios';

/**
 * @param {int} categoryId
 * @param {string|null} filtre
 * @returns {Promise}
 */
export function fetchOffres( categoryId, filtre ) {
    let url = `wp-json/hades/offres/${categoryId}`;

    if ( null !== filtre && '0' !== filtre ) {
        url = url.concat( `/${filtre}` );
    }

    return axios.get( url, {

    });
}

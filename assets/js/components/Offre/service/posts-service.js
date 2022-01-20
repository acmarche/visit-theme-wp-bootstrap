import axios from '../../Axios';

/**
 * @param {string} language
 * @param {int} categoryId
 * @param {string|null} filtre
 * @returns {Promise}
 */
export function fetchOffres( language, categoryId, filtre ) {
    let url = `${language}/wp-json/hades/offres/${categoryId}`;

    if ( null !== filtre && '0' !== filtre ) {
        url = url.concat( `/${filtre}` );
    }

    console.log( url );
    return axios.get( url, {

    });
}

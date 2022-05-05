import axios from '../../Axios';

/**
 * @param {string} language
 * @param {int} categoryId
 * @param {int} filtre
 * @returns {Promise}
 */
export function fetchOffres( language, categoryId, filtre ) {
    const url = `${language}/wp-json/pivot/offres/${categoryId}/${filtre}`;
    return axios.get( url, {

    });
}

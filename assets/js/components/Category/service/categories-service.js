import axios from '../../Axios';

/**
 * @param {int|null} mainCategoryId
 * @returns {Promise}
 */
export function fetchCategories(mainCategoryId ) {
    const params = {};

    if ( mainCategoryId ) {
        params.parent = mainCategoryId;
    }

    params._fields = 'name, id, description';
    params.per_page = 100;

    const url = 'wp-json/wp/v2/categories';

    return axios.get( url, {
        params
    });
}

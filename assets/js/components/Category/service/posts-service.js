import axios from '../../Axios';

/**
 * @param {string|null} siteSlug
 * @param {int|null} mainCategoryId
 * @returns {Promise}
 */
export function fetchPosts( siteSlug, mainCategoryId ) {
    const params = {};

    if ( mainCategoryId ) {

        //params.parent = mainCategoryId;
    }

    const url = `${siteSlug}wp-json/jfs/v1/all/${mainCategoryId}`;

    return axios.get( url, {
        params
    });
}

const defaults = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
    ...defaults,
    mode: 'production',
    entry: {
        category: [
            `${path.resolve( __dirname, 'src' )}/category.js`
        ],
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM'
    }
};

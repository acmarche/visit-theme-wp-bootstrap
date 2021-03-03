const defaults = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
    ...defaults,
    mode: 'production',
    entry: {
        offre: [
            `${path.resolve( __dirname, 'src' )}/offre.js`
        ]
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM'
    }
};

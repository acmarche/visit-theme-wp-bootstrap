const defaults = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
    ...defaults,
    mode: 'production',
    entry: {
        offre: [
            `${path.resolve( __dirname, 'src' )}/offre.js`
        ],
        filtre: [
            `${path.resolve( __dirname, 'src' )}/filtre.js`
        ]
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM'
    }
};

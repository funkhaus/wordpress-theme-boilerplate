const path = require('path')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const resolve = file => path.resolve(__dirname, file)
const postcssImport = require('postcss-import')
const autoprefixer = require('autoprefixer')
const postcssUrl = require('postcss-url')
const webpack = require('webpack')

const config = {
    entry: './main',
    output: {
        path: '../static',
        filename: 'bundle.js'
    },
    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract(
                  'style-loader',
                  'css-loader?-minimize!postcss-loader'
                )
            }, {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel'
            },
            {
                test: /\.(png|woff|woff2|eot|ttf|svg)$/,
                loader: 'url-loader?limit=100000'
            }
        ]
    },
    babel: {
        presets: ['es2015'],
        plugins: ['transform-runtime']
    },
    postcss: function () {
        return [postcssImport, postcssUrl, autoprefixer]
    },
    resolve: {
        alias: {
            'replaceSVGs': resolve('libs/replaceSVGs.js')
        }
    },
    plugins: [
        new ExtractTextPlugin('bundle.css')
    ]
}

if ( process.env.NODE_ENV === 'production' ) {

    // add these plugins for production mode
    config.plugins = config.plugins.concat([
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        }),
        new webpack.optimize.OccurenceOrderPlugin()
    ])

} else {

    config.devtool = '#source-map'

}

module.exports = config

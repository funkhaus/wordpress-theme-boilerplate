var webpack = require('webpack')
var path = require('path')

var config = {
    entry: './index',
    output: {
        path: '../js',
        filename: 'bundle.js'
    },

    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: 'style!css'
            }, {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    plugins: [

    ]
}

if (process.env.NODE_ENV === 'production') {

    config.plugins.concat([
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: '"production"'
            }
        }),
        new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            }
        }),
        new webpack.optimize.OccurenceOrderPlugin()
    ])

} else {

    config.devServer = {
        hot: true,
        inline: true,
        quiet: true,
        noInfo: true,
        contentBase: path.join(__dirname, 'static'),
        historyApiFallback: true
    }
    config.devtool = '#source-map'

}

module.exports = config

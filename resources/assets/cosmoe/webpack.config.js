// var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");


module.exports = {
    entry: {
        bundle: ['vue', 'vuex', 'vue-router', 'vue-resource', 'vue-touch', 'bootstrap/less/bootstrap.less', 'font-awesome/less/font-awesome.less', './src/index.js'],
    },

    output: {
        path: './build',
        filename: "[name].js",
    },

    externals: [

    ],

    devtool: 'source-map',

    module: {
        loaders: [
            {test: require.resolve('vue'), loader: 'expose?Vue'},
            {test: /\.(js|jsx)$/, loader: 'babel', exclude: /node_modules/},
            {test: /\.sass$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader!sass-loader')},
            {test: /\.scss$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader!scss-loader')},
            {test: /\.less$/, loader: ExtractTextPlugin.extract('style-loader', 'css-loader!less-loader')},
            {test: /\.css$/, loader: ExtractTextPlugin.extract("style-loader", "css-loader")},
            {test: /\.vue$/, loader: 'vue-loader'},
            {test: /\.(gif|jpg|png)\??.*$/, loader: 'url-loader?limit=4096&name=images/[name].[ext]?v=[hash]'},
            {test: /\.(woff|svg|eot|ttf|woff2|woff)\??.*$/, loader: 'url-loader?limit=4096&name=fonts/[name].[ext]?v=[hash]'},
        ]
    },

    babel: {
        presets: ['es2015'],
        plugins: ['transform-runtime'],
        compact : true,
    },

    vue: {
        loaders: {
            js: 'babel',
            // sass: ExtractTextPlugin.extract('style-loader', 'css-loader!sass-loader'),
            // scss: ExtractTextPlugin.extract('style-loader', 'css-loader!scss-loader'),
            // less: ExtractTextPlugin.extract('style-loader', 'css-loader!less-loader'),
            // css: ExtractTextPlugin.extract('style-loader', 'css-loader'),
        }
    },


    plugins: [
        new ExtractTextPlugin("[name].css"),
    ],
};

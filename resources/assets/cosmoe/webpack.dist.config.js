var webpack = require('webpack');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = require('./webpack.config');


module.exports.output.path = './dist';
module.exports.plugins.push(new webpack.optimize.UglifyJsPlugin(
    {
        mangle: {
            // except: ['Vue']
        },
        compress: {
            warnings: false
        },
    }
));

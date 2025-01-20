const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    entry: {
        app: './src/js/app.js', // Front area entry
        admin: './src/_admin/js/admin.js', // Admin area entry
    },
    output: {
        path: path.resolve(__dirname, '../www'),
        filename: (pathData) => {
            return pathData.chunk.name === 'admin'
                ? 'admin/js/[name].js' // Admin output path
                : 'js/[name].js';      // Front output path
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                },
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader',
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: (pathData) => {
                return pathData.chunk.name === 'admin'
                    ? 'admin/css/[name].css' // Admin CSS output path
                    : 'css/[name].css';     // Front CSS output path
            },
        }),
    ]
};
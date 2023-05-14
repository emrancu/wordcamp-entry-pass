const {VueLoaderPlugin} = require("vue-loader");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const devServer = {
    static: {
        directory: '',
    },
    port: 8080
}

module.exports = [
    {
        devServer: devServer,
        entry: {
            app: __dirname + '/resources/js/app.js'
        },
        output: {
            path: __dirname + '/public/js'
        },
        module: {
            rules: [
                {
                    test: /\.vue$/,
                    loader: 'vue-loader'
                },
                {
                    test: /\.js$/,
                    loader: 'babel-loader'
                },
                {
                    test: /\.(scss|css)$/,
                    exclude: /node_modules/,
                    use: ["vue-style-loader", "css-loader", "sass-loader"]
                }
            ]
        },
        plugins: [
            new VueLoaderPlugin()
        ]
    },
    {
        devServer: devServer,
        entry: {
            app: __dirname + '/resources/css/app.css'
        },
        output: {
            path: __dirname + '/public/css'
        },
        module: {
            rules: [
                {
                    test: /\.css$/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        "css-loader",
                        "postcss-loader",
                    ]
                }
            ]
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: "[name].css",
                chunkFilename: "[id].css",
            })
        ]
    }
]

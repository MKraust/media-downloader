const path = require('path')
const webpack = require('webpack')

module.exports = {
  plugins: [
    new webpack.IgnorePlugin({
      resourceRegExp: /^\.\/locale$/,
      contextRegExp: /moment$/,
    })
  ],
  resolve: {
    extensions: ['.ts', '.tsx', '.js', '.jsx'],
    alias: {
      '@': path.resolve(__dirname, 'resources/js/app'),
      '@metronic': path.resolve(__dirname, 'resources/js/_metronic')
    },
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        loader: 'ts-loader',
        exclude: /node_modules/,
        options: {
          appendTsSuffixTo: [/\.vue$/],
        },
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                includePaths: ['node_modules', 'resources/js/src/assets'],
              },
            },
          },
        ],
      },
    ],
  },
}

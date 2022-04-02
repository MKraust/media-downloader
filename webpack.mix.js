const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

const webpackConfig = require('./webpack.config');

mix.js('resources/js/main.tsx', 'public/js')
  .version()
  .sourceMaps()
  .webpackConfig(webpackConfig);

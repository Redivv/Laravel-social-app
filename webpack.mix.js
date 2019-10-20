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

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/talk.js', 'public/chat/js')
    .js('resources/js/register.js', 'public/js')
    .js('resources/js/searcher.js', 'public/js')
    .js('node_modules/emojionearea/dist/emojionearea.js', 'public/js')
    .js('resources/js/profile.js', 'public/js')
    .sass('resources/sass/chat.scss', 'public/chat/css')
    .sass('resources/sass/app.scss', 'public/css');

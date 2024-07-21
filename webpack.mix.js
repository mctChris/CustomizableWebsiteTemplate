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


// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');


//admin
mix.js('resources/js/admin.js', 'public/assets/admin/js')
    // .js('resources/js/base/admin.listing.js', 'public/assets/admin/js')
    // .js('resources/js/base/admin.detail.js', 'public/assets/admin/js')
    // .js('resources/js/base/admin.arrangement.js', 'public/assets/admin/js')
    // .js('resources/js/base/admin.other.js', 'public/assets/admin/js')
    .sass('resources/sass/admin.scss', 'public/assets/admin/css');



// fontend
mix.js('resources/js/app.js', 'public/assets/frontend/common/js')
   .sass('resources/sass/app.scss', 'public/assets/frontend/common/css');

const mix = require("laravel-mix");

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

mix.js("resources/js/app-user.js", "public/js").sass(
    "resources/sass/app-user.scss",
    "public/css"
);

mix.sass("resources/sass/credential.scss", "public/css");
mix.sass("resources/sass/pos.scss", "public/css");
mix.sass("resources/sass/payment.scss", "public/css");
mix.sass("resources/sass/invoice.scss", "public/css");
mix.sass("resources/sass/stock.scss", "public/css");

mix.js("resources/js/app-admin.js", "public/js").sass(
    "resources/sass/app-admin.scss",
    "public/css"
);

// mix.browserSync("dealership.test");

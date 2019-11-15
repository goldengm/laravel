let mix = require('laravel-mix');

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
 
// Sass compile Code
mix.sass('resources/assets/sass/app.scss', 'public/css')
.sass('resources/assets/sass/app.theme.1.scss', 'public/css')
.sass('resources/assets/sass/app.theme.2.scss', 'public/css')
.sass('resources/assets/sass/app.theme.3.scss', 'public/css')
.sass('resources/assets/sass/app.theme.4.scss', 'public/css')
.sass('resources/assets/sass/app.theme.5.scss', 'public/css')
.sass('resources/assets/sass/app.theme.6.scss', 'public/css')
.sass('resources/assets/sass/app.theme.7.scss', 'public/css')
.sass('resources/assets/sass/app.theme.8.scss', 'public/css')
.sass('resources/assets/sass/app.theme.9.scss', 'public/css')
.sass('resources/assets/sass/jquery-ui.scss', 'public/css')
.sass('resources/assets/sass/font-awesome.scss', 'public/css')
.sass('resources/assets/sass/rtl.scss', 'public/css')
.sass('resources/assets/sass/responsive.scss', 'public/css')
.sass('resources/assets/sass/owl.carousel.scss', 'public/css')
.sass('resources/assets/sass/stripe.scss', 'public/css')


.options({
	processCssUrls: false
});

// Images Compile Code
mix.copy('resources/assets/images/website_images', 'public/images')
.copy('resources/assets/fonts', 'public/fonts');

// Js Compile Code
mix.js('resources/assets/js/app.js', 'public/js');

	
	

   

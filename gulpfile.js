var elixir = require('laravel-elixir');

require('laravel-elixir-del');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.config.sourcemaps = false;

elixir(function(mix) {
	
	// Copy JS
	mix.copy('resources/assets/js', 'public/js');

	// Concat CSS
	mix.styles([
		'bootstrap.min.css',
		'bootstrap-extend.min.css',
		'site.css',
		'login-v3.css',
		'adjustments.css'
		], 'public/css/all.css');

	// Concat JS
	// mix.scripts([
	// 	"",
	// 	""
	// 	], "public/js/all.js");

	// Copy Images
	mix.copy('resources/assets/images', 'public/images');

	// Copy Fonts
	mix.copy('resources/assets/fonts', 'public/fonts');
	
	// Version & Del
	mix.version(['css/all.css', 'public/images', 'public/js/*']).del(['public/css', 'public/js', 'public/images']);
	
});
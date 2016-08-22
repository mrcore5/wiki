var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //mix.less('default.less');
    //mix.less('simplex.less');
    //mix.less('slate.less');

	mix.styles([
		"wiki.css",
		"markdown.css"
	], 'Assets/css/wiki-bundle.css', 'Assets/css');

	mix.scripts([
		"wiki.js"
	], 'Assets/js/wiki-bundle.js', 'Assets/js');

});

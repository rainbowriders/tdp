var elixir = require('laravel-elixir');

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

elixir(function(mix) {
    mix.sass('app.scss');
    mix.scripts(['flash-messages.js'], 'public/js/flash-messages.js')
        .scripts('contact-form-validator.js', 'public/js/contact-form-validator.js')
        .scripts('smooth-scroll.js', 'public/js/smooth-scroll.js');
});


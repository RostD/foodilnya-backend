const elixir = require('laravel-elixir');

require('laravel-elixir-vue');
require('elixir-typescript');


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
var dir_frontend = '../../views/frontend';
elixir(mix => {
    mix
    /** Хз что это
    .sass('app.scss')
    .webpack('app.js')*/

    /******* Подгрузка ядра Angular 2 в public *********
     .copy('node_modules/@angular', 'public/angular/@angular')
    .copy('node_modules/anular2-in-memory-web-api', 'public/anular2-in-memory-web-api')
     .copy('node_modules/core-js', 'public/angular/core-js')
     .copy('node_modules/reflect-metadata', 'public/angular/reflect-metadata')
     .copy('node_modules/systemjs', 'public/angular/systemjs')
     .copy('node_modules/rxjs', 'public/angular/rxjs')
     .copy('node_modules/zone.js', 'public/angular/zone.js')*/

/******* Control/Dishes *******/
    .typescript(
        [
            dir_frontend + '/control/dishes/main.ts',
            dir_frontend + '/control/dishes/app.module.ts',
            dir_frontend + '/control/dishes/app.component.ts',
            dir_frontend + '/control/dishes/table.component.ts',
            dir_frontend + '/control/dishes/modal.component.ts',
            dir_frontend + '/control/dishes/dish.service.ts',
            dir_frontend + '/control/dishes/dish.ts'
        ],
        'public/app/control/dishes',
        {
            "target": "es5",
            "module": "system",
            "moduleResolution": "node",
            "sourceMap": false,
            "emitDecoratorMetadata": true,
            "experimentalDecorators": true,
            "removeComments": false,
            "noImplicitAny": false
        }
    )
    /** Копируем html шаблоны и css файлы в public **/
    .copy('resources/views/frontend/control/dishes/view', 'public/app/control/dishes/view')
});
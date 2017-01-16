/**
 * System configuration for Angular samples
 * Adjust as necessary for your application needs.
 */
(function (global) {
    System.config({

        // map tells the System loader where to look for things
        map: {
            // our app is within the app folder
            app: _url('app'),
            // angular bundles
            '@angular/core': _url('angular/@angular/core/bundles/core.umd.js'),
            '@angular/common': _url('angular/@angular/common/bundles/common.umd.js'),
            '@angular/compiler': _url('angular/@angular/compiler/bundles/compiler.umd.js'),
            '@angular/platform-browser': _url('angular/@angular/platform-browser/bundles/platform-browser.umd.js'),
            '@angular/platform-browser-dynamic': _url('angular/@angular/platform-browser-dynamic/bundles/platform-browser-dynamic.umd.js'),
            '@angular/http': _url('angular/@angular/http/bundles/http.umd.js'),
            '@angular/router': _url('angular/@angular/router/bundles/router.umd.js'),
            '@angular/forms': _url('angular/@angular/forms/bundles/forms.umd.js'),
            // other libraries
            'rxjs': _url('angular/rxjs'),
            'angular2-in-memory-web-api': _url('angular2-in-memory-web-api'),
        },
        // packages tells the System loader how to load when no filename and/or no extension
        packages: {
            app: {
                main: '/control/meals/main.js',
                defaultExtension: 'js'
            },
            rxjs: {
                defaultExtension: 'js'
            },
            'angular2-in-memory-web-api': {
                main: './index.js',
                defaultExtension: 'js'
            }
        }
    });
})(this);
'use strict';

/* подключаем gulp и плагины */
var gulp = require('gulp'),  // подключаем Gulp
    plumber = require('gulp-plumber'), // модуль для отслеживания ошибок
    rigger = require('gulp-rigger'), // модуль для импорта содержимого одного файла в другой
    sourcemaps = require('gulp-sourcemaps'), // модуль для генерации карты исходных файлов
    sass = require('gulp-sass'), // модуль для компиляции SASS (SCSS) в CSS
    autoprefixer = require('gulp-autoprefixer'), // модуль для автоматической установки автопрефиксов
    cleanCSS = require('gulp-clean-css'); // плагин для минимизации CSS
var debug = require('gulp-debug');


var css_scripts = {
    'common/assets/common/src/scss/my_bootstrap.scss': 'common/assets/common/dists/css',
    'frontend/themes/main/assets/app/src/scss/main.scss': 'frontend/themes/main/assets/app/dists/css'
};

// сбор стилей common assets
gulp.task('css:build_css', function () {
    for (var i in css_scripts) {
        gulp.src(i) // получим .scss
            .pipe(plumber()) // для отслеживания ошибок
            .pipe(sourcemaps.init()) // инициализируем sourcemap
            .pipe(sass()) // scss -> css
            .pipe(autoprefixer())
            .pipe(cleanCSS()) // минимизируем CSS
            .pipe(sourcemaps.write('maps')) // записываем sourcemap
            .pipe(gulp.dest(css_scripts[i])); // выгружаем в папку назначения
    }
});

// сбор js
var uglifyjs = require('uglify-js');
var composer = require('gulp-uglify/composer');
var minify = composer(uglifyjs, console);

var scripts = {
    'vendor/twbs/bootstrap/dist/js/bootstrap.js': 'common/assets/common/dists/js',
    'node_modules/popper.js/dist/popper.min.js': 'common/assets/common/dists/js'
};

gulp.task('js:build_js', function () {
    var options = {};
    for (var i in scripts) {
        gulp.src(i)
            // .pipe(debug({title: i}))
            // .pipe(debug({title: scripts[i]}))
            .pipe(plumber()) // для отслеживания ошибок
            .pipe(rigger())
            // .pipe(sourcemaps.init())
            // .pipe(minify(options))
            // .pipe(sourcemaps.write())
            .pipe(gulp.dest(scripts[i]));
    }
});

// запуск задач при изменении файлов
gulp.task('watch', function() {
    gulp.watch('common/assets/common/src/scss/**/*.{sass,scss}', ['css:build_css']);
    gulp.watch('frontend/themes/main/assets/app/src/scss/**/*.{sass,scss}', ['css:build_css']);
    // gulp.watch('common/assets/src/common/js/src/**/*.js', ['js:build_js']);
});

gulp.task('default', [
    'watch'
]);

// gulp.task('autoprefix', function () {
//     return gulp.src(['css/**/*.css', '!css/vendor/**'])
//         .pipe(debug({title: 'src'}))
//         .pipe(autoprefixer({
//             browsers: ['last 2 versions'],
//             cascade: false
//         }))
//         .pipe(debug({title: 'autoprefixer'}))
//         .pipe(gulp.dest(function (file){
//             return 'css';
//         }))
// });

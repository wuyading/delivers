// 载入外挂
var gulp = require('gulp'),
    sass = require('gulp-ruby-sass'),
    autoprefixer = require('gulp-autoprefixer'),
    minifycss = require('gulp-clean-css'),
    jshint = require('gulp-jshint'),
    uglify = require('gulp-uglify'),
    imagemin = require('gulp-imagemin'),
    rename = require('gulp-rename'),
    clean = require('gulp-clean'),
    fileinclude = require('gulp-file-include'),
    order = require("gulp-order"),
    concat = require('gulp-concat'),
    notify = require('gulp-notify'),
    cache = require('gulp-cache');

var browserSync = require('browser-sync').create();
var SRC_DIR = './src/';
var DST_DIR = './public/static/';

// 样式
gulp.task('styles', function() {
  return sass([SRC_DIR + '**/*.scss'],{noCache: true, compass: true , sourcemap: true})
      .pipe(autoprefixer('ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'))
      .pipe(gulp.dest(DST_DIR))
      .pipe(rename({ suffix: '.min' }))
      .pipe(minifycss())
      .pipe(gulp.dest(DST_DIR))
      .pipe(browserSync.stream())
      .pipe(notify({ message: 'Styles task complete' }));
});

// 脚本
gulp.task('scripts', function() {
  return gulp.src([SRC_DIR + '**/*.js'])
      /*.pipe(order([
        "lib/jquery-2.0.3.min.js",
        "lib/!*.js",
        "js/!*.js"
      ]))*/
      .pipe(jshint('.jshintrc'))
      .pipe(jshint.reporter('default'))
      //.pipe(concat('js/main.js'))
      .pipe(gulp.dest(DST_DIR))
      .pipe(rename({ suffix: '.min' }))
      .pipe(uglify())
      .pipe(gulp.dest(DST_DIR))
      .pipe(browserSync.stream())
      .pipe(notify({ message: 'Scripts task complete' }));
});

// 脚本
gulp.task('scripts_backend', function() {
  return gulp.src([SRC_DIR + 'admin/**/*.js'])
      /*.pipe(order([
        "lib/jquery-2.0.3.min.js",
        "lib/!*.js",
        "js/!*.js"
      ]))*/
      .pipe(jshint('.jshintrc'))
      .pipe(jshint.reporter('default'))
      // .pipe(concat('admin/js/main.js'))
      .pipe(gulp.dest(DST_DIR))
      .pipe(rename({ suffix: '.min' }))
      .pipe(uglify())
      .pipe(gulp.dest(DST_DIR))
      .pipe(browserSync.stream())
      .pipe(notify({ message: 'scripts_backend task complete' }));
});

// 图片
gulp.task('images', function() {
  return gulp.src(SRC_DIR + 'images/**/*')
      .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
      .pipe(gulp.dest(DST_DIR+'/images'))
      .pipe(browserSync.stream())
      .pipe(notify({ message: 'Images task complete' }));
});

// HTML处理
gulp.task('html', function() {
    return gulp.src(SRC_DIR + '**/*.html' )
        .pipe(fileinclude())
        .pipe(gulp.dest(DST_DIR))
        // .pipe(browserSync.stream())
        .pipe(notify({ message: 'html task complete' }));
});

// 清理
gulp.task('clean', function() {
  return gulp.src([DST_DIR], {read: false})
      .pipe(clean());
});

// 预设任务
gulp.task('default', ['clean'], function() {
    gulp.start('styles', 'scripts', 'scripts_backend', 'images', 'html');
});

gulp.task('watch',['styles','scripts','scripts_backend','html'],function() {

    browserSync.init({
        files:['**'],
        server:{
            baseDir:DST_DIR,  // 设置服务器的根目录
            index:'web/index.html' // 指定默认打开的文件
        }
    });

  // 看守所有.scss档
  gulp.watch(SRC_DIR + '**/*.scss', ['styles']);

  // 看守所有.js档
  gulp.watch(SRC_DIR + '**/*.js', ['scripts','scripts_backend']);

  // 看守所有图片档
  gulp.watch(SRC_DIR + 'images/**/*', ['images']);

  //看守html
  gulp.watch(SRC_DIR + '**/*.html',['html']).on('change', browserSync.reload);

});

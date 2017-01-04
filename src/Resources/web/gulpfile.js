// Requis
var gulp = require('gulp');

//Include (plugins)
var del = require('del');
// Variables de chemins
var source = './src'; // dossier de travail
var destination = '../public'; // dossier à livrer

/*******
TINY MCE
*******/
gulp.task('TinyMCE.core', function () {
  return gulp.src('node_modules/tinymce/tinymce.min.js')
    .pipe(gulp.dest(destination+'/tinymce'));
});

gulp.task('TinyMCE.skins', function () {
  return gulp.src('node_modules/tinymce/skins/**')
    .pipe(gulp.dest(destination+'/tinymce/skins'));
});

gulp.task('TinyMCE.themes', function () {
  return gulp.src('node_modules/tinymce/themes/**')
    .pipe(gulp.dest(destination+'/tinymce/themes'));
});

gulp.task('TinyMCE.plugins', function () {
  return gulp.src('node_modules/tinymce/plugins/**')
    .pipe(gulp.dest(destination+'/tinymce/plugins'));
});

/*******
APP
*******/
gulp.task('scripts', function () {
  return gulp.src('src/**/**')
    .pipe(gulp.dest(destination+'/'));
});

gulp.task('default', ['clean'], function() {
   gulp.start('TinyMCE.core');
   gulp.start('TinyMCE.skins');
   gulp.start('TinyMCE.themes');
   gulp.start('TinyMCE.plugins');

   gulp.start('scripts');
});

gulp.task('clean', function() {
   return del([destination], {force: true});
});

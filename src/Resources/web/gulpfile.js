// Requis
var gulp = require('gulp');

//Include (plugins)
var del = require('del');
// Variables de chemins
var source = './src'; // dossier de travail
var destination = '../../../../../web/dist/BackBundle'; // dossier à livrer

/********
ADMIN LTE
********/
gulp.task('AdminLTE.bootstrap', function () {
  return gulp.src('bower_components/AdminLTE/bootstrap/**')
    /* ici les plugins Gulp à exécuter */
    .pipe(gulp.dest(destination+'/AdminLTE/bootstrap'));
});

gulp.task('AdminLTE.dist', function () {
  return gulp.src('bower_components/AdminLTE/dist/**')
    /* ici les plugins Gulp à exécuter */
    .pipe(gulp.dest(destination+'/AdminLTE/dist'));
});

gulp.task('AdminLTE.plugins', function () {
  return gulp.src('bower_components/AdminLTE/plugins/**')
    /* ici les plugins Gulp à exécuter */
    .pipe(gulp.dest(destination+'/AdminLTE/plugins'));
});

/*******
TINY MCE
*******/
gulp.task('TinyMCE.core', function () {
  return gulp.src('bower_components/tinymce/tinymce.min.js')
    .pipe(gulp.dest(destination+'/tinymce'));
});

gulp.task('TinyMCE.skins', function () {
  return gulp.src('bower_components/tinymce/skins/**')
    .pipe(gulp.dest(destination+'/tinymce/skins'));
});

gulp.task('TinyMCE.themes', function () {
  return gulp.src('bower_components/tinymce/themes/**')
    .pipe(gulp.dest(destination+'/tinymce/themes'));
});

gulp.task('TinyMCE.plugins', function () {
  return gulp.src('bower_components/tinymce/plugins/**')
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

   gulp.start('AdminLTE.bootstrap');
   gulp.start('AdminLTE.dist');
   gulp.start('AdminLTE.plugins');

   gulp.start('scripts');
});

gulp.task('clean', function() {
   return del([destination], {force: true});
});

/*

REQUIREMENTS:

npm install gulp
npm install gulp-concat gulp-uglify gulp-notify gulp-util gulp-minify-css gulp-connect gulp-autoprefixer --save-dev

OR (lazy)
(1 time) npm install -g gulp gulp-concat gulp-uglify gulp-notify gulp-util gulp-minify-css gulp-connect gulp-autoprefixer
npm link gulp gulp-concat gulp-uglify gulp-notify gulp-util gulp-minify-css gulp-connect gulp-autoprefixer

CD TO PROJECT FOLDER AND RUN WITH:
gulp

*/


var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var notify = require('gulp-notify');
var gutil = require('gulp-util');
var minifyCSS = require('gulp-minify-css');
var connect = require('gulp-connect');
var autoprefixer = require('gulp-autoprefixer');

var serverPort = 8088;

//var jshint = require('gulp-jshint');
//var hexuglify = require('gulp-hexuglify');

//gulp.task('json', function () {
//    return gulp.src([
//          "../www/mycircle-data/**/*.json",
//        ]) //  if orderd not important, 'js/**/*.js' to select all javascript files in js/ and subdirectory
//        .pipe(gulp.dest('../www/build')) // the destination folder
//});

gulp.task('js', function () {
    return gulp.src([
          // "js/zepto.js",
          //"js/furnax.js",
          //"js/plugins.js",
          // "js/moment.js",
          // "js/load-image.js",
          // "js/nouislider.js",
          // "js/notification.js",
          // "js/swiper.min.js",
          // "js/Chart.min.js",


        "js/app.js",

        ]) //  if orderd not important, 'js/**/*.js' to select all javascript files in js/ and subdirectory
        //.pipe(jshint())
        //.pipe(jshint.reporter('default'))
        .pipe(concat('app.min.js')) // the name of the resulting file
        .pipe(gulp.dest('../public/build')) // the destination folder
        .pipe(uglify()).on('error', function(err){
          console.log("=== ERROR UGLIFYJS ===");
          console.log(err.message);
          console.log("------");
          console.log("subl://open?url=file://"+err.fileName+"&line="+err.lineNumber+"&column=1");
          console.log("======");
          notify().write({
              message: '😡 😡 😡 Error minifying Javascript !!'
          });
          console.log("\x07");
          console.log("\x07");
          console.log("\x07");
          this.emit("end");
          })
        .pipe(gulp.dest('../public/build')) // the destination folder
        .pipe(notify({ message: '😍 😍 😍 Finished minifying JavaScript'}))
        .pipe(connect.reload());
});

gulp.task('css', function () {
    return gulp.src([
           //"css/normalize.css",
           //"css/furnax.css",
           //"css/plugins.css",
           //"css/furnicon.css",
           //"css/swiper.min.css",
           //"css/lity.min.css",
           "css/app.css"
        ])
        .pipe(concat('app.min.css')) // the name of the resulting file
        .pipe(autoprefixer({
            browsers: ['last 8 versions'],
            cascade: true
        }))
        .pipe(minifyCSS())
        .pipe(gulp.dest('../public/build')) // the destination folder
        .pipe(notify({ message: '😘 😘 😘 Finished minifying CSS'}))
        .pipe(connect.reload());
});

gulp.task('html', function () {
  gulp.src('../public/*.html')
    .pipe(connect.reload());
});

gulp.task('webserver', function() {
  connect.server({
    port: serverPort,
    livereload: true,
    root: "../public/"
  })        
});


gulp.task('watch', function() {
    gulp.watch('../public/index.html', ['html']);
    gulp.watch('js/**/*.js', ['js']);
    gulp.watch('css/**/*.css', ['css']);
    //gulp.watch('../www/**/*.json', ['json']);
});


gulp.task('default', [/*'json', */'js', 'css', 'html', 'watch', 'webserver']);


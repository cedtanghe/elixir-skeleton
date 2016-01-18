var gulp = require('gulp');
var jshint = require('gulp-jshint');
var jshintStylish = require('jshint-stylish');
 
// Lint
gulp.task('lint', function() 
{
    gulp.src(['medias/**/scripts/**/*.js', '!medias/vendor/*'])
    .pipe(jshint('.jshintrc'))
    .pipe(jshint.reporter('jshint-stylish'));
});
 
// Launch all
gulp.task('default', ['lint'], function(){});

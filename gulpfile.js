var gulp = require('gulp'),
    less = require('gulp-less'),
    minify = require('gulp-clean-css'),
    rename = require('gulp-rename');

var path = './assets/';

gulp.task('less', function () {
    return gulp.src(path + 'vpaginator.less')
        .pipe(rename({suffix: '.min'}))
        .pipe(less())
        .pipe(minify({keepSpecialComments: 0}))
        .pipe(gulp.dest(path));
});

gulp.task('watch', function () {
    gulp.watch(path + 'vpaginator.less', ['less']);
});

gulp.task('default', ['less', 'watch']);
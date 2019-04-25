var gulp = require('gulp');
var sass = require('gulp-sass');

gulp.task('sass',function () {
    return gulp.src('resources/sass/**/*.scss')
            .pipe(sass().on('error',sass.logError))
            .pipe(gulp.dest('public/css/'));
});
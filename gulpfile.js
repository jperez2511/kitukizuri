const gulp = require('gulp');
const npmdist = require('gulp-npm-dist');
const rename = require('gulp-rename');

gulp.task('copy:libs', function() {
    return gulp
      .src(npmdist(), { base:'./node_modules'})
      .pipe(rename(function(path) {
          path.dirname = path.dirname.replace(/\/dist/, '').replace(/\\dist/, '');
      }))
      .pipe(gulp.dest('./src/public/kitukizuri/libs'));
  });

  gulp.task('build', gulp.series(gulp.parallel('copy:libs')));
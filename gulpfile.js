/**
Mostly derived from https://bitsofco.de/a-simple-gulp-workflow
npm install gulp
npm install --save-dev gulp-sass
npm install --save-dev gulp-concat
npm install --save-dev gulp-uglify
npm install --save-dev gulp-util
npm install --save-dev gulp-rename
npm install --save-dev gulp-babel
npm install --save-dev gulp-zip
npm install --save-dev del
 */

// First require gulp.
var gulp   = require( 'gulp' ),
	sass   = require( 'gulp-sass' ),
	concat = require( 'gulp-concat' ),
	uglify = require( 'gulp-uglify' ),
	gutil  = require( 'gulp-util' ),
	rename = require( 'gulp-rename' ),
	zip    = require( 'gulp-zip' ),
	del    = require( 'del' ),
	babel  = require('gulp-babel');

var sassFrontendSource        = [ 'dev/scss/gamedev-main-frontend.scss' ];
var sassBackendSource         = [ 'dev/scss/gamedev-main-admin.scss' ];
var jsBackendSource           = [ 'dev/js/backend/*.js' ];
var jsFrontendSource          = [ 'dev/js/frontend/*.js' ];
var watcherMainFrontEndScss = gulp.watch( sassFrontendSource );
var watcherMainBackEndScss = gulp.watch( sassBackendSource );
var watcherJsFrontendSource = gulp.watch( jsFrontendSource );
var watcherJsFrontendSourceBabelize = gulp.watch( 'dev/js/frontend/es6/gamedev_frontend_es6.js' );
var watcherJsBackendSource = gulp.watch( jsBackendSource );

// Define default task.
gulp.task( 'default', function( done ) {
	return done();
});

gulp.task('babelize', () =>
    gulp.src('dev/js/frontend/es6/gamedev_frontend_es6.js')
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(concat( 'gamedev_frontend.min.js' ) ) // Concat to a file named 'script.js'
        .pipe(gulp.dest('dev/js/frontend'))
);

// Task to compile Frontend SASS file.
gulp.task( 'sassFrontendSource', function() {
	return gulp.src( sassFrontendSource )
		.pipe(sass({
			outputStyle: 'compressed'
		})
			.on( 'error', gutil.log ) )
		.pipe(gulp.dest( 'assets/css' ) )
});

// Task to compile Backend SASS file
gulp.task( 'sassBackendSource', function() {
	return gulp.src( sassBackendSource )
		.pipe(sass({
			outputStyle: 'compressed'
		})
			.on( 'error', gutil.log) )
		.pipe(gulp.dest( 'assets/css' ) );
});

// Task to concatenate and uglify js files
gulp.task( 'concatAdminJs', function() {
	return gulp.src(jsBackendSource ) // use jsSources
		.pipe(concat( 'gamedev_admin.min.js' ) ) // Concat to a file named 'script.js'
		.pipe(uglify() ) // Uglify concatenated file
		.pipe(gulp.dest( 'assets/js' ) ); // The destination for the concatenated and uglified file
});


// Task to concatenate and uglify js files
gulp.task( 'concatFrontendJs', function() {
	return gulp.src(jsFrontendSource ) // use jsSources
		.pipe(concat( 'gamedev_frontend.min.js' ) ) // Concat to a file named 'script.js'
		.pipe(uglify() ) // Uglify concatenated file
		.pipe(gulp.dest( 'assets/js' ) ); // The destination for the concatenated and uglified file
});

gulp.task( 'copyassets', function () {
	return gulp.src([ './assets/**/*' ], {base: './'}).pipe(gulp.dest( '../gamedev_dist/GameDev-Distribution' ) );
});

gulp.task( 'copyincludes', function () {
	return gulp.src([ './includes/**/*' ], {base: './'}).pipe(gulp.dest( '../gamedev_dist/GameDev-Distribution' ) );
});

gulp.task( 'copyquotes', function () {
	return gulp.src([ './quotes/**/*' ], {base: './'}).pipe(gulp.dest( '../gamedev_dist/GameDev-Distribution' ) );
});

gulp.task( 'copymainfile', function () {
	return gulp.src([ './gamedev.php' ], {base: './'}).pipe(gulp.dest( '../gamedev_dist/GameDev-Distribution' ) );
});

gulp.task( 'zip', function () {
	return gulp.src( '../gamedev_dist/GameDev-Distribution/**' )
		.pipe(zip( 'gamedev.zip' ) )
		.pipe(gulp.dest( '../gamedev_dist/GameDev-Distribution' ) );
});

gulp.task( 'cleanzip', function(cb) {
	return del([ '../gamedev_dist/GameDev-Distribution/**/*' ], {force: true}, cb);
});

gulp.task( 'clean', function(cb) {
	return del([ '../gamedev_dist/GameDev-Distribution/**/*', '!../gamedev_dist/GameDev-Distribution/gamedev.zip' ], {force: true}, cb);
});

// Cleanup/Zip/Deploy task
gulp.task('default', gulp.series( 'babelize', 'cleanzip', 'sassFrontendSource', 'sassBackendSource', 'concatAdminJs', 'concatFrontendJs', gulp.parallel('copyassets','copyincludes','copyquotes','copymainfile'),'zip','clean',function(done) {done();}));

/*
 *	WATCH TASKS FOR SCSS/CSS
 *
*/
watcherMainFrontEndScss.on('all', function(event, path, stats) {

	gulp.src( sassFrontendSource )
		.pipe(sass({
			outputStyle: 'compressed'
		})
			.on( 'error', gutil.log ) )
		.pipe(gulp.dest( 'assets/css' ) )
		.on('end', function(){ console.log('Finished!!!') });

  console.log('File ' + path + ' was ' + event + 'd, running tasks...');
});
watcherMainBackEndScss.on('all', function(event, path, stats) {

	gulp.src( sassBackendSource )
		.pipe(sass({
			outputStyle: 'compressed'
		})
			.on( 'error', gutil.log) )
		.pipe(gulp.dest( 'assets/css' ) )
		.on('end', function(){ console.log('Finished!!!') });

  console.log('File ' + path + ' was ' + event + 'd, running tasks...');
});
watcherJsBackendSource.on('all', function(event, path, stats) {

	gulp.src( jsBackendSource ) // use jsSources
		.pipe(concat( 'gamedev_admin.min.js' ) ) // Concat to a file named 'script.js'
		.pipe(uglify() ) // Uglify concatenated file
		.pipe(gulp.dest( 'assets/js' ) )
		.on('end', function(){ console.log('Finished!!!') });


  console.log('File ' + path + ' was ' + event + 'd, running tasks...');
});

watcherJsFrontendSourceBabelize.on('all', function(event, path, stats) {

	 gulp.src('dev/js/frontend/es6/gamedev_frontend_es6.js')
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(concat( 'gamedev_frontend.min.js' ) ) // Concat to a file named 'script.js'
        .pipe(gulp.dest('dev/js/frontend'))
        .on('end', function(){ console.log('Finished Babelizing, now minifying/concatenating...'); gulp.src(jsFrontendSource ) // use jsSources
		.pipe(concat( 'gamedev_frontend.min.js' ) ) // Concat to a file named 'script.js'
		.pipe(uglify() ) // Uglify concatenated file
		.pipe(gulp.dest( 'assets/js' ) ) // The destination for the concatenated and uglified file
		.on('end', function(){ console.log('Finished!!!') }); });

  console.log('File ' + path + ' was ' + event + 'd, Babelizing...');
});



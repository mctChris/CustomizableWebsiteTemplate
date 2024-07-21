var gulp = require('gulp'),
rename = require('gulp-rename'),
watch = require('gulp-watch'),
replace = require('gulp-replace'),
fs = require('fs'),
pathExists = require('path-exists');


/**
	0: Only 1 language, no description table. e.g. Contact form
	1: Page
	2: Records
	3: Records with Category
	4: Records with Mutli Category (to do)
**/

var classObj = {
	0: [
		'language_page'
	],
	1 : [
	],
	2 : [
	],
	3 : [
	],
}
var sqlDestPath = 'build/mysql.sql';

var routeDestPath = 'build/routes.php';

gulp.task('default', async function() {
	initSqlFile();
	initRouteFile();
	for (var type in classObj){
    	classObj[type] = classObj[type].filter(function(item, pos) {
			return classObj[type].indexOf(item) == pos;
		})
		for(var i = 0; i < classObj[type].length; i++){
			var className = classObj[type][i];
			await createPhpFile(className, type);
			createSqlFile(className, type);
			createRouteFile(className, type);
		}
	}
});

async function createPhpFile(className, type){
	
	let watchStream = await watch('template/**/**/*' + type + '*.php', { ignoreInitial: false }, async function(obj) {
	  	var path = obj.path.replace(obj.base, "");
	  	var fileName = path.match(/([^\/]*)\/*$/)[1];
		var destPath = path.replace(fileName, '').replace(/^\//, '');
	  	var newFileName = fileName.replace(type, toClassName(className));

	  	var fileExists = await pathExists(destPath + newFileName);
	  	if(!fileExists){

			await gulp.src(obj.path)
			  .pipe(replace('{controller}', toClassName(className)))
			  .pipe(replace('{class}', className))
			  .pipe(replace('{page_title}', className.replace('_', ' ').ucwords()))
		      .pipe(rename(newFileName))
		      .pipe(gulp.dest(destPath));
		 }
	});
	

	let watchStream2 = await watch('template/**/**/' + type + '*/**', { ignoreInitial: false }, async function(obj) {

		var path = obj.path.replace(obj.base, "");
	  	var fileName = path.match(/([^\/]*)\/*$/)[1];
		var destPath = path.replace(fileName, '').replace('/' + type, '/' + className).replace(/^\//, '');

	  	var fileExists = await pathExists(destPath + fileName);
		if(!fileExists){
			await gulp.src(obj.path)
		      .pipe(gulp.dest(destPath));
		}
	});

	setTimeout(function(){
		watchStream.close()
		watchStream2.close()
	}, 3000)
}


function createRouteFile(className, type){
	var route = '';
	if(type == 3 || type == 4){
	route = 
`		Route::group(['prefix' => '${className}_category'], function () {
	        Route::get('listing/{parent_id?}', 'Admin\\${toClassName(className)}CategoryController@listing')->name('admin.${className}_category.listing');
	        Route::get('detail/{id?}/parent_id/{parent_id?}', 'Admin\\${toClassName(className)}CategoryController@detail')->name('admin.${className}_category.detail');
	        Route::get('arrangement/{parent_id?}', 'Admin\\${toClassName(className)}CategoryController@arrangement')->name('admin.${className}_category.arrangement');
	        Route::post('save', 'Admin\\${toClassName(className)}CategoryController@save')->name('admin.${className}_category.save');
	        Route::post('delete', 'Admin\\${toClassName(className)}CategoryController@delete')->name('admin.${className}_category.delete');
	        Route::post('save_arrangement', 'Admin\\${toClassName(className)}CategoryController@save_arrangement')->name('admin.${className}_category.save_arrangement');
	        Route::post('bulk_action', 'Admin\\${toClassName(className)}CategoryController@bulk_action')->name('admin.${className}_category.bulk_action');
	    });

        Route::group(['prefix' => '${className}'], function () {
            Route::get('listing/{parent_id?}', 'Admin\\${toClassName(className)}Controller@listing')->name('admin.${className}.listing');
            Route::get('detail/{id?}/parent_id/{parent_id?}', 'Admin\\${toClassName(className)}Controller@detail')->name('admin.${className}.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\\${toClassName(className)}Controller@arrangement')->name('admin.${className}.arrangement');
            Route::post('save', 'Admin\\${toClassName(className)}Controller@save')->name('admin.${className}.save');
            Route::post('delete', 'Admin\\${toClassName(className)}Controller@delete')->name('admin.${className}.delete');
            Route::post('save_arrangement', 'Admin\\${toClassName(className)}Controller@save_arrangement')->name('admin.${className}.save_arrangement');
            Route::post('bulk_action', 'Admin\\${toClassName(className)}Controller@bulk_action')->name('admin.${className}.bulk_action');
        });

`;
	}else{
	route = 
`		Route::group(['prefix' => '${className}'], function () {
	        Route::get('listing', 'Admin\\${toClassName(className)}Controller@listing')->name('admin.${className}.listing');
	        Route::get('detail/{id?}', 'Admin\\${toClassName(className)}Controller@detail')->name('admin.${className}.detail');
	        Route::get('arrangement/{parent_id?}', 'Admin\\${toClassName(className)}Controller@arrangement')->name('admin.${className}.arrangement');
	        Route::post('save', 'Admin\\${toClassName(className)}Controller@save')->name('admin.${className}.save');
	        Route::post('delete', 'Admin\\${toClassName(className)}Controller@delete')->name('admin.${className}.delete');
	        Route::post('save_arrangement', 'Admin\\${toClassName(className)}Controller@save_arrangement')->name('admin.${className}.save_arrangement');
            Route::post('bulk_action', 'Admin\\${toClassName(className)}Controller@bulk_action')->name('admin.${className}.bulk_action');
	    });

`;
	}
	fs.appendFileSync(routeDestPath, route);
}
function initRouteFile(){
	fs.writeFileSync(routeDestPath, "");
}
function initSqlFile() {
	var sql = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";" +
		"SET time_zone = \"+00:00\";"
	fs.writeFileSync(sqlDestPath, sql);
}

function createSqlFile(className, type){
	appendToSql(className, type);
	if(type == 3 || type == 4){
		appendToSql(className + '_category', type);
	}
}
function appendToSql(className, type){
	var sql = "";
	var parent_id = "";
	var type_sql = "";
	var main_sql = "";
	var isCat = (type == 3 && className.match('_category')) || (type == 4 && className.match('_category'));
	var isCatChild = (type == 3 && !className.match('_category')) || (type == 4 && !className.match('_category'));
	var withDesc;
	if(isCatChild){
		parent_id = "`parent_id` int(11) DEFAULT 0,";
	}
	if(isCat){
		parent_id = "`parent_id` int(11) DEFAULT 0,`has_child_category` tinyint(4) DEFAULT 0,";
	}
	if(type != 0){
		withDesc = true
	}else{
		withDesc = false;
		main_sql = "`title` varchar(191) DEFAULT NULL,";
	}

	if(isCat){
		type_sql = "  `type` enum('category','product') NOT NULL DEFAULT 'category',";
	}
	sql += "CREATE TABLE IF NOT EXISTS `{table}` (" +
		"  `id` int(11) UNSIGNED NOT NULL," +
		"	{parent_id}" +
		main_sql +
		type_sql +
		"  `url` text DEFAULT NULL," +
		"  `url_slug` text DEFAULT NULL," +
		"  `arrange` int(11) DEFAULT NULL," +
		"  `is_show` tinyint(1) NOT NULL DEFAULT '1'," +
		"  `publish_at` timestamp NULL DEFAULT NULL," +
		"  `offline_at` timestamp NULL DEFAULT NULL," +
		"  `created_by` int(11) DEFAULT NULL," +
		"  `updated_by` int(11) DEFAULT NULL," +
		"  `created_at` timestamp NULL DEFAULT NULL," +
		"  `updated_at` timestamp NULL DEFAULT NULL" +
		") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;" +
		"ALTER TABLE `{table}`" +
		"  ADD PRIMARY KEY (`id`);" +
		"ALTER TABLE `{table}`" +
		"  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
	if(withDesc){
		sql += "CREATE TABLE IF NOT EXISTS `{table}_description` (" +
			"  `description_id` int(11) NOT NULL," +
			"  `language_id` int(11) NOT NULL," +
			"  `title` varchar(191) DEFAULT NULL," +
			"  `description` text" +
			") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;" +
			"ALTER TABLE `{table}_description` ADD PRIMARY KEY (`description_id`,`language_id`);";
	}
	// if(isCat){
	// 	sql += "CREATE TABLE IF NOT EXISTS `{table}_relation` (" +
	// 	"  `product_id` int(11) NOT NULL," +
	// 	"  `product_category_id` int(11) NOT NULL" +
	// 	") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
	// }
	sql = sql.replace(/\{table\}/g, className);
	sql = sql.replace(/\{parent_id\}/g, parent_id);

	fs.appendFileSync(sqlDestPath, sql);
}
function toClassName(string) {
    return string.replace(/_/g, ' ').ucwords().replace(/\s/g, '');
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function toTitleCase(str){
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
String.prototype.ucwords = function() {
    str = this.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
        function($1){
            return $1.toUpperCase();
        });
}
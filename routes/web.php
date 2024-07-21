<?php
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Dynamic route determined by page title
$aboutStoryRoute = slug(\App\AboutUsPage::withDescription()->pluck('page_title')->first() ?? 'story', '');

// dd($aboutStoryRoute, config('app.locale'));

$aboutValuesRoute = slug(\App\AboutValuesPage::withDescription()->pluck('page_title')->first() ?? 'values', '');
$aboutProcessRoute = slug(\App\AboutProcessPage::withDescription()->pluck('page_title')->first() ?? 'process', '');
$productsRoute = slug(\App\ProductsPage::withDescription()->pluck('page_title')->first() ?? 'products', '');
$newsRoute = slug(\App\NewsPage::withDescription()->pluck('page_title')->first() ?? 'news', '');
$certificateRoute = slug(\App\CertificatePage::withDescription()->pluck('page_title')->first() ?? 'certificates', '');
$contactRoute = slug(\App\ContactPage::withDescription()->pluck('page_title')->first() ?? 'contacts', '');


Route::get('refresh-csrf', function() {
    return csrf_token();
})->name('refresh-csrf');

\App\Language::indexRoute();

// Route::get('/', 'IndexController@index')->name('index');

Route::get($aboutStoryRoute, 'AboutUsController@storyPage')->name('about.story');

Route::get($aboutValuesRoute, 'AboutUsController@valuePage')->name('about.values');

Route::get($aboutProcessRoute, 'AboutUsController@processPage')->name('about.process');

Route::get($productsRoute, 'ProductsController@categoryListing')->name('products');

Route::get($productsRoute . '/{category}', 'ProductsController@singleCategory')->name('products.singleCategory');

Route::get($productsRoute . '/{category}/{product_url_slug}', 'ProductsController@productDetail')->name('products.detail');

Route::get($newsRoute, 'NewsController@index')->name('news');

Route::get($newsRoute . '/{news_url_slug}', 'NewsController@detail')->name('news.detail');

Route::get($certificateRoute, 'CertificatesController@index')->name('certificates');

Route::get($contactRoute, 'ContactController@index')->name('contact');


<?php
use Illuminate\Routing\Router;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();

Route::group(['prefix' => config('appcustom.admin_path')], function () {

    Route::get('/', function(){
        return redirect(route('admin.home.detail'));
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('user', 'Admin\UserController@index')->name('admin.user');
        Route::get('home', 'Admin\HomeController@index')->name('admin.home.detail');
        Route::get('example', 'Admin\ExampleController@index')->name('admin.example.index');

        Route::group(['prefix' => 'home_page'], function () {
            Route::get('listing', 'Admin\HomePageController@listing')->name('admin.home_page.listing');
            Route::get('detail/{id?}', 'Admin\HomePageController@detail')->name('admin.home_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\HomePageController@arrangement')->name('admin.home_page.arrangement');
            Route::post('save', 'Admin\HomePageController@save')->name('admin.home_page.save');
            Route::post('delete', 'Admin\HomePageController@delete')->name('admin.home_page.delete');
            Route::post('save_arrangement', 'Admin\HomePageController@save_arrangement')->name('admin.home_page.save_arrangement');
            Route::post('bulk_action', 'Admin\HomePageController@bulkAction')->name('admin.home_page.bulk_action');
        });

        Route::group(['prefix' => 'home_banner'], function () {
            Route::get('listing', 'Admin\HomeBannerController@listing')->name('admin.home_banner.listing');
            Route::get('detail/{id?}', 'Admin\HomeBannerController@detail')->name('admin.home_banner.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\HomeBannerController@arrangement')->name('admin.home_banner.arrangement');
            Route::post('save', 'Admin\HomeBannerController@save')->name('admin.home_banner.save');
            Route::post('delete', 'Admin\HomeBannerController@delete')->name('admin.home_banner.delete');
            Route::post('save_arrangement', 'Admin\HomeBannerController@save_arrangement')->name('admin.home_banner.save_arrangement');
            Route::post('bulk_action', 'Admin\HomeBannerController@bulkAction')->name('admin.home_banner.bulk_action');
        });

        Route::group(['prefix' => 'about_us_page'], function () {
            Route::get('listing', 'Admin\AboutUsPageController@listing')->name('admin.about_us_page.listing');
            Route::get('detail/{id?}', 'Admin\AboutUsPageController@detail')->name('admin.about_us_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\AboutUsPageController@arrangement')->name('admin.about_us_page.arrangement');
            Route::post('save', 'Admin\AboutUsPageController@save')->name('admin.about_us_page.save');
            Route::post('delete', 'Admin\AboutUsPageController@delete')->name('admin.about_us_page.delete');
            Route::post('save_arrangement', 'Admin\AboutUsPageController@save_arrangement')->name('admin.about_us_page.save_arrangement');
            Route::post('bulk_action', 'Admin\AboutUsPageController@bulk_action')->name('admin.about_us_page.bulk_action');
        });

        Route::group(['prefix' => 'about_values_page'], function () {
            Route::get('listing', 'Admin\AboutValuesPageController@listing')->name('admin.about_values_page.listing');
            Route::get('detail/{id?}', 'Admin\AboutValuesPageController@detail')->name('admin.about_values_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\AboutValuesPageController@arrangement')->name('admin.about_values_page.arrangement');
            Route::post('save', 'Admin\AboutValuesPageController@save')->name('admin.about_values_page.save');
            Route::post('delete', 'Admin\AboutValuesPageController@delete')->name('admin.about_values_page.delete');
            Route::post('save_arrangement', 'Admin\AboutValuesPageController@save_arrangement')->name('admin.about_values_page.save_arrangement');
            Route::post('bulk_action', 'Admin\AboutValuesPageController@bulk_action')->name('admin.about_values_page.bulk_action');
        });

        Route::group(['prefix' => 'about_process_page'], function () {
            Route::get('listing', 'Admin\AboutProcessPageController@listing')->name('admin.about_process_page.listing');
            Route::get('detail/{id?}', 'Admin\AboutProcessPageController@detail')->name('admin.about_process_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\AboutProcessPageController@arrangement')->name('admin.about_process_page.arrangement');
            Route::post('save', 'Admin\AboutProcessPageController@save')->name('admin.about_process_page.save');
            Route::post('delete', 'Admin\AboutProcessPageController@delete')->name('admin.about_process_page.delete');
            Route::post('save_arrangement', 'Admin\AboutProcessPageController@save_arrangement')->name('admin.about_process_page.save_arrangement');
            Route::post('bulk_action', 'Admin\AboutProcessPageController@bulk_action')->name('admin.about_process_page.bulk_action');
        });

        Route::group(['prefix' => 'about_process'], function () {
            Route::get('listing', 'Admin\AboutProcessController@listing')->name('admin.about_process.listing');
            Route::get('detail/{id?}', 'Admin\AboutProcessController@detail')->name('admin.about_process.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\AboutProcessController@arrangement')->name('admin.about_process.arrangement');
            Route::post('save', 'Admin\AboutProcessController@save')->name('admin.about_process.save');
            Route::post('delete', 'Admin\AboutProcessController@delete')->name('admin.about_process.delete');
            Route::post('save_arrangement', 'Admin\AboutProcessController@save_arrangement')->name('admin.about_process.save_arrangement');
            Route::post('bulk_action', 'Admin\AboutProcessController@bulk_action')->name('admin.about_process.bulk_action');
        });        

        Route::group(['prefix' => 'products_category'], function () {
            Route::get('listing/{parent_id?}', 'Admin\ProductsCategoryController@listing')->name('admin.products_category.listing');
            Route::get('detail/{id?}/parent_id/{parent_id?}', 'Admin\ProductsCategoryController@detail')->name('admin.products_category.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\ProductsCategoryController@arrangement')->name('admin.products_category.arrangement');
            Route::post('save', 'Admin\ProductsCategoryController@save')->name('admin.products_category.save');
            Route::post('delete', 'Admin\ProductsCategoryController@delete')->name('admin.products_category.delete');
            Route::post('save_arrangement', 'Admin\ProductsCategoryController@save_arrangement')->name('admin.products_category.save_arrangement');
            Route::post('bulk_action', 'Admin\ProductsCategoryController@bulk_action')->name('admin.products_category.bulk_action');
        });

        Route::group(['prefix' => 'products'], function () {
            Route::get('listing/{parent_id?}', 'Admin\ProductsController@listing')->name('admin.products.listing');
            Route::get('detail/{id?}/parent_id/{parent_id?}', 'Admin\ProductsController@detail')->name('admin.products.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\ProductsController@arrangement')->name('admin.products.arrangement');
            Route::post('save', 'Admin\ProductsController@save')->name('admin.products.save');
            Route::post('delete', 'Admin\ProductsController@delete')->name('admin.products.delete');
            Route::post('save_arrangement', 'Admin\ProductsController@save_arrangement')->name('admin.products.save_arrangement');
            Route::post('bulk_action', 'Admin\ProductsController@bulk_action')->name('admin.products.bulk_action');
        });

        Route::group(['prefix' => 'news_page'], function () {
            Route::get('listing', 'Admin\NewsPageController@listing')->name('admin.news_page.listing');
            Route::get('detail/{id?}', 'Admin\NewsPageController@detail')->name('admin.news_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\NewsPageController@arrangement')->name('admin.news_page.arrangement');
            Route::post('save', 'Admin\NewsPageController@save')->name('admin.news_page.save');
            Route::post('delete', 'Admin\NewsPageController@delete')->name('admin.news_page.delete');
            Route::post('save_arrangement', 'Admin\NewsPageController@save_arrangement')->name('admin.news_page.save_arrangement');
            Route::post('bulk_action', 'Admin\NewsPageController@bulk_action')->name('admin.news_page.bulk_action');
        });

        Route::group(['prefix' => 'news'], function () {
            Route::get('listing', 'Admin\NewsController@listing')->name('admin.news.listing');
            Route::get('detail/{id?}', 'Admin\NewsController@detail')->name('admin.news.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\NewsController@arrangement')->name('admin.news.arrangement');
            Route::post('save', 'Admin\NewsController@save')->name('admin.news.save');
            Route::post('delete', 'Admin\NewsController@delete')->name('admin.news.delete');
            Route::post('save_arrangement', 'Admin\NewsController@save_arrangement')->name('admin.news.save_arrangement');
            Route::post('bulk_action', 'Admin\NewsController@bulk_action')->name('admin.news.bulk_action');
        });

        Route::group(['prefix' => 'certificate_page'], function () {
            Route::get('listing', 'Admin\CertificatePageController@listing')->name('admin.certificate_page.listing');
            Route::get('detail/{id?}', 'Admin\CertificatePageController@detail')->name('admin.certificate_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\CertificatePageController@arrangement')->name('admin.certificate_page.arrangement');
            Route::post('save', 'Admin\CertificatePageController@save')->name('admin.certificate_page.save');
            Route::post('delete', 'Admin\CertificatePageController@delete')->name('admin.certificate_page.delete');
            Route::post('save_arrangement', 'Admin\CertificatePageController@save_arrangement')->name('admin.certificate_page.save_arrangement');
            Route::post('bulk_action', 'Admin\CertificatePageController@bulk_action')->name('admin.certificate_page.bulk_action');
        });

        Route::group(['prefix' => 'certificates'], function () {
            Route::get('listing', 'Admin\CertificatesController@listing')->name('admin.certificates.listing');
            Route::get('detail/{id?}', 'Admin\CertificatesController@detail')->name('admin.certificates.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\CertificatesController@arrangement')->name('admin.certificates.arrangement');
            Route::post('save', 'Admin\CertificatesController@save')->name('admin.certificates.save');
            Route::post('delete', 'Admin\CertificatesController@delete')->name('admin.certificates.delete');
            Route::post('save_arrangement', 'Admin\CertificatesController@save_arrangement')->name('admin.certificates.save_arrangement');
            Route::post('bulk_action', 'Admin\CertificatesController@bulk_action')->name('admin.certificates.bulk_action');
        });

        Route::group(['prefix' => 'contact_page'], function () {
            Route::get('listing', 'Admin\ContactPageController@listing')->name('admin.contact_page.listing');
            Route::get('detail/{id?}', 'Admin\ContactPageController@detail')->name('admin.contact_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\ContactPageController@arrangement')->name('admin.contact_page.arrangement');
            Route::post('save', 'Admin\ContactPageController@save')->name('admin.contact_page.save');
            Route::post('delete', 'Admin\ContactPageController@delete')->name('admin.contact_page.delete');
            Route::post('save_arrangement', 'Admin\ContactPageController@save_arrangement')->name('admin.contact_page.save_arrangement');
            Route::post('bulk_action', 'Admin\ContactPageController@bulk_action')->name('admin.contact_page.bulk_action');
        });

        Route::group(['prefix' => 'header'], function () {
            Route::get('listing', 'Admin\HeaderController@listing')->name('admin.header.listing');
            Route::get('detail/{id?}', 'Admin\HeaderController@detail')->name('admin.header.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\HeaderController@arrangement')->name('admin.header.arrangement');
            Route::post('save', 'Admin\HeaderController@save')->name('admin.header.save');
            Route::post('delete', 'Admin\HeaderController@delete')->name('admin.header.delete');
            Route::post('save_arrangement', 'Admin\HeaderController@save_arrangement')->name('admin.header.save_arrangement');
            Route::post('bulk_action', 'Admin\HeaderController@bulk_action')->name('admin.header.bulk_action');
        });

        Route::group(['prefix' => 'products_page'], function () {
            Route::get('listing', 'Admin\ProductsPageController@listing')->name('admin.products_page.listing');
            Route::get('detail/{id?}', 'Admin\ProductsPageController@detail')->name('admin.products_page.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\ProductsPageController@arrangement')->name('admin.products_page.arrangement');
            Route::post('save', 'Admin\ProductsPageController@save')->name('admin.products_page.save');
            Route::post('delete', 'Admin\ProductsPageController@delete')->name('admin.products_page.delete');
            Route::post('save_arrangement', 'Admin\ProductsPageController@save_arrangement')->name('admin.products_page.save_arrangement');
            Route::post('bulk_action', 'Admin\ProductsPageController@bulk_action')->name('admin.products_page.bulk_action');
        });




        Route::group(['prefix' => 'language'], function () {
            Route::get('listing', 'Admin\LanguagePageController@listing')->name('admin.language.listing');
            Route::get('detail/{id?}', 'Admin\LanguagePageController@detail')->name('admin.language.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\LanguagePageController@arrangement')->name('admin.language.arrangement');
            Route::post('save', 'Admin\LanguagePageController@save')->name('admin.language.save');
            Route::post('delete', 'Admin\LanguagePageController@delete')->name('admin.language.delete');
            Route::post('save_arrangement', 'Admin\LanguagePageController@save_arrangement')->name('admin.language.save_arrangement');
            Route::post('bulk_action', 'Admin\LanguagePageController@bulk_action')->name('admin.language.bulk_action');
        });





                

        Route::group(['prefix' => 'translation'], function () {
            Route::get('listing', 'Admin\TranslationController@listing')->name('admin.translation.listing');
            Route::get('detail/{id?}', 'Admin\TranslationController@detail')->name('admin.translation.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\TranslationController@arrangement')->name('admin.translation.arrangement');
            Route::post('save', 'Admin\TranslationController@save')->name('admin.translation.save');
            Route::post('delete', 'Admin\TranslationController@delete')->name('admin.translation.delete');
            Route::post('save_arrangement', 'Admin\TranslationController@save_arrangement')->name('admin.translation.save_arrangement');
            Route::post('bulk_action', 'Admin\TranslationController@bulkAction')->name('admin.translation.bulk_action');
        });

        Route::group(['prefix' => 'media'], function () {
            Route::post('upload', 'Admin\MediaController@upload')->name('admin.media.upload');
            Route::get('medias_in_folder/{folder_id?}', 'Admin\MediaController@medias_in_folder')->name('admin.media.medias_in_folder');
            Route::post('create_folder', 'Admin\MediaController@create_folder')->name('admin.media.create_folder');
            Route::post('rename_folder', 'Admin\MediaController@rename_folder')->name('admin.media.rename_folder');
            Route::post('move_folder', 'Admin\MediaController@move_folder')->name('admin.media.move_folder');
            Route::post('delete_folder', 'Admin\MediaController@delete_folder')->name('admin.media.delete_folder');
            Route::post('move', 'Admin\MediaController@move')->name('admin.media.move');
            Route::post('edit', 'Admin\MediaController@edit')->name('admin.media.edit');
            Route::post('delete', 'Admin\MediaController@delete')->name('admin.media.delete');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('listing', 'Admin\UserController@listing')->name('admin.user.listing');
            Route::get('detail/{id?}', 'Admin\UserController@detail')->name('admin.user.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\UserController@arrangement')->name('admin.user.arrangement');
            Route::post('save', 'Admin\UserController@save')->name('admin.user.save');
            Route::post('delete', 'Admin\UserController@delete')->name('admin.user.delete');
            Route::post('save_arrangement', 'Admin\UserController@save_arrangement')->name('admin.user.save_arrangement');
            Route::post('bulk_action', 'Admin\UserController@bulkAction')->name('admin.user.bulk_action');
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('listing', 'Admin\RoleController@listing')->name('admin.role.listing');
            Route::get('detail/{id?}', 'Admin\RoleController@detail')->name('admin.role.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\RoleController@arrangement')->name('admin.role.arrangement');
            Route::post('save', 'Admin\RoleController@save')->name('admin.role.save');
            Route::post('delete', 'Admin\RoleController@delete')->name('admin.role.delete');
            Route::post('save_arrangement', 'Admin\RoleController@save_arrangement')->name('admin.role.save_arrangement');
            Route::post('bulk_action', 'Admin\RoleController@bulkAction')->name('admin.role.bulk_action');
        });

        Route::group(['prefix' => 'system_setting'], function () {
            Route::get('listing', 'Admin\SystemSettingController@listing')->name('admin.system_setting.listing');
            Route::get('detail/{id?}', 'Admin\SystemSettingController@detail')->name('admin.system_setting.detail');
            Route::get('arrangement/{parent_id?}', 'Admin\SystemSettingController@arrangement')->name('admin.system_setting.arrangement');
            Route::post('save', 'Admin\SystemSettingController@save')->name('admin.system_setting.save');
            Route::post('delete', 'Admin\SystemSettingController@delete')->name('admin.system_setting.delete');
            Route::post('save_arrangement', 'Admin\SystemSettingController@save_arrangement')->name('admin.system_setting.save_arrangement');
            Route::post('bulk_action', 'Admin\SystemSettingController@bulkAction')->name('admin.system_setting.bulk_action');
        });

        Route::get('media_library', 'Admin\MediaLibraryController@index')->name('admin.media_library.index');

        Route::get('2fa','Auth\PasswordSecurityController@show2faForm')->name('2fa');
        Route::post('generate2faSecret','Auth\PasswordSecurityController@generate2faSecret')->name('generate2faSecret');
        Route::post('2fa','Auth\PasswordSecurityController@enable2fa')->name('enable2fa');
        Route::post('disable2fa','Auth\PasswordSecurityController@disable2fa')->name('disable2fa');

        Route::post('2faVerify', function () {
            return redirect(URL()->previous());
        })->name('2faVerify')->middleware('2fa');

    });



    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // // Registration Routes...
    // Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    // Route::post('register', 'Auth\RegisterController@register');

    // // Password Reset Routes...
    // Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    // Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    // Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    // Route::post('password/reset', 'Auth\ResetPasswordController@reset');

});





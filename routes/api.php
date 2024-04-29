<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', 'Api\AuthController@signup');
Route::post('login', 'Api\AuthController@login');
Route::get('signup-settings', 'Api\AuthController@signUpSettings');
Route::get('/get-countries', 'Api\AuthController@getCountries');
Route::get('/sellers-type', 'Api\ApiController@sellerTypes');
Route::post('forget-password', 'Api\AuthController@forgetPassword');
Route::post('verify-password-otp', 'Api\AuthController@verifyPasswordOTP');
Route::post('reset-password/{token}', 'Api\AuthController@resetPassword');

Route::middleware('auth:api')->group(function () {
    Route::post('change-password', 'Api\AuthController@changePassword');
    Route::post('add-company', 'Api\DashboardController@Company');
    Route::get('company-list', 'Api\DashboardController@companyList');
    Route::post('add-category', 'Api\DashboardController@Category');
    Route::post('add-sub-category', 'Api\DashboardController@SubCategory');
    Route::post('add-file', 'Api\DashboardController@FileSubCategory');
    Route::post('search-sub-category', 'Api\DashboardController@searchSubCategory');
    Route::post('search-file', 'Api\DashboardController@SearchFile');
    Route::post('add-favorite-file', 'Api\DashboardController@FavoriteFile');
    Route::post('delete-favorite-file', 'Api\DashboardController@FavoriteFileDelete');
    Route::get('favorite-file-list', 'Api\DashboardController@FavoriteFilesShow');
    Route::get('category-list', 'Api\DashboardController@CategoryList');
    Route::post('subcategory-list', 'Api\DashboardController@SubCategoryList');
    Route::post('files', 'Api\DashboardController@Files')->name('files');
    
    //logout
    Route::post('logout', 'Api\AuthController@logout');
    //Phase 2
    Route::post('subcategory-child-list', 'Api\DashboardController@subCategoryChild');
    Route::post('search-sub-category-child', 'Api\DashboardController@searchSubCategoryChild');
    Route::post('subcategory-subchild-list', 'Api\DashboardController@subCategorySubChild');
    Route::post('search-sub-category-sub-child', 'Api\DashboardController@searchSubCategorySubChild');
    Route::post('search-file-type', 'Api\DashboardController@searchFileType');
    Route::get('file-type', 'Api\DashboardController@fileType');
    Route::post('view-file', 'Api\DashboardController@viewFile');
    Route::post('search-files', 'Api\DashboardController@SearchFiles');
    Route::post('list-folder', 'Api\DashboardController@listFolder');
    Route::post('create-folder', 'Api\DashboardController@createFolder');
    Route::post('delete-folder', 'Api\DashboardController@deleteFolder');
    Route::post('add-file', 'Api\DashboardController@addFile');
    Route::post('folder-file', 'Api\DashboardController@folderFile');
    Route::post('delete-file', 'Api\DashboardController@deleteFile');

});

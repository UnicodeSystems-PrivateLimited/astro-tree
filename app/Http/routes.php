<?php
use App\User;
use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/admin/client/list', [
    'as' => 'client.list',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'ClientController@getList'
]);
Route::get('/admin/client/{cid}/details', [
    'as'=>'client.details',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'ClientController@showDetails'
]);
Route::get('/admin/client/add', [
    'as'=>'client.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'ClientController@editUser'
]);
Route::post('/admin/client/add', [
    'as'=>'client.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'ClientController@postEditUser'
]);
Route::post('/admin/client/astro/{cid}/add', [
    'as'=>'client.astro.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'ClientController@addAstroProfile'
]);
Route::get('/admin/solutions/', [
    'as'=>'solution.list',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@getList'
]);
Route::post('/admin/solutions/add', [
    'as'=>'solution.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@addSolution'
]);
Route::get('/admin/gifts', [
    'as'=>'gift.list',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@getList'
]);
Route::post('/admin/gifts/add', [
    'as'=>'gift.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@addGift'
]);
Route::get('/admin/relations', [
    'as'=>'relation.list',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@getList'
]);
Route::post('/admin/relations/add', [
    'as'=>'relation.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@addRelation'
]);

Route::get('/admin/gift/categories', [
    'as'=>'gift.categories',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@getCategoryList'
]);
Route::post('/admin/gift/category/add', [
    'as'=>'gift.category.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@addCategory'
]);
Route::get('/admin/gift/category/{id}', [
    'as'=>'gift.category.view',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@getCategory'
]);
Route::get('/admin/gift/category/{id}/delete', [
    'as'=>'gift.category.delete',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'GiftController@deleteCategory'
]);

Route::get('/admin/solution/categories', [
    'as'=>'solution.categories',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@getCategoryList'
]);
Route::post('/admin/solution/category/add', [
    'as'=>'solution.category.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@addCategory'
]);
Route::get('/admin/gift/category/{id}', [
    'as'=>'solution.category.view',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@getCategory'
]);
Route::get('/admin/solution/category/{id}/delete', [
    'as'=>'solution.category.delete',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'SolutionController@deleteCategory'
]);

Route::get('/admin/relation/types', [
    'as'=>'relation.types',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@getTypeList'
]);
Route::post('/admin/relation/type/add', [
    'as'=>'relation.type.add',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@addType'
]);
Route::get('/admin/relation/type/{id}', [
    'as'=>'relation.type.view',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@getType'
]);
Route::get('/admin/relation/type/{id}/delete', [
    'as'=>'relation.type.delete',
    'middleware' => 'logged:/',
//    'middleware' => 'has_perm:_superadmin',
    'uses' => 'RelationController@deleteType'
]);
/********Dashboard routes**********/
Route::get('/admin/dashboard',[
    'as'=>'dashboard.data',
    'middleware' => 'logged:/',
    'uses' => 'Admin\DashboardController@getData'
]);

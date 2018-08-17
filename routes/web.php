<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// Routes to the passwordless authentication. the ->name is what allows you to
// call route('signin'). BB 12/02/17
// remove route('signin'). DA 1/18/2018
//Route::get('/signin', 'Auth\PasswordlessLoginController@show')->name('signin');
//Route::post('/signin', 'Auth\PasswordlessLoginController@sendToken');
//Route::get('/signin/{token}', 'Auth\PasswordlessLoginController@validateToken');

Route::get('organization', 'OrganizationController@index');
Route::get('organization/create', 'OrganizationController@create');
Route::get('organization/{id}/edit', 'OrganizationController@show')->name("organization-edit");
Route::post('organization', 'OrganizationController@store')->name('organization.save');
Route::patch('organization/{id}', 'OrganizationController@update')->name('organization.store');
Route::post('organization/{id}', 'OrganizationController@delete')->name('organization-delete');
Route::get('organization/{organzationid}/team','CollaboratorController@index')->name("organization-team");
Route::post('organization/{organzationid}/team/add','CollaboratorController@postInvite')->name('add-member');
Route::post('organization/{organzationId}/team/resend/{invitationId}','CollaboratorController@postResendInvitation')->name('resend-invitation');
Route::post('organization/{organzationId}/team/{collaboratorId}','CollaboratorController@postDelete')->name('delete-collaboration');
Route::post('organization/{organzationId}/update-role','CollaboratorController@postRoleUpdate')->name('role-update');
//Route::post('organization/add-user', 'OrganizationController@addusers');
Route::get("organization/{id}",'ApplicationController@getIndex')->name("organization-application");
Route::post("organization/{id}/application", 'ApplicationController@postStore')->name("organization-application-new");
//Route::post("organization/{id}/application-restore", 'ApplicationController@postRestore')->name("organization-application-restore");
Route::get("organization/{id}/application/{appId}","ApplicationController@getShow")->name("organization-application-view");
Route::get("organization/{id}/application/{appId}/edit","ApplicationController@getEdit")->name("organization-application-edit");
Route::post("organization/{id}/application/{appId}/update","ApplicationController@postUpdate")->name("organization-application-update");
Route::post("organization/{id}/application/{appId}/delete",'ApplicationController@postDelete')->name('application-delete');
Route::get("organization/{id}/search","ApplicationController@getSearch")->name("organization-search");
Route::post("application-save",'ApplicationController@postSave')->name('application-update');
Route::get("organization/{organization}/columns",'MetaController@getIndex')->name('organization-meta');
Route::post("organization/{organization}/columns",'MetaController@postStore')->name('organization-meta-new');
Route::get("organization/{organization}/columns-attribute",'MetaController@getMetaform')->name('organization-meta-new-ajax');
Route::post("organization/{organization}/columns/{meta}",'MetaController@deleteMeta')->name('organization-meta-delete');
Route::post("organization/{organization}/columns-reposition",'MetaController@postReposition')->name('meta-reposition');
Route::get("organization/{organization}/groups",'GroupController@getIndex')->name('organization-groups');
Route::get("organization/{organization}/groups/create",'GroupController@getCreate')->name('create-group');
Route::post("organization/{organization}/groups/create",'GroupController@postStore')->name('organization-group-new');
Route::post("organization/{organization}/groups/{group}",'GroupController@postDelete')->name('delete-group');
Route::post("organization/{organization}/save-columns",'OrganizationController@postSaveColumns')->name('save-columns');
Route::post("organization/{organization}/application/{application}/attachment",'ApplicationController@postUpload')->name('application-upload');
Route::get("organization/{organization}/application/{application}/attachment/{id}",'ApplicationController@getDocument')->name('document');
Route::get("organization/{organization}/application/{application}/attachment-download/{id}",'ApplicationController@getDownloadDocument')->name('download-document');
Route::get("organization/{organization}/application/{application}/delete-document/{id}",'ApplicationController@getDeleteDocument')->name('delete-document');



Route::get('/invitation/{code}','InvitationController@index');
Route::get('user/subscription','UserController@index')->name("user-subscription");
Route::get('/upgrade','UserController@upgrade')->name("user-plan-upgrade");
Route::post('/upgrade','UserController@update')->name("user-plan-payment");

Route::get('user/','UserController@show')->name("user-show");
Route::get('user/edit','UserController@edit')->name("user-edit");
Route::put('user/update','UserController@save')->name("user-update");
Route::get('user/changepassword','UserController@showChangePasswordForm')->name("user-changepassword");
Route::post('user/changepassword','UserController@changePassword')->name('changePassword');
Route::post("/invitation/{code}",'InvitationController@postCreatePassword')->name("user-createpassword");

Route::get("user/payment-info",'UserController@payment')->name('user-payment-info');
Route::post("user/payment-info",'UserController@paymentUpdate')->name('user-payment-info-update');

Route::get('/servicecatalog/search/', 'ServiceCatalogController@find');
//Routes for admin DA 1/6/2018
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', 'AdminController@index')->name('admin');

	//Routes for admin/servicecatalog
	Route::resource('/admin/service_catalog', 'ServiceCatalogController');
	Route::get('/admin/service_catalog/{?}', 'ServiceCatalogController@search')->name('service_catalog_search');
	Route::post('/admin/service_catalog/fetch_by_url','ServiceCatalogController@getservicebyURL')->name('getservicebyURL');
	Route::post('/admin/service_catalog/fetch_url_from_files','ServiceCatalogController@fetchbyURL')->name('fetchbyURL');

	//Routes for admin/category DA 1/6/2018
	Route::resource('/admin/service_category', 'ServiceCategoryController');
	Route::get('/admin/service_category/{?}', 'ServiceCategoryController@search')->name('service_category_search');
});

Route::get('/permission-denied',function(){
     return view('permission-denied');
});

Route::post(
    'stripe/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);

Route::get('pricing','PagesController@pricing')->name("pricing");
Route::get('features','PagesController@features')->name("features");
Route::get('about','PagesController@about')->name("about");
Route::get('contact','PagesController@contact')->name("contact");

Route::get('/analyze','AnalyzerController@getIndex');
Route::post('/analyze','AnalyzerController@postAnalyze')->name('analyze-stack');


Route::post('/create-organization-with-domain','AnalyzerController@postCreateOrganizationWithDomain')->name('create-organization-with-domain');


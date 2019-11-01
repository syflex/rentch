<?php
use Illuminate\Http\Request;
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
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('email/subscribe', 'EmailListController@create');
// Route::get('email/get-all', 'EmailListController@show');
Route::prefix('auth')->group(function () {
	Route::post('signup', 'SignUpController@signUp');
	Route::post('login', 'LoginController@auth');
	Route::get('logout', 'LoginController@logout');
	Route::post('send-activation-link', 'UserController@resend_activation_link');
	Route::post('send-reset-password-token', 'UserController@send_reset_password_token');
	Route::get('verifyemail/{token}', 'UserController@activate_email_account');
	Route::post('reset-password', 'UserController@reset_password_with_token');
});
Route::group(['middleware' => 'auth:api'], function() {
	Route::prefix('user')->group(function () {
	    Route::post('update', 'UserController@update');
	    Route::post('avata', 'UserController@upload_user_image');
		Route::get('me', 'UserController@me');
	});
	
    Route::prefix('states')->group(function () {
        Route::post('create', 'StateController@create');
        Route::get('/', 'StateController@show');
        Route::get('/{resource_id}', 'StateController@get_state');
        Route::get('/locals/{state_id}', 'LocalGovtController@state_locals');
        Route::get('/cities/{state_id}', 'CityController@state_cities');
    });
    Route::prefix('amenities')->group(function () {
        Route::post('create', 'AmenitiesController@create');
        Route::post('update', 'AmenitiesController@update');
        Route::get('all', 'AmenitiesController@show');
        Route::get('paginate', 'AmenitiesController@paginated_amenities');
        Route::get('delete/{resource_id}', 'AmenitiesController@destroy');
    });
    Route::prefix('user-amenities')->group(function () {
        Route::post('bulk/update', 'UserAmenitiesController@bulk_update');
        Route::get('/{user_id}', 'UserAmenitiesController@show');
    });
    Route::prefix('user-preference')->group(function () {
        Route::post('update', 'UserPreferenceController@create');
        Route::get('/{user_id}', 'UserPreferenceController@show');
        Route::get('/delete/{user_id}', 'UserPreferenceController@destroy');
    });
    Route::prefix('listing-categories')->group(function () {
	    Route::post('create', 'ListingCategoryController@create');
	    Route::post('update/{id}', 'ListingCategoryController@update');
	    Route::get('/', 'ListingCategoryController@show');
	    Route::get('delete/{resource_id}', 'ListingCategoryController@delete');
	});
	Route::prefix('listings')->group(function () {
	    Route::post('create', 'ListingController@create');
	    Route::post('search', 'ListingController@search');
	    Route::post('update', 'ListingController@update');
	    Route::get('/', 'ListingController@get');
	    Route::get('/{resource_id}', 'ListingController@show');
	    Route::get('/user/{resource_id}', 'ListingController@user_listings');
	    Route::get('/limit/{resource_id}', 'ListingController@paginated');
	    Route::get('delete/{resource_id}', 'ListingController@delete');
	});
	Route::prefix('listing-image')->group(function () {
	    Route::post('upload', 'ListingImageController@create');
	    Route::get('delete/{resource_id}', 'ListingImageController@destroy');
	});
	
	Route::prefix('listing-amenities')->group(function () {
	    Route::post('bulk/update', 'ListingAmenitiesController@bulk_update');
	    Route::get('/{user_id}', 'ListingAmenitiesController@show');
	});
	Route::prefix('blog-category')->group(function () {
	    Route::post('create', 'BlogCategoryController@create');
	    Route::post('/update', 'BlogCategoryController@update');
	    Route::get('/', 'BlogCategoryController@get');
	    Route::get('/{resource_id}', 'BlogCategoryController@show');
	    Route::get('/delete/{resource_id}', 'BlogCategoryController@destroy');
	});
	Route::prefix('blog')->group(function () {
	    Route::post('create', 'BlogController@create');
	    Route::post('/update', 'BlogController@update');
	    Route::get('/', 'BlogController@get');
	    Route::get('/{resource_id}', 'BlogController@show');
	    Route::get('/delete/{resource_id}', 'BlogController@destroy');
	});
	Route::prefix('listing-favorites')->group(function () {
	    Route::post('create', 'ListingFavoriteController@create');
	    Route::post('delete', 'ListingFavoriteController@destroy');
	    Route::get('user/{resource_id}', 'ListingFavoriteController@user_favorites');
	    Route::get('listing/{resource_id}', 'ListingFavoriteController@listing_favorites');
	});
});
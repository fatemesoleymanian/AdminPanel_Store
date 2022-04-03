<?php

use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

//Route::get('/{any?}',[\App\Http\Controllers\AdminUsersController::class,'index'] )
//->where('any','.*');

Route::post('users/update_information',[\App\Http\Controllers\AdminUsersController::class,'updateInfo']);
Route::post('app/login_admin',[\App\Http\Controllers\AdminUsersController::class,'adminLogin']);
Route::get('app/get_panelusers',[\App\Http\Controllers\AdminUsersController::class,'showPanelUsers']);
Route::get('app/get_roles',[\App\Http\Controllers\RolesController::class,'showRoles']);
Route::post('app/create_role',[\App\Http\Controllers\RolesController::class,'addRole']);
Route::post('app/update_role',[\App\Http\Controllers\RolesController::class,'editRole']);
Route::post('app/delete_role',[\App\Http\Controllers\RolesController::class,'deleteRole']);
Route::post('app/assign_role',[\App\Http\Controllers\RolesController::class,'assignRoles']);
Route::post('users/delete_user',[\App\Http\Controllers\AdminUsersController::class,'deleteUser']);
Route::post('users/create_user',[\App\Http\Controllers\AdminUsersController::class,'createPanelUser']);
Route::post('users/edit_user_role',[\App\Http\Controllers\AdminUsersController::class,'editPanelUserRole']);

//permissions
Route::get('/permissions/get_permissions',[\App\Http\Controllers\PermissionController::class,'show']);
Route::get('/permissions/get_role_permissions/{id}',[\App\Http\Controllers\RolePermissionController::class,'getPermissionOfRole']);
Route::post('/permissions/change_permission',[\App\Http\Controllers\RolePermissionController::class,'change']);

Route::post('roles/create_role',[\App\Http\Controllers\RolesController::class,'addRole']);
Route::post('roles/edit_role',[\App\Http\Controllers\RolesController::class,'editRole']);
Route::post('roles/delete_role',[\App\Http\Controllers\RolesController::class,'deleteRole']);

//ads
Route::get('ads/get_ads',[\App\Http\Controllers\AdsController::class,'getAds']);
Route::post('ads/create_ad',[\App\Http\Controllers\AdsController::class,'createAds']);
Route::post('app/upload',[\App\Http\Controllers\AdsController::class,'upload']);
Route::post('app/delete_image',[\App\Http\Controllers\AdsController::class,'deleteServerImage']);
Route::post('app/delete_images',[\App\Http\Controllers\AdsController::class,'deleteGroupImages']);
Route::post('ads/edit_ad',[\App\Http\Controllers\AdsController::class,'editAd']);
Route::post('ads/delete_ad',[\App\Http\Controllers\AdsController::class,'deleteAd']);

//brands
Route::get('/brands/get_brands',[\App\Http\Controllers\BrandsController::class,'show']);
Route::post('brand/upload',[\App\Http\Controllers\BrandsController::class,'upload']);
Route::post('brands/create_brand',[\App\Http\Controllers\BrandsController::class,'add']);
Route::post('brands/edit_brand',[\App\Http\Controllers\BrandsController::class,'edit']);
Route::post('brands/delete_brand',[\App\Http\Controllers\BrandsController::class,'delete']);

//Categories
Route::get('categories/get_categories',[\App\Http\Controllers\CategoriesController::class,'show']);
Route::post('category/upload',[\App\Http\Controllers\CategoriesController::class,'upload']);
Route::post('categories/create_category',[\App\Http\Controllers\CategoriesController::class,'add']);
Route::post('categories/delete_category',[\App\Http\Controllers\CategoriesController::class,'delete']);
Route::post('categories/edit_category',[\App\Http\Controllers\CategoriesController::class,'edit']);

//Colors
Route::post('colors/create_color',[\App\Http\Controllers\ColorsController::class,'add']);
Route::get('colors/get_colors',[\App\Http\Controllers\ColorsController::class,'show']);
Route::post('colors/delete_color',[\App\Http\Controllers\ColorsController::class,'delete']);
Route::post('colors/edit_color',[\App\Http\Controllers\ColorsController::class,'edit']);

//Products
Route::get('products/get_products',[\App\Http\Controllers\ProductsController::class,'show']);
Route::get('products/get_product/{id}',[\App\Http\Controllers\ProductsController::class,'showDetails']);
Route::post('products/add_product',[\App\Http\Controllers\ProductsController::class,'add']);
Route::post('products/upload',[\App\Http\Controllers\ProductsController::class,'upload']);
Route::post('products/edit_product',[\App\Http\Controllers\ProductsController::class,'update']);
Route::post('products/delete_product',[\App\Http\Controllers\ProductsController::class,'delete']);
Route::get('products/get_images/{id}',[\App\Http\Controllers\ProductsController::class,'showImages']);
Route::get('products/get_prices/{id}',[\App\Http\Controllers\ProductsController::class,'showPrices']);
Route::get('products/get_numbers',[\App\Http\Controllers\ProductsController::class,'productNumbers']);
Route::post('products/filter',[\App\Http\Controllers\ProductsController::class,'filter']);

//Forget Password
Route::post('/forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm']);
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);

Route::get('/logout',[\App\Http\Controllers\AdminUsersController::class,'logout']);
Route::get('/{any?}',[\App\Http\Controllers\AdminUsersController::class,'access'])
    ->where('any','.*');

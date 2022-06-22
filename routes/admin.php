<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware applied to all routes
 *
 * @see \App\Providers\RouteServiceProvider::mapAdminRoutes()
 */


Route::get('profile/{user}', 'Auth\UserController@show')->name('profile');
Route::group(['prefix' => 'admin'], function () {

    Route::get('/', 'Admin\HomeController@index')->name('admin.home');
    Route::get('/administrativeTasks', 'AdministrativeTasks\AdministrativeTaskController@indexAdmin')->name('admin.administrativeTasks');
    Route::delete('/destroy/administrativeTasks/{id}', 'AdministrativeTasks\AdministrativeTaskController@destroyAdmin')->name('admintaskAdmin.delete');
    Route::resource('users', 'Auth\UserController')->except('show');
    Route::resource('roles', 'Auth\RoleController')->except('show');
    Route::resource('companies', 'Admin\CompanyController');
    Route::resource('departments', 'Admin\DepartmentController')->except('show');
    Route::group(['prefix' => 'catalogs'], function () {
        Route::view('/', 'modules.indicator.index')->name('indicator.catalogs');
        Route::resource('thresholds', 'Indicators\Thresholds\ThresHoldController');
        Route::resource('indicator_units', 'Indicators\Units\IndicatorUnitController');
        Route::resource('indicator_sources', 'Indicators\Sources\IndicatorSourceController');
        Route::resource('perspectives', 'Admin\PerspectiveController');
    });

});
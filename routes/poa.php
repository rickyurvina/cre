<?php

use App\Models\Poa\PoaActivityPiat;
use Illuminate\Support\Facades\Route;

/**
 * 'poa' middleware applied to all routes
 *
 * @see \App\Providers\RouteServiceProvider::mapPoaRoutes()
 */

Route::group(['prefix' => 'poa'], function () {

    Route::resource('poas','Poa\PoaController')->except('index');
    Route::get('/', 'Poa\HomeController@index')->name('poa.home');
    Route::get('poas', 'Poa\PoaController@index')->name('poa.poas');
    Route::get('poasReports/{poaId?}', 'Poa\PoaController@reports')->name('poa.reports');
    Route::get('poasChangeControl/{poaId?}', 'Poa\PoaController@changeControl')->name('poa.change_control');
    Route::get('poasConfig/{poaId?}', 'Poa\PoaController@config')->name('poa.config');
    Route::get('create', 'Poa\PoaController@store')->name('poa.create');
    Route::get('programs/{poaId}', 'Poa\PoaController@showPrograms')->name('poa.show_programs');
    Route::get('replicate/{poaId}', 'Poa\PoaController@replicate')->name('poa.replicate');
    Route::get('goalChangeRequest', 'Poa\PoaController@goalChangeRequest')->name('poa.goal_change_request');
    Route::get('manageCatalogActivities', 'Poa\PoaController@manageCatalogActivities')->name('poa.manage_catalog_activities');
    Route::delete('deleteCatalogActivities/{id}', 'Poa\PoaController@deleteCatalogActivities')->name('poa.delete_catalog_activities');
    Route::delete('deletePiatMatrix/{piat}', 'Poa\ActivityController@deleteMatrixPiat', function(PoaActivityPiat $piat){
        return $piat;
    })->name('poa.activity.delete_piat_matrix');

    Route::get('reports/index/{poaId?}', 'Poa\PoaReportController@index')->name('poa.reports.index');
    Route::get('reports/reached-people/{poaId?}', 'Poa\PoaReportController@reachedPeople')->name('poa.reports.reached_people');
    Route::get('reports/reached-people-export/{poaId?}', 'Poa\PoaReportController@exportReachedPeople')->name('poa.reports.reached_people.export');
    Route::get('reports/satisfaction-level/{poaId?}', 'Poa\PoaReportController@satisfactionLevel')->name('poa.reports.satisfaction_level');
    Route::get('reports/satisfaction-level-export/{poaId?}', 'Poa\PoaReportController@exportSatisfactionLevel')->name('poa.reports.satisfaction_level.export');
    Route::get('reports/trained-people/{poaId?}', 'Poa\PoaReportController@trainedPeople')->name('poa.reports.trained_people');
    Route::get('reports/trained-people-export/{poaId?}', 'Poa\PoaReportController@exportTrainedPeople')->name('poa.reports.trained_people.export');
    Route::get('reports/products/{poaId?}', 'Poa\PoaReportController@products')->name('poa.reports.products');
    Route::get('reports/products-export/{poaId?}', 'Poa\PoaReportController@exportProducts')->name('poa.reports.products.export');
    Route::get('reports/goals/{poaId?}', 'Poa\PoaReportController@goals')->name('poa.reports.goals');
    Route::get('reports/goals-export/{poaId?}', 'Poa\PoaReportController@exportGoals')->name('poa.reports.goals.export');
    Route::get('reports/activity-status/{poaId?}', 'Poa\PoaReportController@activityStatus')->name('poa.reports.activity_status');
    Route::get('reports/activity-status-export/{poaId?}', 'Poa\PoaReportController@exportActivityStatus')->name('poa.reports.activity_status.export');

    Route::resource('poas.activities','Poa\ActivityController')->shallow();

});
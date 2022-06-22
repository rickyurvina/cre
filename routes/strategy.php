<?php

use Illuminate\Support\Facades\Route;

/**
 * 'strategy' middleware applied to all routes
 *
 * @see \App\Providers\RouteServiceProvider::mapStrategyRoutes()
 */

Route::group(['prefix' => 'strategy'], function () {

    Route::get('/', 'Strategy\HomeController@index')->name('strategy.home');

    Route::resource('templates', 'Strategy\TemplateController');

    Route::resource('plans', 'Strategy\PlanController');

    Route::delete('plans/{plan}/delete', 'Strategy\PlanController@destroy')->name('plans.delete');
    Route::delete('plan/{plan}/delete', 'Strategy\PlanController@delete')->name('plan.delete');

    Route::get('plans/{plan}/details', 'Strategy\PlanController@detail')->name('plans.detail');
    Route::get('plans/{plan}/details-index', 'Strategy\PlanController@listDetails')->name('plans.details');

    Route::get('plans/indicators/{planDetailId?}',[\App\Http\Controllers\Strategy\PlanController::class,'showPlanDetailsIndicators'])->name('plan_details.indicators');

    Route::get('plans/{plan}/articulations', 'Strategy\PlanController@articulations')->name('plans.articulations');
    Route::get('plans/detail/{id}/edit', 'Strategy\PlanController@detailEdit')->name('plans.detail.edit');
    Route::post('plans/detail/{id}/update', 'Strategy\PlanController@detailUpdate')->name('plans.detail.update');
    Route::get('report', 'Strategy\StrategyReportController@reportIndicators')->name('report.index');
    Route::get('report_articulations', 'Strategy\StrategyReportController@reportArticulations')->name('report_articulations.index');

    Route::get('report/poa', 'Strategy\StrategyReportController@exportPdf')->name('report.poa');
});
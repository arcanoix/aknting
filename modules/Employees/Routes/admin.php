<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'employees' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('employees', function () {
    Route::get('employees/{employee}/duplicate', 'Employees@duplicate')->name('employees.duplicate');
    Route::post('employees/import', 'Employees@import')->name('employees.import');
    Route::get('employees/export', 'Employees@export')->name('employees.export');
    Route::get('employees/{employee}/enable', 'Employees@enable')->name('employees.enable');
    Route::get('employees/{employee}/disable', 'Employees@disable')->name('employees.disable');
    Route::resource('employees', 'Employees')->middleware(['dropzone']);

    Route::get('positions/{position}/duplicate', 'Positions@duplicate')->name('positions.duplicate');
    Route::post('positions/import', 'Positions@import')->name('positions.import');
    Route::get('positions/export', 'Positions@export')->name('positions.export');
    Route::get('positions/{position}/enable', 'Positions@enable')->name('positions.enable');
    Route::get('positions/{position}/disable', 'Positions@disable')->name('positions.disable');
    Route::resource('positions', 'Positions');

    Route::group(['as' => 'modals.', 'prefix' => 'modals'], function () {
        Route::resource('positions', 'Modals\Positions');
    });
});

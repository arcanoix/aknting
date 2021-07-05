<?php


Route::admin('print-template', function () {
    Route::group(['as' => 'settings.'], function () {
        Route::get('settings/{print_template}/enable', 'PrintTemplateController@enable')->name("enable");
        Route::get('settings/{print_template}/disable', 'PrintTemplateController@disable')->name("disable");
        Route::get('settings/{print_template}/print/{invoice}', 'PrintTemplateController@printInvoice')->name("print");
        Route::post('settings/{print_template}/save', 'PrintTemplateController@save')->name("save"); 
        
        Route::get('settings', 'PrintTemplateController@index')->name("index");
        Route::get('settings/create', 'PrintTemplateController@create')->name("create");
        Route::get('settings/{print_template}/edit', 'PrintTemplateController@edit')->name("edit");
        Route::patch('settings/{print_template}/edit', 'PrintTemplateController@update')->name("update");
        Route::post('settings', 'PrintTemplateController@store')->name("store");
        Route::delete('settings/{print_template}', 'PrintTemplateController@destroy')->name("delete");



        Route::get('settings/{print_template}', 'PrintTemplateController@show')->name("show");
        Route::get('settings/{print_template}/design', 'PrintTemplateController@design')->name("design");


    });
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'pos' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('pos', function () {
    Route::get('pos', 'Main@index')->name('pos');

    Route::get('categories', 'Categories@index')->name('categories.index');
    Route::get('items', 'Items@index')->name('items.index');
    Route::get('customers', 'Customers@index')->name('customers.index');

    Route::get('receipts/{order}', 'OrderReceipts@show')->name('receipts.show');
    Route::get('receipts/{order}/print', 'OrderReceipts@print')->name('receipts.print');
    Route::post('receipts/{order}/send', 'OrderReceipts@email')->name('receipts.send');

    Route::resource('orders', 'Orders');
    Route::get('orders/{order}/cancelled', 'Orders@markCancelled')->name('orders.cancelled');

    Route::group([
        'prefix'    => 'settings',
        'as'        => 'settings.',
    ], function () {
        Route::get('/', 'Settings@edit')->name('edit');
        Route::patch('/', 'Settings@update')->name('update');
    });
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * 'portal' middleware and 'portal/credit-debit-notes' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::portal('credit-debit-notes', function () {
    Route::group(['prefix' => 'credit-notes', 'as' => 'credit-notes.'], function () {
        Route::get('{credit_note}/print', 'CreditNotes@printCreditNote')->name('print');
        Route::get('{credit_note}/pdf', 'CreditNotes@pdfCreditNote')->name('pdf');
        Route::post('{credit_note}/confirm', 'CreditNotes@confirm')->name('confirm');
    });
    Route::resource('credit-notes', 'CreditNotes');

    Route::group(['prefix' => 'debit-notes', 'as' => 'debit-notes.'], function () {
        Route::get('{debit_note}/print', 'DebitNotes@printDebitNote')->name('print');
        Route::get('{debit_note}/pdf', 'DebitNotes@pdfDebitNote')->name('pdf');
        Route::post('{debit_note}/confirm', 'DebitNotes@confirm')->name('confirm');
    });
    Route::resource('debit-notes', 'DebitNotes');
}, ['namespace' => 'Modules\CreditDebitNotes\Http\Controllers\Portal']);

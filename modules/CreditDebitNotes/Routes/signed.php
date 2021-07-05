<?php

use Illuminate\Support\Facades\Route;

/**
 * 'signed' middleware and 'signed/credit-debit-notes' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::signed('credit-debit-notes', function () {
    Route::group(['prefix' => 'credit-notes', 'as' => 'credit-notes.'], function () {
        Route::get('{credit_note}', 'CreditNotes@signed')->name('show');
        Route::get('{credit_note}/print', 'CreditNotes@printCreditNote')->name('print');
        Route::get('{credit_note}/pdf', 'CreditNotes@pdfCreditNote')->name('pdf');
    });
    Route::group(['prefix' => 'debit-notes', 'as' => 'debit-notes.'], function () {
        Route::get('{debit_note}', 'DebitNotes@signed')->name('show');
        Route::get('{debit_note}/print', 'DebitNotes@printDebitNote')->name('print');
        Route::get('{debit_note}/pdf', 'DebitNotes@pdfDebitNote')->name('pdf');
    });
}, ['namespace' => 'Modules\CreditDebitNotes\Http\Controllers\Portal']);

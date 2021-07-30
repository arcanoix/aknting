<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'credit-debit-notes' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('credit-debit-notes', function () {
    Route::resource('credit-notes', 'CreditNotes')->middleware(['date.format', 'money', 'dropzone']);
    Route::group(['prefix' => 'credit-notes', 'as' => 'credit-notes.'], function () {
        Route::get('{credit_note}/sent', 'CreditNotes@markSent')->name('sent');
        Route::get('{credit_note}/cancelled', 'CreditNotes@markCancelled')->name('cancelled');
        Route::get('{credit_note}/email', 'CreditNotes@emailCreditNote')->name('email');
        Route::get('{credit_note}/print', 'CreditNotes@printCreditNote')->name('print');
        Route::get('{credit_note}/pdf', 'CreditNotes@pdfCreditNote')->name('pdf');
        Route::get('{credit_note}/duplicate', 'CreditNotes@duplicate')->name('duplicate');
        // TODO: implement export
        Route::get('export', 'CreditNotes@export')->name('export');
    });

    Route::get('customers/{customer}/invoices', 'Customers@invoices')->name('customers.invoices');

    Route::resource('debit-notes', 'DebitNotes')->middleware(['date.format', 'money', 'dropzone']);
    Route::group(['prefix' => 'debit-notes', 'as' => 'debit-notes.'], function () {
        Route::get('{debit_note}/sent', 'DebitNotes@markSent')->name('sent');
        Route::get('{debit_note}/cancelled', 'DebitNotes@markCancelled')->name('cancelled');
        Route::get('{debit_note}/print', 'DebitNotes@printDebitNote')->name('print');
        Route::get('{debit_note}/pdf', 'DebitNotes@pdfDebitNote')->name('pdf');
        Route::get('{debit_note}/duplicate', 'DebitNotes@duplicate')->name('duplicate');
//            // TODO: implement export
        Route::get('export', 'DebitNotes@export')->name('export');
    });

    Route::get('vendors/{vendor}/bills', 'Vendors@bills')->name('vendors.bills');

    Route::group([
        'prefix'    => 'settings',
        'as'        => 'settings.',
        'namespace' => 'Settings'
    ], function () {
        Route::group(['prefix' => 'credit-note', 'as' => 'credit-note.'], function () {
            Route::get('/', 'CreditNote@edit')->name('edit');
            Route::patch('/', 'CreditNote@update')->name('update');
        });
        Route::group(['prefix' => 'debit-note', 'as' => 'debit-note.'], function () {
            Route::get('/', 'DebitNote@edit')->name('edit');
            Route::patch('/', 'DebitNote@update')->name('update');
        });
    });

    Route::group(['prefix' => 'modals', 'as' => 'modals.'], function () {
        Route::patch('credit-note-templates', 'Modals\CreditNoteTemplates@update')->name('credit-note-templates.update');
        Route::resource('invoices/{invoice}/credits-transactions', 'Modals\InvoiceCreditsTransactions', ['names' => 'invoices.invoice.credits-transactions', 'middleware' => ['dropzone']]);
        Route::resource('credit-notes/{credit_note}/refund-transactions', 'Modals\CreditNoteRefundTransactions', ['names' => 'credit-notes.credit-note.refund-transactions', 'middleware' => ['date.format', 'money', 'dropzone']]);
        Route::resource('debit-notes/{debit_note}/refund-transactions', 'Modals\DebitNoteRefundTransactions', ['names' => 'debit-notes.debit-note.refund-transactions', 'middleware' => ['date.format', 'money', 'dropzone']]);
    });

    Route::resource('credits-transactions', 'CreditsTransactions')->middleware(['money']);
});

<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'payroll' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('payroll', function () {
    // Employees
    Route::resource('employees/{employee}/deduction', 'Employees\EmployeeDeductions');
    Route::resource('employees/{employee}/benefit', 'Employees\EmployeeBenefits');

    Route::get('getType', 'PayCalendars\PayCalendarTypes@getType')->name('pay-calendars.pay.type');

    // Run payroll steps
    Route::group(['prefix' => 'pay-calendars/{payCalendar}/run-payrolls', 'as' => 'pay-calendars.run-payrolls.', 'namespace' => 'RunPayrolls'], function () {
        // Create run payroll page and steps
        Route::get('create', 'RunPayrolls@create')->name('create');

        // 1st step
        Route::get('employees/create', 'Employees@create')->name('employees.create');
        Route::post('employees', 'Employees@store')->name('employees.store');

        // 2nd step
        Route::get('{runPayroll}/variables/create', 'Variables@create')->name('variables.create');
        Route::post('{runPayroll}/variables', 'Variables@store')->name('variables.store');

        // 3rd step
        Route::get('{runPayroll}/pay-slips', 'PaySlips@index')->name('pay-slips.index');
        Route::post('{runPayroll}/pay-slips', 'PaySlips@store')->name('pay-slips.post');
        Route::get('{runPayroll}/pay-slips/employees/{employee}', 'PaySlips@employee')->name('pay-slips.employee');
        Route::get('{runPayroll}/pay-slips/{employee}/print', 'PaySlips@print')->name('pay-slips.print');

        // 4th step.
        Route::get('{runPayroll}/approvals', 'Approvals@edit')->name('approvals.edit');
        Route::post('{runPayroll}/approvals', 'Approvals@update')->name('approvals.update');

        // Run Payroll last step.
        Route::get('{runPayroll}/attachments/edit', 'Attachments@edit')->name('attachments.edit');
        Route::post('{runPayroll}/attachments', 'Attachments@update')->name('attachments.update')->middleware(['dropzone']);
    });

    // Pay Calendars
    Route::get('pay-calendars/{payCalendar}/duplicate', 'PayCalendars\PayCalendars@duplicate')->name('pay-calendars.duplicate');
    Route::post('pay-calendars/import', 'PayCalendars\PayCalendars@import')->name('pay-calendars.import');
    Route::get('pay-calendars/export', 'PayCalendars\PayCalendars@export')->name('pay-calendars.export');
    Route::get('pay-calendars/types', 'PayCalendars\PayCalendars@getTypes')->name('pay-calendars.types');
    Route::get('pay-calendars/{payCalendar}/enable', 'PayCalendars\PayCalendars@enable')->name('pay-calendars.enable');
    Route::get('pay-calendars/{payCalendar}/disable', 'PayCalendars\PayCalendars@disable')->name('pay-calendars.disable');
    Route::resource('pay-calendars', 'PayCalendars\PayCalendars');

    // Run Payroll
    Route::get('run-payrolls/{runPayroll}/pay-slips/edit', 'RunPayrolls\PaySlips@edit')->name('run-payrolls.pay-slips.edit');
    Route::post('run-payrolls/{runPayroll}/pay-slips', 'RunPayrolls\PaySlips@update')->name('run-payrolls.pay-slips.update');

    Route::post('run-payrolls/{runPayroll}/variables/deductions', 'RunPayrolls\Variables@storeDeduction')->name('run-payrolls.variables.deduction.store')->middleware('money');
    Route::post('run-payrolls/{runPayroll}/variables/deductions/{deduction}/remove', 'RunPayrolls\Variables@destroyDeduction')->name('run-payrolls.variables.deduction.destroy')->middleware('money');

    Route::post('run-payrolls/{runPayroll}/variables/benefits', 'RunPayrolls\Variables@storeBenefit')->name('run-payrolls.variables.benefit.store')->middleware(['money']);
    Route::post('run-payrolls/{runPayroll}/variables/benefits/{benefit}/remove', 'RunPayrolls\Variables@destroyBenefit')->name('run-payrolls.variables.benefit.destroy')->middleware('money');

    Route::get('run-payrolls/{runPayroll}/variables/edit', 'RunPayrolls\Variables@edit')->name('run-payrolls.variables.edit');
    Route::post('run-payrolls/{runPayroll}/variables', 'RunPayrolls\Variables@update')->name('run-payrolls.variables.update');

    Route::get('run-payrolls/{runPayroll}/employees/edit', 'RunPayrolls\Employees@edit')->name('run-payrolls.employees.edit');
    Route::post('run-payrolls/{runPayroll}/employees', 'RunPayrolls\Employees@update')->name('run-payrolls.employees.update');

    Route::get('run-payrolls/{runPayroll}/not_approved', 'RunPayrolls\Approvals@not_approved')->name('run-payrolls.not.approved');
    Route::get('run-payrolls/{runPayroll}/employees/{employee}', 'RunPayrolls\Employees@employee')->name('run-payrolls.variables.employee');
    Route::get('run-payrolls/{runPayroll}/duplicate', 'RunPayrolls\RunPayrolls@duplicate')->name('run-payrolls.duplicate');
    Route::post('run-payrolls/import', 'RunPayrolls\RunPayrolls@import')->name('run-payrolls.import');
    Route::get('run-payrolls/export', 'RunPayrolls\RunPayrolls@export')->name('run-payrolls.export');
    Route::get('run-payrolls/statuses', 'RunPayrolls\RunPayrolls@getStatuses')->name('run-payrolls.statuses');
    Route::resource('run-payrolls', 'RunPayrolls\RunPayrolls');

    Route::group(['as' => 'modals.', 'prefix' => 'modals'], function () {
        Route::get('employees/deduction/{deduction}', 'Modals\EmployeeDeductions@show')->name('payroll.employee.deduction.modal.show');
        Route::get('employees/deduction/{deduction}/edit', 'Modals\EmployeeDeductions@edit')->name('payroll.employee.deduction.modal.edit');
        Route::patch('employees/deduction/{deduction}/update', 'Modals\EmployeeDeductions@update')->name('payroll.employee.deduction.modal.update');

        Route::resource('employees/{employee}/deduction', 'Modals\EmployeeDeductions', [
            'names' => [
                'index'   => 'employees.deduction.index',
                'show'    => 'employees.deduction.show',
                'create'  => 'employees.deduction.create',
                'store'   => 'employees.deduction.store',
                'edit'    => 'employees.deduction.edit',
                'destroy' => 'employees.deduction.destroy'
            ]
        ]);

        Route::get('employees/benefit/{benefit}', 'Modals\EmployeeBenefits@show')->name('payroll.employee.benefit.modal.show');
        Route::get('employees/benefit/{benefit}/edit', 'Modals\EmployeeBenefits@edit')->name('payroll.employee.benefit.modal.edit');
        Route::patch('employees/benefit/{benefit}/update', 'Modals\EmployeeBenefits@update')->name('payroll.employee.benefit.modal.update');

        Route::resource('employees/{employee}/benefit', 'Modals\EmployeeBenefits', [
            'names' => [
                'index'   => 'employees.benefit.index',
                'create'  => 'employees.benefit.create',
                'store'   => 'employees.benefit.store',
                'destroy' => 'employees.benefit.destroy'
            ]
        ]);
    });
});

Route::admin('payroll', function () {
    Route::get('payroll/pay-item/create', 'Common\Settings@payItemCreate')->name('settings.pay-item.create');
    Route::post('payroll/pay-item', 'Common\Settings@payItemStore')->name('settings.pay-item.store');
    Route::delete('payroll/pay-item/{pay_item}', 'Common\Settings@payItemDestroy')->name('settings.pay-item.destroy');
    Route::get('payroll/pay-item/{pay_item}/edit', 'Common\Settings@payItemEdit')->name('settings.pay-item.edit');
    Route::patch('payroll/pay-item/{pay_item}/update', 'Common\Settings@payItemUpdate')->name('settings.pay-item.update');

    Route::get('payroll', 'Common\Settings@edit')->name('settings.edit');
    Route::post('payroll', 'Common\Settings@update')->name('settings.update');
}, ['prefix' => 'settings']);

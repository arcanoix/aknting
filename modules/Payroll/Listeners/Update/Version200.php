<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Traits\Contacts;
use App\Traits\Permissions;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version200 extends Listener
{
    use Contacts, Permissions;

    const ALIAS = 'payroll';

    const VERSION = '2.0.0';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->callSeeds();

        $this->updatePermissions();

        $this->addContactType();
    }

    protected function updateDatabase()
    {
        if (DB::table('migrations')->where('migration', '2018_10_02_000000_payroll_v1')->first()) {
            return;
        }

        DB::table('migrations')->insert([
            'id'        => DB::table('migrations')->max('id') + 1,
            'migration' => '2018_10_02_000000_payroll_v1',
            'batch'     => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);

        $this->copyData();
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Payroll\Database\Seeds\Widgets',
        ]);

        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\Payroll\Database\Seeds\Reports',
        ]);
    }

    public function updatePermissions()
    {
        if (Permission::where('name', 'read-payroll-settings')->pluck('id')->first()) {
            return;
        }

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'read-payroll-settings',
            'display_name' => 'Read Payroll Settings',
            'description' => 'Read Payroll Settings',
        ]);

        $attach_permissions[] = Permission::firstOrCreate([
            'name' => 'update-payroll-settings',
            'display_name' => 'Update Payroll Settings',
            'description' => 'Update Payroll Settings',
        ]);

        $detach_permissions = [
            'read-payroll-summary',
            'read-payroll-detailed',
        ];

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($attach_permissions as $permission) {
                $this->attachPermission($role, $permission);
            }

            foreach ($detach_permissions as $permission_name) {
                $this->detachPermission($role, $permission_name);
            }
        }

        $this->attachDefaultModulePermissions('payroll');
    }

    public function copyData()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->integer('contact_id');
        });

        $employees = DB::table('payroll_employees')->cursor();

        foreach ($employees as $employee) {
            $contact_id = DB::table('contacts')->insertGetId([
                'company_id' => $employee->company_id,
                'type' => 'employee',
                'name' => $employee->name,
                'email' => $employee->email,
                'phone' => $employee->social_number,
                'address' => $employee->address,
                'website' => '',
                'currency_code' => $employee->currency_code,
                'enabled' => $employee->enabled,
                'created_at' => $employee->created_at,
                'updated_at' => $employee->updated_at,
                'deleted_at' => $employee->deleted_at,
            ]);

            DB::table('payroll_employees')
                ->where('id', $employee->id)
                ->update([
                    'contact_id' => $contact_id,
                ]);
        }

        Schema::table('payroll_employees', function (Blueprint $table) {
            $connection = Schema::getConnection();
            $d_table = $connection->getDoctrineSchemaManager()->listTableDetails($connection->getTablePrefix() . 'payroll_employees');

            if ($d_table->hasIndex('payroll_employees_company_id_email_deleted_at_unique')) {
                // 1.3 update
                $table->dropUnique('payroll_employees_company_id_email_deleted_at_unique');
            } else {
                // 2.0 install
                $table->dropUnique(['company_id', 'email', 'deleted_at']);
            }

            $table->unique(['company_id', 'contact_id', 'deleted_at']);
        });

        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('address');
            $table->dropColumn('social_number');
            $table->dropColumn('currency_code');
            $table->dropColumn('enabled');
        });
    }

    protected function addContactType()
    {
        setting()->setExtraColumns(['company_id' => company_id()]);
        setting()->forgetAll();
        setting()->load(true);

        $this->addVendorType('employee');

        setting()->forgetAll();
    }
}

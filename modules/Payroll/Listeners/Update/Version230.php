<?php

namespace Modules\Payroll\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished;
use App\Jobs\Install\DownloadModule;
use App\Jobs\Install\InstallModule;
use App\Models\Common\Company;
use App\Models\Common\Widget;
use App\Traits\Jobs;
use App\Traits\Permissions;
use Illuminate\Support\Facades\DB;

class Version230 extends Listener
{
    use Jobs, Permissions;

    const ALIAS = 'payroll';

    const VERSION = '2.3.0';

    public function handle(UpdateFinished $event): void
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->installEmployeesModule();

        $this->copyData();
        $this->updatePermissions();
        $this->migrateWidgets();
    }

    protected function installEmployeesModule(): void
    {
        try {
            $this->dispatch(new DownloadModule('employees', company_id()));

            $this->dispatch(new InstallModule('employees', company_id(), session('locale', app()->getLocale())));
        } catch (\Exception $e) {
            logger($e->getMessage());

            throw $e;
        }
    }

    protected function copyData(): void
    {
        $this->copyPositions();
        $this->copyEmployees();
    }

    protected function copyPositions(): void
    {
        $columns = [
            'id',
            'company_id',
            'name',
            'enabled',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $offset = 0;
        $limit = 500000;
        $builder = DB::table('payroll_positions')
            ->where('company_id', company_id())
            ->select($columns)
            ->limit($limit)
            ->offset($offset);

        while ($builder->cursor()->count()) {
            DB::table('employees_positions')
                ->insertUsing($columns, $builder);

            $offset += $limit;
            $builder->limit($limit)->offset($offset);
        }
    }

    protected function copyEmployees(): void
    {
        $columns = [
            'id',
            'company_id',
            'contact_id',
            'birth_day',
            'gender',
            'position_id',
            'amount',
            'hired_at',
            'bank_account_number',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        $offset = 0;
        $limit = 500000;
        $builder = DB::table('payroll_employees')
            ->where('company_id', company_id())
            ->select($columns)
            ->limit($limit)
            ->offset($offset);

        while ($builder->cursor()->count()) {
            DB::table('employees_employees')
                ->insertUsing($columns, $builder);

            $offset += $limit;
            $builder->limit($limit)->offset($offset);
        }
    }

    protected function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->detachPermissionsFromAdminRoles([
            self::ALIAS . '-positions' => 'c,r,u,d',
            self::ALIAS . '-widgets-total-employees' => 'r',
        ]);
    }

    protected function migrateWidgets()
    {
        company()->users()->each(function ($user) {
            if ($user->cannot('read-admin-panel')) {
                return;
            }

            $dashboard_hr = $user->dashboards()->where([
                'company_id' => company_id(),
                'name' => trans('employees::general.hr'),
                'enabled' => 1,
            ])->first();

            if (!$dashboard_hr) {
                return;
            }

            $sort = $dashboard_hr->widgets()->max('sort');
            $sort = $sort ? $sort + 1 : 1;

            $dashboard_payroll = $user->dashboards()->where([
                'company_id' => company_id(),
                'name' => trans('payroll::general.name'),
                'enabled' => 1,
            ])->first();

            if (!$dashboard_payroll) {
                return;
            }

            $user->dashboards()->detach($dashboard_payroll);

            $dashboard_payroll->widgets()->each(function ($widget) use ($dashboard_payroll, &$sort) {
                if ($widget->class === 'Modules\Payroll\Widgets\TotalEmployees') {
                    return;
                }

                Widget::create([
                    'company_id' => $widget->company_id,
                    'dashboard_id' => $dashboard_payroll->id,
                    'class' => $widget->class,
                    'name' => $widget->name,
                    'sort' => $sort,
                    'settings' => $widget->settings,
                ]);

                $widget->delete();

                $sort++;
            });
        });
    }
}

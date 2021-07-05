<?php

namespace Modules\Payroll\Database\Factories;

use App\Abstracts\Factory;
use Modules\Payroll\Models\PayCalendar\PayCalendar as Model;

class PayCalendar extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public function definition(): array
    {
        $types = Model::getAvailableTypes();

        $monthly = [
            'last_day',
            'specific_day',
        ];

        $weekly = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday',
        ];

        $type = strtolower($this->faker->randomElement($types));
        $pay_day_mode = $this->faker->randomElement($type === 'monthly' ? $monthly : $weekly);
        $pay_day = $pay_day_mode === 'specific_day' ? $this->faker->numberBetween(1, 31) : null;

        return [
            'company_id'   => $this->company->id,
            'name'         => $this->faker->sentence(3),
            'type'         => $type,
            'pay_day_mode' => $pay_day_mode,
            'pay_day'      => $pay_day,
        ];
    }
}

<?php

namespace Modules\Employees\Database\Factories;

use App\Abstracts\Factory as AbstractFactory;
use App\Models\Common\Contact;
use Modules\Employees\Models\Employee as Model;
use Modules\Employees\Models\Position;

class Employee extends AbstractFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public function definition(): array
    {
        $contact_id = Contact::enabled()->inRandomOrder()->pluck('id')->first();
        if (!$contact_id) {
            $contact_id = Contact::factory()->enabled()->create(['type' => 'employee'])->id;
        }

        $position_id = Position::enabled()->inRandomOrder()->pluck('id')->first();
        if (!$position_id) {
            $position_id = Position::factory()->enabled()->create()->id;
        }

        $date = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d');

        return [
            'company_id'          => $this->company->id,
            'birth_day'           => $date,
            'hired_at'            => $date,
            'amount'              => $this->faker->randomFloat(2, 10, 20),
            'contact_id'          => $contact_id,
            'position_id'         => $position_id,
            'gender'              => $this->faker->randomElement(Model::getAvailableGenders()),
            'bank_account_number' => $this->faker->iban(),
        ];
    }
}

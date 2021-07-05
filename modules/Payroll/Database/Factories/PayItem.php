<?php

namespace Modules\Payroll\Database\Factories;

use App\Abstracts\Factory as AbstractFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Payroll\Models\Setting\PayItem as Model;

class PayItem extends AbstractFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public function definition(): array
    {
        return [
            'company_id'         => $this->company->id,
            'pay_type'           => $this->faker->randomElement(['benefit', 'deduction']),
            'pay_item'           => $this->faker->sentence(3),
            'amount_type'        => $this->faker->randomElement(['addition', 'subtraction']),
            'code'               => $this->faker->word,
        ];
    }

    public function benefit(): Factory
    {
        return $this->state([
            'pay_type'    => 'benefit',
            'amount_type' => 'addition',
        ]);
    }

    public function deduction(): Factory
    {
        return $this->state([
            'pay_type'    => 'deduction',
            'amount_type' => 'subtraction',
        ]);
    }
}

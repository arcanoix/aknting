<?php

namespace Modules\Employees\Database\Factories;

use App\Abstracts\Factory as AbstractFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employees\Models\Position as Model;

class Position extends AbstractFactory
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
            'company_id' => $this->company->id,
            'name'       => $this->faker->name,
            'enabled'    => $this->faker->boolean ? 1 : 0,
        ];
    }

    public function enabled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }

    public function disabled(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }
}

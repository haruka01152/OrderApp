<?php

namespace Database\Factories;

use App\Models\Construction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConstructionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Construction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'contract_date' => $this->faker->dateTimeBetween($startDate = '-3 week', $endDate = '+3 week'),
            'construction_date' => $this->faker->dateTimeBetween($startDate = '-2 week', $endDate = '+4 week'),
            'customer_name' => $this->faker->company,
            'construction_name' => $this->faker->realText(15),
            'arrive_status' => '',
            'alert_config' => $this->faker->dateTimeBetween($startDate = '-1 week', $endDate = '+5 week'),
            'status' => 1,
            'order_status' => $this->faker->numberBetween(1,4),
        ];
    }
}

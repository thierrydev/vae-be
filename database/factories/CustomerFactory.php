<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $types = Config::get('constants.CUSTOMER_TYPES');
        $fakedType = $this->faker->randomElement($types);
        $name = $fakedType == $types[0] ? $this->faker->name() : $this->faker->company();
        $email = $fakedType == $types[0] ? $this->faker->safeEmail() : $this->faker->companyEmail();
        return [
            'name' => $name,
            'type' => $fakedType,
            'email' => $email,
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postCode(),
        ];
    }
}

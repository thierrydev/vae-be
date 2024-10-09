<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = Config::get('constants.INVOICE_STATUSES');
        $fakedStatus = $this->faker->randomElement($statuses);

        $billedDate = $this->faker->dateTimeThisYear();
        $paidDate = $this->faker->dateTimeBetween($billedDate, 'now');

        return [
            'customer_id' => Customer::factory(),
            'amount' => $this->faker->numberBetween(100, 20000),
            'status' => $fakedStatus,
            'billed_date' => $billedDate,
            'paid_date' => $fakedStatus == $statuses[1] ? $paidDate : null,
        ];
    }
}

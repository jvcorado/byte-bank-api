<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Transaction;
use App\Enums\TransactionSubtype;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'type' => $this->faker->randomElement(['INCOME', 'EXPENSE']),
            'subtype' => $this->faker->randomElement(TransactionSubtype::values()),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence(3),
            'document' => $this->faker->optional()->numerify('DOC-#####'),
        ];
    }

    /**
     * Indicate that the transaction is an income.
     */
    public function income(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'INCOME',
        ]);
    }

    /**
     * Indicate that the transaction is an expense.
     */
    public function expense(): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => 'EXPENSE',
        ]);
    }
}

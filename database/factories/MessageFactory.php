<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'phone_number' => '09' . str_pad((string)rand(0, 99999999), 8, "0", STR_PAD_LEFT),
            'send_at' => $this->faker->dateTime(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agent>
 */
class AgentFactory extends Factory
{
    public function definition(): array
    {
        $phone = '05'.$this->faker->numerify('## ### ## ##');

        return [
            'name' => $this->faker->name(),
            'phone' => $phone,
            'whatsapp' => $phone,
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}

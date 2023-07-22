<?php

namespace Database\Factories;

use App\Models\Sticker;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StickerFactory extends Factory
{
    protected $model = Sticker::class;

    public function definition(): array
    {
        return [
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'user' => User::factory(),
        ];
    }
}

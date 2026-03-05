<?php

namespace MicroweberPackages\Translation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MicroweberPackages\Translation\Models\TranslationKey;

class TranslationKeyFactory extends Factory
{
    protected $model = TranslationKey::class;

    public function definition(): array
    {
        return [
            'translation_key' => $this->faker->unique()->word . '.' . $this->faker->word,
            'translation_namespace' => $this->faker->optional()->word,
            'translation_group' => $this->faker->word,
        ];
    }
}

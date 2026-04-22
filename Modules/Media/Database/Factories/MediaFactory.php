<?php

declare(strict_types=1);

namespace Modules\Media\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Media\Models\Media;

class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'title' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),
            'media_type' => 'picture',
            'filename' => '{SITE_URL}userfiles/media/default/' . $this->faker->unique()->uuid() . '.jpg',
            'rel_type' => 'content',
            'rel_id' => (string) $this->faker->numberBetween(1, 999),
            'position' => 0,
            'is_synced_to_cdn' => false,
        ];
    }

    public function onCdn(): self
    {
        return $this->state(fn () => [
            'is_synced_to_cdn' => true,
            'cdn_provider' => 's3',
            'cdn_url' => $this->faker->url(),
        ]);
    }
}

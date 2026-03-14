<?php

namespace Database\Factories\Modules\Comments\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'comment_name'    => $this->faker->name(),
            'comment_email'   => $this->faker->safeEmail(),
            'comment_subject' => $this->faker->sentence(4),
            'comment_body'    => $this->faker->paragraph(),
            'rel_type'        => morph_name(Content::class),
            'rel_id'          => Content::factory(),
            'is_moderated'    => false,
            'is_new'          => true,
            'is_spam'         => false,
        ];
    }
}

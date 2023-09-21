<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
*/
class PostFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    $name = fake()->name();
    $slug = str_replace(' ', '-', $name);
    $body = fake()->paragraph(2);
    $excerpt = substr($body, 0, 30);

    return [
      'category_id' => mt_rand(1, 10),
      'user_id' => mt_rand(1, 10),
      'title' => fake()->sentence(4),
      ...compact('slug', 'body', 'excerpt'),
    ];
  }
}

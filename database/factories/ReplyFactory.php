<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
* @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
*/
class ReplyFactory extends Factory {
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array {
    return [
      'user_id'    => mt_rand(1, 10),
      'comment_id' => mt_rand(1, 30),
      'body'       => fake()->sentence(2),
    ];
  }
}

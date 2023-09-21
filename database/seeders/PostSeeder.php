<?php

namespace Database\Seeders;

use App\Models\ {
  Comment,
  Like,
  Post,
};
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder {
  /**
   * Seed the posts table.
   */
  public function run(): void {
    Post::factory(30)->create();

    for ($i = 0; $i < 50; $i++) {
      Like::insert([
        'user_id' => mt_rand(1, 10),
        'post_id' => mt_rand(1, 10),
      ]);

      Comment::insert([
        'user_id' => mt_rand(1, 10),
        'post_id' => mt_rand(1, 10),
        'body' => fake()->paragraph(2),
      ]);
    }
  }
}

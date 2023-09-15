<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder {
  /**
   * Seed the posts table.
   */
  public function run(): void {
    Post::factory(30)->create();
  }
}

<?php

namespace Database\Seeders;

use App\Models\ {
  Comment,
  Like,
  Post,
  Reply
};
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder {
  /**
   * Seed the posts table.
   */
  public function run(): void {
    Post   ::factory(20)->create();
    Like   ::factory(30)->create();
    Comment::factory(30)->create();
    Reply  ::factory(30)->create();
  }
}

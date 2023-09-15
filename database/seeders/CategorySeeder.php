<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder {
  /**
   * Seed the categories table.
   */
  public function run(): void {
    Category::factory(10)->create();
  }
}

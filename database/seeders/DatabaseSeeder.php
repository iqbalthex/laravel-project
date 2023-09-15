<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
  /**
   * Seed the application's database.
   */
  public function run(): void {
    $this->run([
      UserSeeder::class,
      PostSeeder::class,
      CategorySeeder::class,
    ]);
  }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
  /**
   * Seed the users table.
   */
  public function run(): void {
    User::factory()->create([
      'name' => 'Iqbal',
      'email' => 'iqbal@gmail.com',
      'password' => bcrypt('123'),
      'phone' => '6281231360159',
      'role' => 'admin',
    ]);

    User::factory(10)->create();
  }
}

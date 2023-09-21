<?php

namespace Database\Seeders;

use App\Models\ {
  Follower,
  User,
};
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

    for ($i = 0; $i < 10; $i++) {
      Follower::insert([
        'user_id'     => mt_rand(1, 10),
        'follower_id' => mt_rand(1, 10),
      ]);
    }
  }
}

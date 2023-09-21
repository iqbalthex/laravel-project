<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ {
  Factories\HasFactory,
  Relations\BelongsToMany,
  Relations\HasMany,
};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'email',
    'password',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function posts(): HasMany{
    return $this->hasMany(Post::class);
  }

  /*
  SELECT name -- Get name of user with id IN {ex-result}
  FROM users
  WHERE id IN (
    -- Get follower_ids first. (ex-result: [3, 5, 6, 8])
    SELECT follower_id
    FROM users
    JOIN followers
      ON users.id = followers.user_id
    WHERE users.id = 1
  )
  */
  public function followers(): BelongsToMany {
    return $this
      ->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
      ->select(['id', 'name']);
  }
}

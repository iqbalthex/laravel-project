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

  /**
   * Get the posts for the user.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function posts(): HasMany{
    return $this->hasMany(Post::class);
  }

  /**
   * The followers that belong to the user.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function followers(): BelongsToMany {
    return $this
      ->belongsToMany(User::class, 'followers', 'user_id', 'follower_id')
      ->select(['id', 'name']);
  }
}

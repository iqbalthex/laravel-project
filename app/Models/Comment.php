<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ {
  Factories\HasFactory,
  Model,
  Relations\BelongsTo,
  Relations\HasMany,
};

class Comment extends Model {
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array<int, string>
   */
  protected $guarded = ['id'];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'date:Y-m-d h:i:s',
    'updated_at' => 'date:Y-m-d h:i:s',
  ];

  /**
   * Get the user that owns the comment.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo {
    return $this
      ->belongsTo(User::class)
      ->select(['id', 'name']);
  }

  /**
   * Get the replies for the comment.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function replies(): HasMany {
    return $this->hasMany(Reply::class);
  }
}

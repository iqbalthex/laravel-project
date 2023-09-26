<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ {
  Factories\HasFactory,
  Model,
  Relations\BelongsTo,
  Relations\HasMany,
  SoftDeletes,
};

class Post extends Model {
  use HasFactory, SoftDeletes;

  protected $guarded = ['id'];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function category(): BelongsTo {
    return $this
      ->belongsTo(Category::class)
      ->select(['id', 'name']);
  }

  public function user(): BelongsTo {
    return $this
      ->belongsTo(User::class)
      ->select(['id', 'name']);
  }

  public function likes(): HasMany {
    return $this
      ->hasMany(Like::class)
      ->select(['user_id', 'post_id']);
  }
  
  public function comments(): HasMany {
    return $this
      ->HasMany(Comment::class)
      ->orderByDesc('created_at')
      ->limit(15);
  }
}

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

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array<int, string>
   */
  protected $guarded = ['id'];

  /**
   * Get the route key for the model.
   *
   * @return string
   */
  public function getRouteKeyName(): string {
    return 'slug';
  }

  /**
   * Get the category that owns the post.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function category(): BelongsTo {
    return $this
      ->belongsTo(Category::class)
      ->select(['id', 'name']);
  }

  /**
   * Get the user that owns the post.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo {
    return $this
      ->belongsTo(User::class)
      ->select(['id', 'name']);
  }

  /**
   * Get the likes for the post.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function likes(): HasMany {
    return $this
      ->hasMany(Like::class)
      ->select(['user_id', 'post_id']);
  }
  
  /**
   * Get the comments for the post.
   *
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function comments(): HasMany {
    return $this
      ->HasMany(Comment::class)
      ->orderByDesc('created_at')
      ->limit(15);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ {
  Factories\HasFactory,
  Model,
  Relations\BelongsTo,
};

class Post extends Model {
  use HasFactory;

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
}

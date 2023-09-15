<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ {
  Factories\HasFactory,
  Model,
  Relations\HasMany,
};

class Category extends Model {
  use HasFactory;

  protected $guarded = ['id'];

  public function posts(): HasMany {
    return $this->hasMany(Post::class);
  }
}

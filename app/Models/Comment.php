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

  protected $guarded = ['id'];
  protected $casts = [
    'created_at' => 'date:Y-m-d h:i:s',
    'updated_at' => 'date:Y-m-d h:i:s',
  ];

  public function user(): BelongsTo {
    return $this
      ->belongsTo(User::class)
      ->select(['id', 'name']);
  }

  public function replies(): HasMany {
    return $this->hasMany(Reply::class);
  }
}

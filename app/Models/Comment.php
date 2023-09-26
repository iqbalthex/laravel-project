<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
  use HasFactory;

  protected $guarded = ['id'];
  protected $casts = [
    'created_at' => 'date:Y-m-d h:i:s',
    'updated_at' => 'date:Y-m-d h:i:s',
  ];

  public function user() {
    return $this
      ->belongsTo(User::class)
      ->select(['id', 'name']);
  }
}

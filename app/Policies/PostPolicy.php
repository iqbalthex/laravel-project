<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy {
  public function before($user) {
    if (!in_array($user->role, ['admin', 'member'])) {
      return false;
    }

    if ($user->banned) {
      return false;
    }
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool {
    return true;
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Post $post): bool {
    return $user->id === $post->user_id;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Post $post): bool {
    return $user->id === $post->user_id;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Post $post): bool {
    //
  }
}

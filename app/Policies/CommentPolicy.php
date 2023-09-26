<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy {
  static int $i = 0;

  public function before($user) {
    if (!in_array($user->role, ['admin', 'member'])) {
      return false;
    }

    if ($user->banned) {
      return false;
    }
  }

  /**
   * Determine whether the user can create comment.
   */
  public function create(User $user): bool {
    // limit 3 comment per post per day
    return true;
  }

  /**
   * Determine whether the user can reply comment.
   */
  public function reply(User $user, Comment $comment): bool {
    return $user->id !== $comment->user_id;
  }

  /**
   * Determine whether the user can update the comment.
   */
  public function update(User $user, Comment $comment): bool {
    return $user->id === $comment->user_id;
  }

  /**
   * Determine whether the user can delete the comment.
   */
  public function delete(User $user, Comment $comment): bool {
    return $user->id === $comment->user_id;
  }
}

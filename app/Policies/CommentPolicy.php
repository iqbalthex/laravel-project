<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy {
  /**
   * Perform pre-authorization checks.
   *
   * @param   App\Models\User  $user
   * @return  bool|void
   */
  public function before(User $user) {
    if (!in_array($user->role, ['admin', 'member'])) {
      return false;
    }

    if ($user->banned) {
      return false;
    }
  }

  /**
   * Determine whether the user can create comment.
   *
   * @param   App\Models\User  $user
   * @return  bool
   */
  public function create(User $user): bool {
    // limit 3 comment per post per day
    return true;
  }

  /**
   * Determine whether the user can reply comment.
   *
   * @param   App\Models\User     $user
   * @param   App\Models\Comment  $comment
   * @return  bool
   */
  public function reply(User $user, Comment $comment): bool {
    return $user->id !== $comment->user_id;
  }

  /**
   * Determine whether the user can update or delete the comment.
   *
   * @param   App\Models\User     $user
   * @param   App\Models\Comment  $comment
   * @return  bool
   */
  public function modify(User $user, Comment $comment): bool {
    return $user->id === $comment->user_id;
  }
}

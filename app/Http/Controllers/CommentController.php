<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\ {
  Response,
  Request,
};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\ {
  DB,
  Log,
  Validator,
};

class CommentController extends Controller {
  /**
   * Store a new comment in storage.
   *
   * @param   Illuminate\Http\Request  $request
   * @return  Illuminate\Http\Response
   */
  public function store(Request &$request): Response {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'post_id' => ['required', 'integer'],
      'body'    => ['required', 'string'],
    ]);

    // Return error when fails to validate the request.
    if ($validator->stopOnFirstFailure()->fails()) {
      return response([
        'error' => $validator->errors()->first()
      ], 400);
    }

    DB::beginTransaction();
    try {
      $comment = Comment::create($validator->validated());
      DB::commit();

      return response([
        'comments' => $this->fetchComments($comment->user_id, $comment->post_id),
      ], 201);

    } catch (\Exception $e) {
      DB::rollBack();

      $error = $e->getMessage();
      return response(compact('error'), 500);

    } finally {
      $comment->makeHidden(['created_at', 'updated_at', 'created', 'updated']);

      Log::info('Comment created', compact('comment'));
    }
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Comment $comment) {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Comment $comment) {
    //
  }

  /**
   * Get the latest 15 comments.
   *
   * @param   int  $user_id
   * @param   int  $post_id
   * @return  Illuminate\Database\Eloquent\Collection  $comments
   */
  private function fetchComments(int $user_id, int $post_id): Collection {
    $comments = Comment
      ::with(['user'])
      ->where('post_id', $post_id)
      ->latest('created_at')
      ->limit(15)
      ->get();

    $user = User::find($user_id);

    foreach ($comments as $comment) {
      $comment->createdStr = $comment->created_at->format('Y-m-d h:i');
      $comment->updatedStr = $comment->updated_at->diffForHumans();

      $comment->canReply  = $user->can('reply',  $comment);
      $comment->canModify = $user->can('modify', $comment);
    }

    return $comments;
  }
}

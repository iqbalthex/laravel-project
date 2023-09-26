<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\ { Response, Request };
use Illuminate\Support\Facades\ {
  DB,
  Log,
  Validator,
};

class CommentController extends Controller {
  /**
   * Store a newly created resource in storage.
   */
  public function store(Request &$request): Response {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'post_id' => ['required', 'integer'],
      'body'    => ['required', 'string'],
    ]);

    if ($validator->stopOnFirstFailure()->fails()) return response([
      'error' => $validator->errors()->first(),
    ], 400);

    DB::beginTransaction();
    try {
      $comment = Comment::create($validator->validated());
      DB::commit();

      return response([
        'comments' => $this->refetchComments($comment->post_id),
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

  private function refetchComments($post_id) {
    $comments = Comment
      ::with(['user'])
      ->where('post_id', $post_id)
      ->latest('created_at')
      ->limit(15)
      ->get();

    foreach ($comments as &$comment) {
      $comment->created = $comment->created_at->format('Y-m-d h:i');
      $comment->updated = $comment->updated_at->diffForHumans();
      // $comment->canUpdate = $comment->user->can('update');
    }

    return $comments;
  }
}

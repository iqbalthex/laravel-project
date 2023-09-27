<?php

namespace App\Http\Controllers;

use App\Models\ {
  Category,
  Comment,
  Like,
  Post,
};
use App\Http\Requests\PostRequest;
use Illuminate\Http\ {
  RedirectResponse,
  Request,
};
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PostController extends Controller {
  /**
   * Display a list of posts.
   *
   * @return Illuminate\View\View
   */
  public function index(): View {
    $posts = Post
      ::with(['category', 'user.followers'])
      ->select(['category_id', 'user_id', 'id', 'title', 'body', 'slug', 'excerpt'])
      ->latest('posts.updated_at')
      ->simplePaginate(10)
      ->withQueryString();

    $userId = auth()->user()->id;

    foreach ($posts as &$post) {
      // Is authenticated user like the post.
      $post->liked = in_array($userId,
        $post->likes
          ->map(fn ($like) => $like->user_id)
          ->toArray()
      );
    };

    return view('posts.index', compact(
      'posts',
    ));
  }

  /**
   * Show the form for creating a new post.
   *
   * @return Illuminate\View\View
   */
  public function create(): View {
    $categories = Category::all();

    return view('posts.create', compact(
      'categories',
    ));
  }

  /**
   * Store a new post in storage.
   *
   * @param   Illuminate\Http\Request  $request
   * @return  Illuminate\Http\RedirectResponse
   */
  public function store(Request $request): RedirectResponse {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'title' => ['required', 'string'],
      'slug'  => ['required'],
    ]);

    // Redirect and return error alert when fails to validate the request.
    if ($validator->stopOnFirstFailure()->fails()) {
      return back()->with('alert', $this->failAlert($validator->errors()->first()));
    }

    $data = $request->only([
      'category_id',
      'user_id',
      'title',
      'slug',
      'body',
    ]);

    $postCreated = Post::create([
      ...$data,
      'excerpt' => substr($data['body'], 0, 30),
    ]);

    if ($postCreated) {
      return back()->with('alert', $this->successAlert('Create post success.'));
    }

    return back()->with('alert', $this->failAlert('Create post failed.'));
  }

  /**
   * Display the specified post.
   *
   * @param   App\Models\Post  $post
   * @return  Illuminate\View\View
   */
  public function show(Post $post): View {
    // Eager load the relations.
    $post->load([
      'comments.user',
      'user.posts' => fn ($query) => $query
        ->whereNot('id', $post->id)
        ->limit(5),
    ]);

    // Recent posts.
    $recentPosts = Post::with('user.posts')
      ->whereNot('user_id', $post->user_id)
      ->limit(5)
      ->get();

    // Get 5 posts randomly.
    $otherPosts = Post::with('user.posts')
      ->whereNot('user_id', $post->user_id)
      ->whereNotIn('id', $recentPosts->map(fn ($post) => $post->id))
      ->inRandomOrder()
      ->latest('updated_at')
      ->limit(5)
      ->get();

    return view('posts.show', compact(
      'post', 'recentPosts', 'otherPosts',
    ));
  }

  /**
   * Show the form for editing the specified post.
   *
   * @param   App\Models\Post  $post
   * @return  Illuminate\View\View
   */
  public function edit(Post $post): View {
    $categories = Category::all();

    $post->image = $post->image ?? 'ru.jpg';

    return view('posts.edit', compact(
      'post',
      'categories',
    ));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param   Illuminate\Http\Request  $request
   * @param   App\Models\Post          $post
   * @return  Illuminate\Http\RedirectResponse
   */
  public function update(Request $request, Post $post): RedirectResponse {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'title' => ['required', 'string'],
      'slug'  => ['required'],
      // 'image' => [],
      // 'old-image' => [],
    ]);

    // Redirect and return error alert when fails to validate the request.
    if ($validator->stopOnFirstFailure()->fails()) {
      return back()->with('alert', $this->failAlert($validator->errors()->first()));
    }

    // $request->image = $request->image ?? $request->old_image;

    $data = $request->only([
      'category_id',
      'user_id',
      'title',
      'slug',
      'body',
    ]);

    $postUpdated = $post->update([
      ...$data,
      'excerpt' => substr($data['body'], 0, 30),
    ]);

    if ($postUpdated) {
      return back()->with('alert', $this->successAlert('Create post success.'));
    }

    return back()->with('alert', $this->failAlert('Create post failed.'));
  }

  /**
   * Remove the specified post from storage.
   *
   * @param   App\Models\Post  $post
   * @return  Illuminate\Http\RedirectResponse
   */
  public function destroy(Post $post): RedirectResponse {
    $postDeleted = $post->delete();

    if ($postDeleted) {
      return back()->with('alert', $this->successAlert('Delete post success.'));
    }

    return back()->with('alert', $this->failAlert('Delete post failed.'));
  }

  /**
   * Add like to specified post.
   *
   * @param   Illuminate\Http\Request  $request
   * @return  Illuminate\Http\RedirectResponse
   */
  public function like(Request $request): RedirectResponse {
    try {
      Like::create($request->only([ 'user_id', 'post_id' ]));

      $likeCount = $this->countLike($request->post_id);

      return response(compact('likeCount'), 200);

    } catch (\Exception $err) {
      return response()->noContent(500);
    }    
  }

  /**
   * Remove like from specified post.
   *
   * @param   Illuminate\Http\Request  $request
   * @return  Illuminate\Http\RedirectResponse
   */
  public function unlike(Request $request): RedirectResponse {
    try {
      Like::where($request->only([ 'user_id', 'post_id' ]))
        ->delete();

      $likeCount = $this->countLike($request->post_id);

      return response(compact('likeCount'), 200);

    } catch (\Exception $err) {
      return response()->noContent(500);
    }
  }

  /**
   * Count like from specified post.
   *
   * @param   int  $postId
   * @return  int
   */
  private function countLike(int $postId): int {
    return Like::where('post_id', $postId)->count();
  }
}

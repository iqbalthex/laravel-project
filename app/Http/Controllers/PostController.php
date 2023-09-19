<?php

namespace App\Http\Controllers;

use App\Models\ {
  Category,
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
   * Display a listing of the resource.
   */
  public function index(): View {
    $posts = Post
      ::with('user', 'category')
      ->select(['category_id', 'user_id', 'id', 'title', 'body', 'slug'])
      ->latest('posts.updated_at')
      ->simplePaginate(10)
      ->withQueryString();

    return view('posts.index', compact(
      'posts',
    ));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View {
    $categories = Category::all();

    return view('posts.create', compact(
      'categories',
    ));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'title' => ['required', 'string'],
      'slug'  => ['required'],
    ]);

    if ($validator->stopOnFirstFailure()->fails()) {
      return back()->with('alert', $this
        ->failAlert($validator->errors()->first())
      );
    }

    $postCreated = Post::create($request->only([
      'category_id',
      'user_id',
      'title',
      'slug',
      'body',
    ]));

    if (!$postCreated) {
      return back()->with('alert', $this->failAlert('Create post failed.'));
    }

    return back()->with('alert', $this->successAlert('Create post success.'));
  }

  /**
   * Display the specified resource.
   */
  public function show(Post $post): View {
    $post->user->load([
      'posts' => fn ($query) => $query
        ->whereNot('id', $post->id)
        ->limit(5),
    ]);

    $recentPosts = Post::with('user.posts')
      ->whereNot('user_id', $post->user_id)
      ->inRandomOrder()
      ->limit(5)
      ->get();

    $otherPosts = Post::with('user.posts')
      ->whereNot('user_id', $post->user_id)
      ->whereNotIn('id', $recentPosts->map(fn ($post) => $post->id))
      ->latest('updated_at')
      ->limit(5)
      ->get();

    // $popularPosts

    return view('posts.show', compact(
      'post', 'recentPosts', 'otherPosts',
    ));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Post $post): View {
    return view('posts.edit');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Post $post): RedirectResponse {
    return back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Post $post): RedirectResponse {
    return back();
  }
}

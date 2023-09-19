<?php

namespace App\Http\Controllers;

use App\Models\ {
  Category,
  Post,
};
use Illuminate\Http\ {
  RedirectResponse,
  Request,
};
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
    return back();
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

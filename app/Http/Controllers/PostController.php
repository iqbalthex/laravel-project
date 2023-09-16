<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
      ::simplePaginate(10)
      ->withQueryString();

    return view('posts.index', compact(
      'posts',
    ));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View {
    return view('posts.create');
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

    $otherPosts = Post
      ::whereNot('user_id', $post->user_id)
      ->latest('updated_at')
      ->limit(5)
      ->get();

    return view('posts.show', compact(
      'post', 'otherPosts',
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

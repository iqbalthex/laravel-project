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
      ::with(['category', 'user.followers'])
      ->select(['category_id', 'user_id', 'id', 'title', 'body', 'slug', 'excerpt'])
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

    if ($validator->stopOnFirstFailure()->fails()) return back()
      ->with('alert', $this
        ->failAlert($validator->errors()->first())
      );

    $data = $request->only([
      'category_id',
      'user_id',
      'title',
      'slug',
      'body',
    ]);

    $postCreated = Post::create([
      ...$data
      'excerpt' => substr($data['body'], 0, 30),
    ]);

    if ($postCreated) {
      return back()->with('alert', $this->successAlert('Create post success.'));
    }

    return back()->with('alert', $this->failAlert('Create post failed.'));
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
    $categories = Category::all();

    $post->image = $post->image ?? 'ru.jpg';

    return view('posts.edit', compact(
      'post',
      'categories',
    ));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Post $post): RedirectResponse {
    $validator = Validator::make($request->all(), [
      'user_id' => ['required', 'integer'],
      'title' => ['required', 'string'],
      'slug'  => ['required'],
      // 'image' => [],
      // 'old-image' => [],
    ]);

    if ($validator->stopOnFirstFailure()->fails()) return back()
      ->with('alert', $this
        ->failAlert($validator->errors()->first())
      );

    // $request->image = $request->image ?? $request->old_image;

    $data = $request->only([
      'category_id',
      'user_id',
      'title',
      'slug',
      'body',
    ]);

    $postUpdated = $post->update([
      ...$data
      'excerpt' => substr($data['body'], 0, 30),
    ]);

    if ($postUpdated) {
      return back()->with('alert', $this->successAlert('Create post success.'));
    }

    return back()->with('alert', $this->failAlert('Create post failed.'));
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Post $post): RedirectResponse {
    $postDeleted = $post->delete();

    if ($postDeleted) {
      return back()->with('alert', $this->successAlert('Delete post success.'));
    }

    return back()->with('alert', $this->failAlert('Delete post failed.'));
  }

  /**
   * Display a listing of posts wrote by user.
   */
  public function myPosts(): View {
    $posts = Post
      ::with('category')
      ->select(['category_id', 'id', 'title', 'body', 'slug'])
      ->latest('posts.updated_at')
      ->simplePaginate(10)
      ->withQueryString();

    return view('posts.my-posts', compact(
      'posts',
    ));
  }
}

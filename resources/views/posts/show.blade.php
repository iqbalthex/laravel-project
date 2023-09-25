@extends('layouts.main')

@section('content')

<div class="container">
  <div class="row g-3">
    <div class="col-md-8">
      <article class="blog-post">
        <div class="d-flex justify-content-between">
          <div>
            <h2 class="display-5 mb-1">{{ $post->title }}</h2>
            <p class="blog-post-meta">
              Last updated at {{ $post->updated_at->diffForHumans() }} by
              @if ($post->user->id === auth()->user()->id)
                you
              @else
                <a href="{{ route('posts.index', ['author' => $post->user->id]) }}">
                  {{ $post->user->name }}
                </a>
              @endif
            </p>
          </div>
          <div class="d-flex flex-column gap-2">
            @php($actionBtn = 'action-btn btn d-inline-flex align-items-center')
            @can('update', $post)
              <a href="{{ route('posts.edit', $post->slug) }}?from=show" class="{{ $actionBtn }} btn-primary">
                Edit
              </a>
            @endcan

            @can('delete', $post)
              <form action="{{ route('posts.destroy', $post->slug) }}" method="post">
                @csrf
                @method('DELETE')
                <button type="submit" class="{{ $actionBtn }} btn-danger" onclick="return confirm('Are you sure?')">
                  Delete
                </button>
              </form>
            @endcan
          </div>
        </div>

        {{ $post->body }}
      </article>

      <div id="comments" class="mt-4 p-1 bg-light bg-gradient border">
        <h5 class="mb-2">Comments</h5>
        <ul class="list-unstyled">
        @foreach ($post->comments as $comment)
          <li class="border px-2 pt-1 mb-2">
            <h6>
              {{ $comment->user->name }}
              <i class="fw-normal">({{ $comment->created_at?->diffForHumans() }})</i>
            </h6>
            <p class="mb-2">{{ $comment->body }}</p>
          </li>
        @endforeach
        </ul>
      </div>
    </div>

    <div class="col-md-4">
      <div class="position-sticky" style="top: 5rem;">
        <div>
        @if (count($post->user->posts) > 0)
          <div>
            <h4 class="fst-italic">
              Recents by
              @if ($post->user->id === auth()->user()->id)
                you
              @else
                <a href="{{ route('posts.index', ['author' => $post->user->id]) }}">
                  {{ $post->user->name }}
                </a>
              @endif
            </h4>
            <div id="user-posts" class="carousel slide mb-6" data-bs-ride="carousel">
              <div class="carousel-indicators">
              @for ($i = 0; $i < count($post->user->posts); $i++)
                @if ($i === 0)
                  <button type="button" data-bs-target="#user-posts" data-bs-slide-to="0" class="active" aria-current="true"></button>
                @else
                  <button type="button" data-bs-target="#user-posts" data-bs-slide-to="{{ $i }}"></button>
                @endif
              @endfor
              </div>
              <div class="carousel-inner">
              @foreach ($post->user->posts as $userPost)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}"
                  style="background: rgb({{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }});"
                  data-bs-interval="{{ mt_rand(1500, 3000) }}">
                  <svg width="100%"></svg>
                  <div class="container pt-4">
                    <div class="carousel-caption text-start">
                      <h3>{{ $userPost->title }}</h3>
                      <a class="btn btn-primary" href="{{ route('posts.show', $userPost->slug) }}">
                        <small>Read more...</small>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#user-posts" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#user-posts" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        @endif

          <div class="mt-3">
            <h4 class="fst-italic">Recent Posts</h4>
            <div id="other-posts" class="carousel slide mb-6" data-bs-ride="carousel">
              <div class="carousel-indicators">
              @for ($i = 0; $i < count($otherPosts); $i++)
                @if ($i === 0)
                  <button type="button" data-bs-target="#other-posts" data-bs-slide-to="0" class="active" aria-current="true"></button>
                @else
                  <button type="button" data-bs-target="#other-posts" data-bs-slide-to="{{ $i }}"></button>
                @endif
              @endfor
              </div>
              <div class="carousel-inner">
              @foreach ($recentPosts as $recentPost)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}"
                  style="background: rgb({{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }});"
                  data-bs-interval="{{ mt_rand(1500, 3000) }}">
                  <svg width="100%"></svg>
                  <div class="container pt-4">
                    <div class="carousel-caption text-start">
                      <h3>{{ $recentPost->title }}</h3>
                      <span class="d-block mb-2">
                        <small>
                          by <a href="{{ $recentPost->user->id }}" class="link-light">
                            {{ $recentPost->user->name }}
                          </a>
                        </small>
                      </span>
                      <a class="btn btn-primary" href="{{ route('posts.show', $recentPost->slug) }}">
                        <small>Read more...</small>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#other-posts" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#other-posts" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>

          <div class="mt-3">
            <h4 class="fst-italic">Other Posts</h4>
            <div id="other-posts" class="carousel slide mb-6" data-bs-ride="carousel">
              <div class="carousel-indicators">
              @for ($i = 0; $i < count($otherPosts); $i++)
                @if ($i === 0)
                  <button type="button" data-bs-target="#other-posts" data-bs-slide-to="0" class="active" aria-current="true"></button>
                @else
                  <button type="button" data-bs-target="#other-posts" data-bs-slide-to="{{ $i }}"></button>
                @endif
              @endfor
              </div>
              <div class="carousel-inner">
              @foreach ($otherPosts as $otherPost)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}"
                  style="background: rgb({{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }}, {{ mt_rand(0, 255) }});"
                  data-bs-interval="{{ mt_rand(1500, 3000) }}">
                  <svg width="100%"></svg>
                  <div class="container pt-4">
                    <div class="carousel-caption text-start">
                      <h3>{{ $otherPost->title }}</h3>
                      <span class="d-block mb-2">
                        <small>
                          by <a href="{{ $otherPost->user->id }}" class="link-light">
                            {{ $otherPost->user->name }}
                          </a>
                        </small>
                      </span>
                      <a class="btn btn-primary" href="{{ route('posts.show', $otherPost->slug) }}">
                        <small>Read more...</small>
                      </a>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#other-posts" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#other-posts" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

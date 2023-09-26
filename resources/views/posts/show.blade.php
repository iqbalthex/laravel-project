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

      <div id="comments" class="mt-4 p-1 pt-2 bg-light bg-gradient border">
        <h5 class="mb-2">Comments</h5>
        <div class="mb-2">
          <textarea class="form-control" placeholder="Write a comment..." oninput="typing()" data-comment-input></textarea>
          <button class="btn btn-primary mt-2 mb-3" onclick="storeComment()" disabled data-submit-btn>Comment</button>
          <button class="btn btn-danger mt-2 mb-3" onclick="cancelComment()" disabled data-cancel-btn>Cancel</button>
        </div>
        <ul class="list-unstyled" data-comments>
        @foreach ($post->comments as $comment)
          @php($updated = ($comment->created_at != $comment->updated_at)
            ? "(Updated {$comment->updated_at->diffForHumans()})"
            : '')
          <li class="border px-2 pt-1 mb-2" data-comment-id="{{ $comment->id }}">
            <h6 class="d-flex justify-content-between">
              <span>
                {{ $comment->user->name }}
                <i class="fw-normal">{{ $updated }}</i>
              </span>
              <span class="fw-normal">
                {{ $comment->created_at->format('Y-m-d h:i') }}
              </span>
            </h6>
            <p class="mb-2">{{ $comment->body }}</p>

          @can('reply', $comment)
            <button class="btn btn-secondary mb-2" onclick="createReply(this)">Reply</button>
          @elsecan('update', $comment)
            <button class="btn btn-primary mb-2" onclick="editComment(this)">Edit</button>
            <button class="btn btn-danger mb-2" onclick="destroyComment(this)">Delete</button>
          @endcan

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

@push('scripts')
<script>

let commentData = @json($post->comments);
commentData.sort((c1, c2) => (c1.created_at - c2.created_at));

const commentInput = $data('comment-input');
const submitBtn = $data('submit-btn');
const cancelBtn = $data('cancel-btn');
let typingTimeout;

function typing() {
  clearTimeout(typingTimeout);

  typingTimeout = setTimeout(() => {
    if (commentInput.value.length > 0) {
      enable(submitBtn);
      enable(cancelBtn);
      return;
    }

    disable(submitBtn);
    disable(cancelBtn);
  }, 400);
}


const commentWrapper = $data('comments');
const loading = document.createElement('li');

function storeComment() {
  const tempCommentData = commentData;
  const token = $('input[name="_token"]');
  const headers = {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': token.value,
  }
  const body = JSON.stringify({
    user_id: {{ auth()->user()->id }},
    post_id: {{ $post->id }},
    body: commentInput.value,
  });

  loading.innerHTML = '<li class="border px-2 pt-1 mb-2">Loading...</li>';
  commentWrapper.prepend(loading);

  disable(commentInput);
  disable(submitBtn);

  fetch("{{ route('comments.store') }}", {
    headers, method: 'POST', body,
  }).then(res => res.json())
    .then(json => {
      commentData = json.comments;
      commentInput.value = '';
      disable(cancelBtn);
    })
    .catch(err => {
      console.error(err);
      commentData = tempCommentData;
      commentWrapper.removeChild(loading);

      enable(submitBtn);
    })
    .finally(() => {
      enable(commentInput);

      renderComment();
    });
}

function cancelComment() {
  commentInput.value = '';

  disable(submitBtn);
  disable(cancelBtn);
}

function renderComment() {
  commentData.sort((c1, c2) => {
    const c1Time = new Date(c1.created_at);
    const c2Time = new Date(c2.created_at);
    return c2Time.getTime() - c1Time.getTime();
  });

  let content = '';
  commentData.forEach(comment => {
    const updated = (comment.created_at !== comment.updated_at)
      ? `(Updated ${comment.updated})`
      : '';

    content += `<li class="border px-2 pt-1 mb-2">
      <h6 class="d-flex justify-content-between">
        <span>
          ${comment.user.name}
          <i class="fw-normal">${updated}</i>
        </span>
        <span class="fw-normal">${comment.created}</span>
      </h6>
      <p class="mb-2">${comment.body}</p>
    </li>`;
  });

  commentWrapper.innerHTML = content;
}

function createReply(btn) {
  
}

function editComment(btn) {
  
}

function destroyComment(btn) {
  
}

</script>
@endpush

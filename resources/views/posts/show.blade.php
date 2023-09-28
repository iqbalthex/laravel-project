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
          <textarea class="form-control" placeholder="Write a comment..." oninput="typing(this)" data-comment-input></textarea>
          <button class="btn btn-primary mt-2 mb-3 px-2 py-0" onclick="storeComment(this)" disabled data-submit-btn>Comment</button>
          <button class="btn btn-secondary mt-2 mb-3 px-2 py-0" onclick="cancelComment(this)" disabled data-cancel-btn>Cancel</button>
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
            <button class="btn btn-dark mb-2 px-2 py-0" onclick="createReply(this)">Reply</button>
            <button class="btn btn-primary mb-2 px-2 py-0 d-none" onclick="storeReply(this)" disabled data-reply-btn>Reply</button>
            <button class="btn btn-secondary mb-2 px-2 py-0 d-none" onclick="cancelReply(this)" disabled data-cancel-reply-btn>Cancel</button>
          @endcan

          @can('modify', $comment)
            <button class="btn btn-primary mb-2 px-2 py-0" onclick="editComment(this)" data-edit-btn>Edit</button>
            <button class="btn btn-primary mb-2 px-2 py-0 d-none" onclick="updateComment(this)" disabled data-submit-btn>Save</button>
            <button class="btn btn-secondary mb-2 px-2 py-0 d-none" onclick="cancelEdit(this)" disabled data-cancel-btn>Cancel</button>

            <button class="btn btn-danger mb-2 px-2 py-0" onclick="deleteComment(this)" data-delete-btn>Delete</button>
            <button class="btn btn-danger mb-2 px-2 py-0 d-none" onclick="destroyComment(this)" data-destroy-btn>Delete this comment</button>
            <button class="btn btn-secondary mb-2 px-2 py-0 d-none" onclick="cancelDelete(this)" data-cancel-delete-btn>Cancel</button>
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

<template id="comment-reply">
  <p>tes</p>
</template>

@endsection

@push('scripts')
<script>

const token = $('input[name="_token"]');
const payload = {
  user_id: {{ auth()->user()->id }},
  post_id: {{ $post->id }},
}
const commentWrapper = $data('comments');

let commentData = @json($post->comments);
commentData.sort((c1, c2) => (c1.created_at - c2.created_at));


let typingTimeout;

function typing(target) {
  clearTimeout(typingTimeout);

  const parent = target.parentNode;
  const submitBtn = parent.querySelector('[data-submit-btn]');
  const cancelBtn = parent.querySelector('[data-cancel-btn]');

  typingTimeout = setTimeout(() => {
    if (target.value.length > 0) {
      enable(submitBtn);
      enable(cancelBtn);
      return;
    }

    disable(submitBtn);
    disable(cancelBtn);
  }, 400);
}


// Placeholder for incoming comment.
const loading = document.createElement('li');
loading.innerHTML = '<li class="border px-2 pt-1 mb-2">Loading...</li>';

function storeComment(btn) {
  const parent = btn.parentNode;
  const commInput = parent.querySelector('[data-comment-input]');
  const submitBtn = parent.querySelector('[data-submit-btn]');
  const cancelBtn = parent.querySelector('[data-cancel-btn]');

  // Preparing backup to anticipate fails when storing comment.
  const tempCommentData = commentData;
  const headers = {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': token.value,
  }
  payload.body = commInput.value;

  commentWrapper.prepend(loading);

  disable(commInput);
  disable(submitBtn);

  fetch("{{ route('comments.store') }}", {
    headers,
    method: 'POST',
    body: JSON.stringify(payload),
  }).then(res => res.json())
    .then(json => {
      // Update commentData.
      commentData = json.comments;
      commInput.value = '';
      disable(cancelBtn);
    })
    .catch(err => {
      console.error(err);

      // Backup commentData.
      commentData = tempCommentData;
      commentWrapper.removeChild(loading);

      enable(submitBtn);
    })
    .finally(() => {
      enable(commInput);

      renderComment();
    });
}

// Reset comment input.
function cancelComment(btn) {
  const parent = btn.parentNode;
  const commInput = parent.querySelector('[data-comment-input]');
  const submitBtn = parent.querySelector('[data-submit-btn]');
  const cancelBtn = parent.querySelector('[data-cancel-btn]');

  commInput.value = '';

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
    // Determine if comment was updated.
    const updated = (comment.created_at !== comment.updated_at)
      ? `(Updated ${comment.updatedStr})`
      : '';

    // Checking for authorization.
    const replyBtn  = comment.canReply  ? '<button class="btn btn-secondary mb-2 px-2 py-0" onclick="createReply(this)">Reply</button>' : '';
    const updateBtn = comment.canModify ? '<button class="btn btn-primary mb-2 px-2 py-0" onclick="editComment(this)">Edit</button>' : '';
    const deleteBtn = comment.canModify ? '<button class="btn btn-danger mb-2 px-2 py-0" onclick="destroyComment(this)">Delete</button>' : '';

    content += `<li class="border px-2 pt-1 mb-2">
      <h6 class="d-flex justify-content-between">
        <span>
          ${comment.user.name}
          <i class="fw-normal">${updated}</i>
        </span>
        <span class="fw-normal">${comment.createdStr}</span>
      </h6>
      <p class="mb-2">${comment.body}</p>

      ${replyBtn}
      ${updateBtn}
      ${deleteBtn}
    </li>`;
  });

  commentWrapper.innerHTML = content;
}


// const commentReplyInput = $('#comment-reply');

function createReply(btn) {
  const comment   = btn.parentNode;
  const commentId = comment.dataset.commentId;

  const replyInput = document.createElement('div');
  replyInput.innerHTML = '<textarea class="form-control mb-2"></textarea>';

  comment.querySelector('p').after(replyInput);

  btn.onclick = storeReply;
}

function storeReply({ target }) {
  {{-- fetch("{{ route('replies.store') }}") --}}
}


function editComment(btn) {
  const parent = btn.parentNode;
  const deleteBtn = parent.querySelector('[data-delete-btn]');
  const saveBtn   = parent.querySelector('[data-submit-btn]');
  const cancelBtn = parent.querySelector('[data-cancel-btn]');

  hide(btn);
  hide(deleteBtn);
  show(saveBtn);
  show(cancelBtn);

  const p = parent.querySelector('p');
  p.outerHTML = '<textarea class="form-control mb-2" placeholder="Write a comment..." oninput="typing(this)" data-comment-input></textarea>';
}

function updateComment(btn) {
  
}

function cancelEdit(btn) {
  const parent = btn.parentNode;
  const saveBtn   = parent.querySelector('[data-submit-btn]');
  const editBtn   = parent.querySelector('[data-edit-btn]');
  const deleteBtn = parent.querySelector('[data-delete-btn]');

  hide(btn);
  hide(saveBtn);
  show(editBtn);
  show(deleteBtn);
}


function deleteComment(btn) {
  const parent = btn.parentNode;
  const editBtn    = parent.querySelector('[data-edit-btn]');
  const destroyBtn = parent.querySelector('[data-destroy-btn]');
  const cancelBtn  = parent.querySelector('[data-cancel-delete-btn]');

  hide(btn);
  hide(editBtn);
  show(destroyBtn);
  show(cancelBtn);
}

function destroyComment(btn) {
  
}

function cancelDelete(btn) {
  const parent = btn.parentNode;
  const destroyBtn = parent.querySelector('[data-destroy-btn]');
  const editBtn    = parent.querySelector('[data-edit-btn]');
  const deleteBtn  = parent.querySelector('[data-delete-btn]');

  hide(btn);
  hide(destroyBtn);
  show(editBtn);
  show(deleteBtn);
}


</script>
@endpush

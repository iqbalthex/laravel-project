@extends('layouts.main')

@push('styles')
<style scoped>

.post {
  --bg-opacity: .4;
  --overlay-color: blue;
  background-image: url(assets/image/ru.jpg);
  background-size: cover;
  background-position: 50%;
  overflow: hidden;
  height: 20rem;
  position: relative;
  transition: all .5s ease-in-out;
}

.post:hover {
  --bg-opacity: .7;
  cursor: pointer;
  scale: 1.02;
  box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.15);
  transition: all .3s ease-in-out;
}

.post:before {
  content: '';
  background-color: var(--overlay-color);
  opacity: 0;
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -10;
  transition: all .3s ease-in-out;
}

.post:hover:before {
  opacity: .2;
}

.post .action-btn svg {
  width: 0;
  transition: width .2s ease-in-out;
}

.post:hover .action-btn svg {
  width: 16px;
}

.post h3.title {
  transition: background .3s ease-in-out;
}

.post small.author {
  width: max-content;
}

.btn-outline-danger .bi-heart-fill {
  color: #777;
}

.btn-outline-secondary .bi-heart-fill {
  color: #f77;
}


@media only screen and (width > 960px) {
  .d-grid {
    grid-template-columns: 1fr 1fr;
  }
}

</style>
@endpush

@section('content')

<div class="container">
  <div class="d-grid row-gap-2 column-gap-3 py-2">
  @foreach ($posts as &$post)
    <div class="post col d-flex align-items-end rounded-2 border"
      style="--overlay-color: {{ ['red', 'green', 'blue', 'orange', 'black'][mt_rand(0, 4)] }};">
      <div class="w-100 p-2 d-flex justify-content-between" style="background: rgba(240,240,240,var(--bg-opacity))">
        <div class="w-75">
          <a class="d-inline-flex bg-gradient mb-2 px-3 fw-semibold text-white rounded text-decoration-none"
            style="background: {{ ['red', 'green', 'blue'][mt_rand(0, 2)] }};"
            href="{{ route('posts.index', ['category' => $post->category->id]) }}">
            <svg class="bi" width="1em" height="1em"></svg>
            {{ $post->category->name }}
          </a>
          <h3 class="title px-2 py-1 rounded-2">{{ $post->title }}</h3>
          <small class="author d-block mb-2 px-2 rounded">
            by
            <a class="text-decoration-none badge pb-2 bg-secondary" href="{{ route('posts.index', ['author' => $post->user->id]) }}">
              {{ $post->user->name }}
            </a>
          </small>
          <p class="body w-50 mb-1 px-2 overflow-hidden rounded-2">{{ $post->excerpt }}...</p>

          @php($actionBtn = 'action-btn btn d-inline-flex align-items-center')
          <a href="{{ route('posts.show', $post->slug) }}" class="{{ $actionBtn }} btn-dark">
            Read more <x-icons.bi-chevron-double-right class="ms-1" />
          </a>

          @can('update', $post)
            <a href="{{ route('posts.edit', $post->slug) }}" class="{{ $actionBtn }} btn-primary">
              Edit <x-icons.bi-chevron-double-right class="ms-1" />
            </a>
          @endcan

          @can('delete', $post)
            <form action="{{ route('posts.destroy', $post->slug) }}" method="post" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="{{ $actionBtn }} btn-danger" onclick="return confirm('Are you sure?')">
                Delete <x-icons.bi-chevron-double-right class="ms-1" />
              </button>
            </form>
          @endcan

        </div>

        <div class="d-flex flex-column justify-content-end gap-1">
          <button class="d-flex px-1 py-0 btn btn-outline-{{ $post->liked ? 'secondary' : 'danger' }}"
            onclick="toggleLike(this)"
            data-liked="{{ $post->liked ? 1 : 0 }}"
            data-post-id="{{ $post->id }}">
            <span class="w-100 h-100 d-flex justify-content-center align-items-center position-relative" style="">
              <x-icons.bi-heart-fill />
            </span>
            <span class="px-1 fw-bold" data-like-count>{{ $post->likes->count() }}</span>
          </button>
          <button class="btn btn-info px-1 py-0">
            <x-icons.bi-chat-dots />
            <span class="px-1 fw-bold">{{ $post->user->followers->count() }}</span>
          </button>
        </div>
      </div>
    </div>
  @endforeach
  </div>
</div>

@endsection

@push('scripts')
<script>

function toggleLike(target) {
  target.disabled = true;

  target.dataset.liked == 1
    ? unlike(target)
    : like(target);
}

const user_id = {{ auth()->user()->id }};
const token   = document.querySelector('input[name="_token"]');
const headers = {
  'X-CSRF-TOKEN': token.value,
  'Content-Type': 'application/json',
};

function like(target) {
  const url  = `{{ route('posts.like') }}`;
  const body = JSON.stringify({
    user_id,
    post_id: target.dataset.postId,
  });

  fetch(url, { headers, method: 'PATCH', body })
    .then(res => {
      if (res.ok) {
        target.classList.replace('btn-outline-danger', 'btn-outline-secondary');
        target.dataset.liked = '1';
        return res.json();
      }
    })
    .then(json => {
      const likeCount = target.querySelector('[data-like-count]');
      likeCount.innerText = json.likeCount;
    })
    .catch(err => console.error(err))
    .finally(() => (target.disabled = undefined));
}


function unlike(target) {
  const url  = `{{ route('posts.unlike') }}`;
  const body = JSON.stringify({
    user_id,
    post_id: target.dataset.postId,
  });

  fetch(url, { headers, method: 'PATCH', body })
    .then(res => {
      if (res.ok) {
        target.classList.replace('btn-outline-secondary', 'btn-outline-danger');
        target.dataset.liked = '0';
        return res.json();
      }
    })
    .then(json => {
      const likeCount = target.querySelector('[data-like-count]');
      likeCount.innerText = json.likeCount;
    })
    .catch(err => console.error(err))
    .finally(() => (target.disabled = undefined));
}

</script>
@endpush

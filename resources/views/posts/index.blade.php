@extends('layouts.main')

@section('content')

<style scoped>

.post {
  --bg-opacity: .4;
  --overlay-color: blue;
  background-image: url(assets/image/ru.jpg);
  background-size: cover;
  background-position: 50%;
  overflow: hidden;
}

.post:hover {
  --bg-opacity: .6;
  cursor: pointer;
  scale: 1.003;
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

.post h3.title,
.post p.body {
  background: rgba(180,180,180,var(--bg-opacity));
}

.post h3.title {
  width: max-content;
  transition: background .3s ease-in-out;
}

.post p.body {
  height: 1.5rem;
  transition: background .3s ease-in-out;
}

@media only screen and (width > 960px) {
  .d-grid {
    grid-template-columns: 1fr 1fr;
  }
}

</style>

<div class="container px-3">
  <div class="d-grid row-gap-2 column-gap-3 py-2">
  @foreach ($posts as $post)
    <div class="post col p-2 rounded-2 border"
      style="--overlay-color: {{ ['red', 'green', 'blue', 'orange', 'black'][mt_rand(0, 4)] }};">
      <div class="d-inline-flex bg-gradient mb-2 px-3 fw-semibold text-white rounded"
        style="background: {{ ['red', 'green', 'blue'][mt_rand(0, 2)] }};">
        <svg class="bi" width="1em" height="1em"></svg>
        yow
      </div>
      <h3 class="title px-2 py-1 rounded-2">{{ $post->title }}</h3>
      <p class="body w-75 px-2 overflow-hidden rounded-2">{{ $post->body }}</p>
      <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-primary d-inline-flex align-items-center">
        Read more
        <x-icons.bi-chevron-double-right />
      </a>
    </div>
  @endforeach
  </div>
</div>

@endsection

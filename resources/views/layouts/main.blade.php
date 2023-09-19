@include('layouts.header')

<nav class="h-100 d-flex flex-column flex-shrink-0 p-3 bg-body-secondary shadow" style="width: 200px; z-index: 30;">
  <div class="me-md-auto">
    <span class="fs-4">IQ Blog</span>
  </div>
  <hr>

  <ul class="nav nav-pills flex-column mb-auto">
    <li>
      <a href="{{ route('posts.index') }}"
        class="nav-link {{ Request::routeIs('posts.index') ? 'active' : 'text-body' }}">
        {{--<svg class="bi pe-none me-2" width="16" height="16"></svg>--}}
        All Posts
      </a>
    </li>
    <li>
      <a href="{{ route('posts.my-posts') }}"
        class="nav-link {{ Request::routeIs('posts.my-posts') ? 'active' : 'text-body' }}">
        My Posts
      </a>
    </li>
    <li>
      <a href="{{ route('posts.create') }}"
        class="nav-link {{ Request::routeIs('posts.create') ? 'active' : 'text-body' }}">
        Create Post
      </a>
    </li>
    <li>
      <a href="{{ route('categories.index') }}"
        class="nav-link {{ Request::routeIs('categories.*') ? 'active' : 'text-body' }}">
        Categories
      </a>
    </li>
  </ul>
</nav>

<div class="w-100 overflow-y-scroll scrollbar-none">
  <header class="d-flex justify-content-between px-4 py-3 bg-body-tertiary position-sticky top-0 border-bottom shadow-sm" style="z-index: 20;">
    <button class="btn btn-outline-dark">
      &lt;
    </button>

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <img @src(ru.jpg) alt="" width="32" height="32" class="rounded-circle me-2 object-fit-cover">
        <strong>Iqbal</strong>
      </a>
      <ul class="dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>
    </div>
  </header>

  <main class="h-100 bg-light">
    <div class="py-2">
      @yield('content')
    </div>
  </main>
</div>

@include('layouts.footer')

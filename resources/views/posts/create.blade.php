@extends('layouts.main')

@section('content')

<div class="container">
  <form action="{{ route('posts.store') }}" method="post">
    <div class="mb-2">
      <input type="text" name="title" placeholder="Title" />
    </div>
    <button class="btn btn-primary">Post</button>
  </form>
</div>

@endsection

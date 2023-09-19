@extends('layouts.main')

@section('content')

<div class="container">
  <form action="{{ route('posts.store') }}" method="post" class="row">
    <input type="hidden" name="user_id" value="1" />
    <div class="col-6">
      <div class="mb-2">
        <input type="text" class="form-control" name="title" placeholder="Title" value="{{ old('title') }}" required autofocus />
      </div>
      <div class="mb-2">
        <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" required disabled />
      </div>
      <div class="mb-2">
        <select class="form-select" name="category_id" required>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
        </select>
      </div>
      <div class="mb-2">
        <input type="file" class="form-control" name="image" accept="jpg" />
      </div>
      <div class="mb-2">
        <textarea class="w-100" rows="5" name="body" placeholder="Content...">{{ old('body') }}</textarea>
      </div>
      <button class="btn btn-primary">Post</button>
    </div>
    <div class="col-6">
      <div class="mb-2">
        <img class="w-50 img-thumbnail" src="{{ asset('assets/image/ru.jpg') }}" alt="post-image" data-image-preview />
      </div>
    </div>
  </form>
</div>

@endsection

@push("scripts")
<script>

const imagePreview = document.querySelector('[data-image-preview]');

</script>
@endpush

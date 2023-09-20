@extends('layouts.main')

@section('content')

<div class="container">
  <form action="{{ route('posts.store') }}" method="post" class="row">
    @csrf
    <input type="hidden" name="user_id" value="1" />
    <div class="col-6">
      <div class="mb-2">
        <label class="form-label" for="title">Title</label>
        <input type="text" class="form-control"
          id="title" name="title"
          placeholder="{{ fake()->sentence(2) }}" value="{{ old('title') }}"
          required autofocus
          data-title />
      </div>
      <div class="mb-2">
        <label class="form-label">Slug</label>
        <input type="text" class="form-control"
          name="slug"
          value="{{ old('slug') }}"
          required readonly
          data-slug />
      </div>
      <div class="mb-2">
        <label class="form-label" for="category">Category</label>
        <select class="form-select" id="category" name="category_id" required>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" @selected($category->id === old('category_id'))>
            {{ $category->name }}
          </option>
        @endforeach
        </select>
      </div>
      <div class="mb-2">
        <label class="form-label" for="image">Cover</label>
        <input type="file" class="form-control"
          id="image" name="image"
          accept="jpg"
          data-cover />
      </div>
      <div class="mb-2">
        <label class="form-label" for="body">Content</label>
        <textarea class="w-100 p-2" rows="5"
          id="body" name="body"
          placeholder="{{ fake()->sentence(10) }}">
          {{ old('body') }}
        </textarea>
      </div>
      <a type="button" class="btn btn-secondary" href="{{ route('posts.index') }}">Back</a>
      <button type="submit" class="btn btn-primary">Post</button>
    </div>
    <div class="col-6">
      <div class="mb-2">
        <label class="form-label d-block">Cover preview</label>
        <img class="w-50 img-thumbnail" src="{{ asset('assets/image/ru.jpg') }}" alt="post-image" data-img-preview />
      </div>
    </div>
  </form>
</div>

@endsection

@push("scripts")
<script>

const title = $('[data-title]');
const slug  = $('[data-slug]');

let typingTimeout;

title.oninput = _ => {
  clearTimeout(typingTimeout);

  typingTimeout = setTimeout(generateSlug, 400);
};


function generateSlug() {
  slug.value = title.value.replaceAll(' ', '-');
}


const imgCover   = $('[data-cover]');
const imgPreview = $('[data-img-preview]');

imgCover.onchange = ({ target }) => {
  
};

</script>
@endpush

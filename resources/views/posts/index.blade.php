@extends('layouts.main')

@section('content')

<div class="row row-cols-3">
  @foreach ([11,22,33,44,55,66,77] as $post)
    <div class="col border border-blue">
      {{ $post }}
    </div>
  @endforeach
</div>

@endsection

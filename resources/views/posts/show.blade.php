@extends('layouts.main')

@section('content')

<div class="container">
  <div class="row g-3">
    <div class="col-md-8">
      <article class="blog-post">
        <h2 class="display-5 mb-1">{{ $post->title }}</h2>
        <p class="blog-post-meta">
          Last updated at {{ $post->updated_at->diffForHumans() }}
          by <a href="#">{{ $post->user->name }}</a>
        </p>

        <p>This blog post shows a few different types of content that’s supported and styled with Bootstrap. Basic typography, lists, tables, images, code, and more are all supported as expected.</p>
        <hr>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h2>Blockquotes</h2>
        <p>This is an example blockquote in action:</p>
        <blockquote class="blockquote">
          <p>Quoted text goes here.</p>
        </blockquote>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h3>Example lists</h3>
        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout. This is an example unordered list:</p>
        <ul>
          <li>First list item</li>
          <li>Second list item with a longer description</li>
          <li>Third list item to close it out</li>
        </ul>
        <p>And this is an ordered list:</p>
        <ol>
          <li>First list item</li>
          <li>Second list item with a longer description</li>
          <li>Third list item to close it out</li>
        </ol>
        <p>And this is a definition list:</p>
        <dl>
          <dt>HyperText Markup Language (HTML)</dt>
          <dd>The language used to describe and define the content of a Web page</dd>
          <dt>Cascading Style Sheets (CSS)</dt>
          <dd>Used to describe the appearance of Web content</dd>
          <dt>JavaScript (JS)</dt>
          <dd>The programming language used to build advanced Web sites and applications</dd>
        </dl>
        <h2>Inline HTML elements</h2>
        <p>HTML defines a long list of available inline tags, a complete list of which can be found on the <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element">Mozilla Developer Network</a>.</p>
        <ul>
          <li><strong>To bold text</strong>, use <code class="language-plaintext highlighter-rouge">&lt;strong&gt;</code>.</li>
          <li><em>To italicize text</em>, use <code class="language-plaintext highlighter-rouge">&lt;em&gt;</code>.</li>
          <li>Abbreviations, like <abbr title="HyperText Markup Language">HTML</abbr> should use <code class="language-plaintext highlighter-rouge">&lt;abbr&gt;</code>, with an optional <code class="language-plaintext highlighter-rouge">title</code> attribute for the full phrase.</li>
          <li>Citations, like <cite>— Mark Otto</cite>, should use <code class="language-plaintext highlighter-rouge">&lt;cite&gt;</code>.</li>
          <li><del>Deleted</del> text should use <code class="language-plaintext highlighter-rouge">&lt;del&gt;</code> and <ins>inserted</ins> text should use <code class="language-plaintext highlighter-rouge">&lt;ins&gt;</code>.</li>
          <li>Superscript <sup>text</sup> uses <code class="language-plaintext highlighter-rouge">&lt;sup&gt;</code> and subscript <sub>text</sub> uses <code class="language-plaintext highlighter-rouge">&lt;sub&gt;</code>.</li>
        </ul>
        <p>Most of these elements are styled by browsers with few modifications on our part.</p>
        <h2>Heading</h2>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <h3>Sub-heading</h3>
        <p>This is some additional paragraph placeholder content. It has been written to fill the available space and show how a longer snippet of text affects the surrounding content. We'll repeat it often to keep the demonstration flowing, so be on the lookout for this exact same string of text.</p>
        <pre><code>Example code block</code></pre>
        <p>This is some additional paragraph placeholder content. It's a slightly shorter version of the other highly repetitive body text used throughout.</p>
      </article>
    </div>

    <div class="col-md-4">
      <div class="position-sticky" style="top: 5rem;">
        <div>
        @if (!$post->user->posts->empty())
          <div>
            <h4 class="fst-italic">Recents by {{ $post->user->name }}</h4>
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
                      <a class="btn btn-primary" href="#">
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
                        <small>by <a href="#" class="link-light">{{ $otherPost->user->name }}</a></small>
                      </span>
                      <a class="btn btn-primary" href="#">
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

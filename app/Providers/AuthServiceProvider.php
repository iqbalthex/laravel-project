<?php

namespace App\Providers;

use App\Modles\ { Comment, Post };
use App\Policies\ { CommentPolicy, PostPolicy };
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
  /**
   * The model to policy mappings for the application.
   *
   * @var array<class-string, class-string>
   */
  protected $policies = [
    Comment::class => CommentPolicy::class,
    Post::class => PostPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   */
  public function boot(): void {
    
  }
}

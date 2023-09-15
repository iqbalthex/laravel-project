<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\ {
  RedirectResponse,
  Request,
};
use Illuminate\View\View;

class CategoryController extends Controller {
  /**
   * Display a listing of the resource.
   */
  public function index(): View {
    return view('categories.index');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View {
    return view('categories.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request): RedirectResponse {
    return back();
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category): View {
    return view('categories.show');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category): View {
    return view('categories.edit');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Category $category): RedirectResponse {
    return back();
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category): RedirectResponse {
    return back();
  }
}

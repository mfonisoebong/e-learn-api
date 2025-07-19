<?php

namespace App\Http\Controllers\Courses;

use App\Http\Controllers\Controller;
use App\Http\Resources\Courses\CategoryResource;
use App\Models\Category;
use App\Traits\HttpResponses;
use App\Traits\UploadFiles;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use HttpResponses, UploadFiles;

    public function index()
    {
        return $this->success(CategoryResource::collection(Category::all()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'slug' => ['required'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
        ]);

        $featuredImage = $request->featured_image ? $this->uploadFile($request->featured_image, 'categories') : null;

        $category = new CategoryResource(Category::create([
            ...$request->except('featured_image'),
            'featured_image' => $featuredImage,
        ]));
        return $this->success($category, 'Category created successfully');
    }

    public function show(Category $category)
    {
        return $this->success(new CategoryResource($category));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'title' => ['required'],
            'slug' => ['required'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
        ]);

        $featuredImage = $request->featured_image ? $this->uploadFile($request->featured_image,
            'categories') : $category->featured_image;

        $category->update([
            ...$request->except('featured_image'),
            'featured_image' => $featuredImage,
        ]);

        return $this->success(new CategoryResource($category), 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->success(null, 'Category updated successfully');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new Category();
        return view('admin.categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation
        $data = $request->validate(
            ['name' => 'required|unique:categories|max:255',],
            [
                'name.required' => 'The category name is required',
                'name.unique' => 'This category name already exists',
                'name.max' => 'The category name may not be greater than 255 characters',
            ]

        );

        //insert category
        $category = new Category();
        $category->fill($data);
        $category->save();

        return to_route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate(
            [
                'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
            ],

            [
                'name.required' => 'The category name is required',
                'name.unique' => 'This category name already exists',
                'name.max' => 'The category name may not be greater than 255 characters',
            ]
        );

        // Update category
        $category->update($data);


        return to_route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Delete category
        $category->delete();

        return to_route('admin.categories.index');
    }
}

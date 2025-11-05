<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('subCategories')->orderBy('CategoryId', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'CategoryName' => 'required|string|max:50|unique:categories,CategoryName',
            'CategoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['CategoryName']);
        
        if ($request->hasFile('CategoryImage')) {
            $image = $request->file('CategoryImage');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/categories'), $imageName);
            $data['CategoryImage'] = 'images/categories/' . $imageName;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori başarıyla eklendi!');
    }

    public function show(Category $category)
    {
        $category->load('subCategories');
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::where('CategoryId', $id)->firstOrFail();
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('CategoryId', $id)->firstOrFail();
        
        $request->validate([
            'CategoryName' => 'required|string|max:50|unique:categories,CategoryName,' . $id . ',CategoryId',
            'CategoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['CategoryName']);
        
        if ($request->hasFile('CategoryImage')) {
            // Eski resmi sil
            if ($category->CategoryImage && file_exists(public_path($category->CategoryImage))) {
                unlink(public_path($category->CategoryImage));
            }
            
            $image = $request->file('CategoryImage');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/categories'), $imageName);
            $data['CategoryImage'] = 'images/categories/' . $imageName;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori başarıyla güncellendi!');
    }

    // Alt kategoriye göre kriterleri getir
       // Alt kategoriye göre kriterleri getir
    public function getCriteriasBySubCategory($subCategoryId)
    {
        $kriterler = \App\Models\Kriter::where('SubCategoryId', $subCategoryId)->get();
        return response()->json($kriterler);
    }
}
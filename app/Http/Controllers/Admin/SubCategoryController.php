<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->orderBy('SubCategoryId', 'desc')->get();
        return view('admin.subcategories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'CategoryId' => 'required|exists:categories,CategoryId',
            'SubCategoryName' => 'required|string|max:50',
            'SubCategoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['CategoryId', 'SubCategoryName']);
        
        if ($request->hasFile('SubCategoryImage')) {
            $image = $request->file('SubCategoryImage');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/subcategories'), $imageName);
            $data['SubCategoryImage'] = 'images/subcategories/' . $imageName;
        }

        SubCategory::create($data);

        return redirect()->route('admin.subcategories.index')
                        ->with('success', 'Alt kategori başarıyla eklendi!');
    }

    public function show($id)
    {
        $subCategory = SubCategory::with('category')->where('SubCategoryId', $id)->firstOrFail();
        return view('admin.subcategories.show', compact('subCategory'));
    }

    public function edit($id)
    {
        $subCategory = SubCategory::where('SubCategoryId', $id)->firstOrFail();
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::where('SubCategoryId', $id)->firstOrFail();
        
        $request->validate([
            'CategoryId' => 'required|exists:categories,CategoryId',
            'SubCategoryName' => 'required|string|max:50',
            'SubCategoryImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['CategoryId', 'SubCategoryName']);
        
        if ($request->hasFile('SubCategoryImage')) {
            // Eski resmi sil
            if ($subCategory->SubCategoryImage && file_exists(public_path($subCategory->SubCategoryImage))) {
                unlink(public_path($subCategory->SubCategoryImage));
            }
            
            $image = $request->file('SubCategoryImage');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/subcategories'), $imageName);
            $data['SubCategoryImage'] = 'images/subcategories/' . $imageName;
        }

        $subCategory->update($data);

        return redirect()->route('admin.subcategories.index')
                        ->with('success', 'Alt kategori başarıyla güncellendi!');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::where('SubCategoryId', $id)->firstOrFail();
        
        // Resmi sil
        if ($subCategory->SubCategoryImage && file_exists(public_path($subCategory->SubCategoryImage))) {
            unlink(public_path($subCategory->SubCategoryImage));
        }
        
        $subCategory->delete();

        return redirect()->route('admin.subcategories.index')
                        ->with('success', 'Alt kategori başarıyla silindi!');
    }

        // AJAX için kategori'ye göre alt kategorileri getir
     // AJAX için kategori'ye göre alt kategorileri getir
    public function getByCategory($categoryId)
    {
        $subCategories = SubCategory::where('CategoryId', $categoryId)->get();
        return response()->json($subCategories);
    }

    

}
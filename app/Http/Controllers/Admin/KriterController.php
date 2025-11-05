<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Kriter;
use App\Models\ProductCriteria; // ProductCriterias -> ProductCriteria
use Illuminate\Http\Request;

class KriterController extends Controller
{
    // Kriter ekleme formu
    public function create()
    {
        $kategoriler = Category::all();
        $altKategoriler = SubCategory::all();
        return view('admin.kriterler.create', compact('kategoriler', 'altKategoriler'));
    }

    // Kriter kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'CategoryId' => 'required|exists:categories,CategoryId',
            'SubCategoryId' => 'nullable|exists:sub_categories,SubCategoryId',
            'KriterAdi' => 'required|string|max:50',
            'KriterDegeri' => 'required|string|max:50',
        ]);

        Kriter::create($request->all());

        return redirect()->back()->with('success', 'Kriter başarıyla eklendi!');
    }

    // Kriterleri listeleme
    public function index()
    {
        $kriterler = Kriter::with('category','subCategory')->get();
        return view('admin.kriterler.index', compact('kriterler'));
    }

    // Düzenleme formu açma
    public function edit($id)
    {
        $kriter = Kriter::findOrFail($id);
        $kategoriler = Category::all();
        $altKategoriler = SubCategory::all();

        return view('admin.kriterler.edit', compact('kriter', 'kategoriler', 'altKategoriler'));
    }

    // Kriter güncelleme
    public function update(Request $request, $id)
    {
        $request->validate([
            'CategoryId' => 'required|exists:categories,CategoryId',
            'SubCategoryId' => 'nullable|exists:sub_categories,SubCategoryId',
            'KriterAdi' => 'required|string|max:50',
            'KriterDegeri' => 'required|string|max:50',
        ]);

        $kriter = Kriter::findOrFail($id);
        $kriter->update($request->only(['CategoryId', 'SubCategoryId', 'KriterAdi', 'KriterDegeri']));

        return redirect()->route('admin.kriterler.index')->with('success', 'Kriter güncellendi!');
    }

    // Kriter silme
    public function destroy($id)
    {
        $kriter = Kriter::findOrFail($id);
        
        // Bu kriterle ilişkili ürün kriterlerini kontrol et
        $relatedProductCriterias = ProductCriteria::where('CriteriaId', $kriter->KriterId)->count();
        
        if ($relatedProductCriterias > 0) {
            return redirect()->back()->with('error', 'Bu kriter ürünlerde kullanıldığı için silinemez! Önce ilgili ürün kriterlerini silin.');
        }
        
        $kriter->delete();
        return redirect()->back()->with('success', 'Kriter silindi!');
    }

    // Alt kategoriye göre kriterleri getir
    public function getCriteriasBySubCategory($subCategoryId)
    {
        // Kriterleri grupla ve her grubun değerlerini topla
        $kriterler = Kriter::where('SubCategoryId', $subCategoryId)
                          ->get()
                          ->groupBy('KriterAdi')
                          ->map(function($group, $kriterAdi) {
                              return [
                                  'KriterId' => $group->first()->KriterId, // İlk kaydın ID'sini al
                                  'KriterAdi' => $kriterAdi,
                                  'kriterValues' => $group->pluck('KriterDegeri')->toArray()
                              ];
                          })
                          ->values(); // Index'leri sıfırla

        return response()->json($kriterler);
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Kriter;
use App\Models\ProductCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Ürün ekleme formu
    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        return view('admin.product.create', compact('categories','subCategories'));
    }

    // Ürün kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'CategoryId'=>'required|exists:categories,CategoryId',
            'SubCategoryId'=>'nullable|exists:sub_categories,SubCategoryId',
            'Ad'=>'required|string|max:100',
            'Fiyat'=>'required|numeric',
            'Marka'=>'nullable|string|max:50',
            'Model'=>'nullable|string|max:50',
            'Resim'=>'nullable|image|max:2048',
            'Aciklama'=>'nullable|string|max:500',
            'Ozellikler'=>'nullable|string',
            'Kriterler'=>'nullable|array'
        ]);

        $data = $request->only(['CategoryId','SubCategoryId','Ad','Fiyat','Marka','Model','Aciklama','Ozellikler']);

        // Resim kaydetme - public/images/products
        if($request->hasFile('Resim')){
            $file = $request->file('Resim');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/products'), $fileName);
            $data['Resim'] = 'images/products/' . $fileName;
        }

        $product = Product::create($data);

        // Seçilen kriterleri kaydet
        if($request->Kriterler){
            foreach($request->Kriterler as $kriterAdi => $value){
                if($value) {
                    $kriter = Kriter::where('SubCategoryId', $request->SubCategoryId)
                                    ->where('KriterAdi', $kriterAdi)
                                    ->where('KriterDegeri', $value)
                                    ->first();
                    
                    if($kriter) {
                        ProductCriteria::create([
                            'ProductId'=>$product->ProductId,
                            'CriteriaId'=>$kriter->KriterId,
                            'CriteriaValue'=>$value
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success','Ürün başarıyla eklendi!');
    }

    // Ürünleri listeleme
    public function index()
    {
        $products = Product::with('category','subCategory','criterias')->get();
        return view('admin.product.index', compact('products'));
    }

    // Ürün düzenleme formu
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $kriterler = Kriter::where('SubCategoryId', $product->SubCategoryId)->get();
        $productCriterias = ProductCriteria::where('ProductId',$id)->pluck('CriteriaValue','CriteriaId')->toArray();

        return view('admin.product.edit', compact('product','categories','subCategories','kriterler','productCriterias'));
    }

    // Ürün güncelleme
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'CategoryId'=>'required|exists:categories,CategoryId',
            'SubCategoryId'=>'nullable|exists:sub_categories,SubCategoryId',
            'Ad'=>'required|string|max:100',
            'Fiyat'=>'required|numeric',
            'Marka'=>'nullable|string|max:50',
            'Model'=>'nullable|string|max:50',
            'Resim'=>'nullable|image|max:2048',
            'Aciklama'=>'nullable|string|max:500',
            'Ozellikler'=>'nullable|string',
            'Kriterler'=>'nullable|array'
        ]);

        $data = $request->only(['CategoryId','SubCategoryId','Ad','Fiyat','Marka','Model','Aciklama','Ozellikler']);

        // Resim güncelleme
        if($request->hasFile('Resim')){
            // Eski resmi sil
            if($product->Resim && file_exists(public_path($product->Resim))){
                unlink(public_path($product->Resim));
            }

            $file = $request->file('Resim');
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('images/products'), $fileName);
            $data['Resim'] = 'images/products/' . $fileName;
        }

        $product->update($data);

        // Eski kriterleri sil
        ProductCriteria::where('ProductId',$id)->delete();

        // Yeni kriterleri kaydet
        if($request->Kriterler){
            foreach($request->Kriterler as $kriterAdi => $value){
                if($value) {
                    $kriter = Kriter::where('SubCategoryId', $request->SubCategoryId)
                                    ->where('KriterAdi', $kriterAdi)
                                    ->where('KriterDegeri', $value)
                                    ->first();
                    
                    if($kriter) {
                        ProductCriteria::create([
                            'ProductId'=>$id,
                            'CriteriaId'=>$kriter->KriterId,
                            'CriteriaValue'=>$value
                        ]);
                    }
                }
            }
        }

        return redirect()->back()->with('success','Ürün güncellendi!');
    }

    // Ürün silme
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if($product->Resim && file_exists(public_path($product->Resim))){
            unlink(public_path($product->Resim));
        }

        ProductCriteria::where('ProductId',$id)->delete();
        $product->delete();

        return redirect()->back()->with('success','Ürün silindi!');
    }

    // Alt kategoriye göre kriterleri getir
    public function getCriteriasBySubCategory($subCategoryId)
    {
        $kriterler = Kriter::where('SubCategoryId', $subCategoryId)
                          ->get()
                          ->groupBy('KriterAdi')
                          ->map(function($group, $kriterAdi) {
                              return [
                                  'KriterId' => $group->first()->KriterId,
                                  'KriterAdi' => $kriterAdi,
                                  'kriterValues' => $group->pluck('KriterDegeri')->toArray()
                              ];
                          })
                          ->values();

        return response()->json($kriterler);
    }
}

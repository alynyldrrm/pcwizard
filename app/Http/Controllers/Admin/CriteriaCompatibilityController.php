<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CriteriaCompatibility;
use App\Models\Kriter;

class CriteriaCompatibilityController extends Controller
{
    // Uyumluluk Listeleme
    public function index()
    {
        $compatibilities = CriteriaCompatibility::with([
            'kriter1.category',
            'kriter1.subCategory',
            'kriter2.category',
            'kriter2.subCategory'
        ])->orderBy('CriteriaId1')->orderBy('CriteriaId2')->get();

        return view('admin.criteria_compatibilities.index', compact('compatibilities'));
    }

    // Uyumluluk Ekleme Sayfası
    public function create()
    {
        $criterias = Kriter::with('category', 'subCategory')
            ->orderBy('CategoryId')
            ->orderBy('SubCategoryId')
            ->orderBy('KriterAdi')
            ->orderBy('KriterDegeri')
            ->get();

        return view('admin.criteria_compatibilities.create', compact('criterias'));
    }

    // Uyumluluk Ekleme
    public function store(Request $request)
    {
        $request->validate([
            'CriteriaId1' => 'required|different:CriteriaId2',
            'CriteriaValue1' => 'required',
            'CriteriaId2' => 'required',
            'CriteriaValue2' => 'required'
        ], [
            'CriteriaId1.different' => 'Bir kriter kendisiyle uyumlu olamaz!'
        ]);

        // Aynı uyumluluk var mı kontrol et (düz ve ters)
        $exists = CriteriaCompatibility::where(function($q) use ($request) {
            $q->where('CriteriaId1', $request->CriteriaId1)
              ->where('CriteriaId2', $request->CriteriaId2)
              ->where('CriteriaValue1', $request->CriteriaValue1)
              ->where('CriteriaValue2', $request->CriteriaValue2);
        })->orWhere(function($q) use ($request) {
            $q->where('CriteriaId1', $request->CriteriaId2)
              ->where('CriteriaId2', $request->CriteriaId1)
              ->where('CriteriaValue1', $request->CriteriaValue2)
              ->where('CriteriaValue2', $request->CriteriaValue1);
        })->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['Uyumluluk kuralı zaten tanımlanmış!'])->withInput();
        }

        CriteriaCompatibility::create($request->all());

        return redirect()->route('admin.criteria_compatibilities.index')
            ->with('success', 'Uyumluluk kuralı başarıyla eklendi!');
    }

    // AJAX ile kriter değerlerini getir
    public function getCriteriaValues($criteriaId)
    {
        $criteria = Kriter::find($criteriaId);
        if (!$criteria) return response()->json(['success' => false]);

        $values = Kriter::where('KriterAdi', $criteria->KriterAdi)
            ->where('CategoryId', $criteria->CategoryId)
            ->where('SubCategoryId', $criteria->SubCategoryId)
            ->select('KriterId', 'KriterDegeri')
            ->get();

        return response()->json(['success' => true, 'values' => $values]);
    }

    // Uyumluluk Silme
    public function destroy($id)
    {
        $compatibility = CriteriaCompatibility::find($id);
        if (!$compatibility) {
            return response()->json(['success' => false, 'message' => 'Uyumluluk kuralı bulunamadı!']);
        }

        $compatibility->delete();
        return response()->json(['success' => true, 'message' => 'Uyumluluk kuralı başarıyla silindi!']);
    }
}

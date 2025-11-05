<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;

class CampaignController extends Controller
{
    // Kampanya listesi
    public function index()
    {
        $campaigns = Campaign::with('categories', 'subCategories', 'products')
                            ->orderBy('created_at', 'desc')
                            ->get();
        return view('admin.campaigns.index', compact('campaigns'));
    }

    // Yeni kampanya formu
    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $products = Product::all();
        return view('admin.campaigns.form', compact('categories', 'subCategories', 'products'));
    }

    // Kampanya kaydetme
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:100',
            'Description' => 'nullable|string',
            'DiscountType' => 'required|in:yuzde,sabit,ucretsiz_kargo,paket',
            'DiscountValue' => 'required|numeric|min:0',
            'CouponCode' => 'nullable|string|max:50|unique:Campaigns,CouponCode',
            'UsageLimit' => 'nullable|integer|min:1',
            'PerUserLimit' => 'nullable|integer|min:1',
            'MinCartTotal' => 'nullable|numeric|min:0',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        $campaign = Campaign::create([
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'DiscountType' => $validated['DiscountType'],
            'DiscountValue' => $validated['DiscountValue'],
            'CouponCode' => $validated['CouponCode'] ?? null,
            'UsageLimit' => $validated['UsageLimit'] ?? null,
            'PerUserLimit' => $validated['PerUserLimit'] ?? null,
            'MinCartTotal' => $validated['MinCartTotal'] ?? null,
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
            'IsActive' => true,
        ]);

        // İlişkileri ekle
        if ($request->has('categories')) {
            $campaign->categories()->sync($request->categories);
        }
        if ($request->has('subCategories')) {
            $campaign->subCategories()->sync($request->subCategories);
        }
        if ($request->has('products')) {
            $campaign->products()->sync($request->products);
        }

        return redirect()->route('admin.campaigns.index')
                        ->with('success', 'Kampanya başarıyla eklendi!');
    }

    // Kampanya düzenleme formu
    public function edit($id)
    {
        $campaign = Campaign::with('categories', 'subCategories', 'products')->findOrFail($id);
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $products = Product::all();
        return view('admin.campaigns.form', compact('campaign', 'categories', 'subCategories', 'products'));
    }

    // Kampanya güncelleme
    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:100',
            'Description' => 'nullable|string',
            'DiscountType' => 'required|in:yuzde,sabit,ucretsiz_kargo,paket',
            'DiscountValue' => 'required|numeric|min:0',
            'CouponCode' => 'nullable|string|max:50|unique:Campaigns,CouponCode,' . $id . ',CampaignId',
            'UsageLimit' => 'nullable|integer|min:1',
            'PerUserLimit' => 'nullable|integer|min:1',
            'MinCartTotal' => 'nullable|numeric|min:0',
            'StartDate' => 'required|date',
            'EndDate' => 'required|date|after_or_equal:StartDate',
        ]);

        $campaign->update([
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'DiscountType' => $validated['DiscountType'],
            'DiscountValue' => $validated['DiscountValue'],
            'CouponCode' => $validated['CouponCode'] ?? null,
            'UsageLimit' => $validated['UsageLimit'] ?? null,
            'PerUserLimit' => $validated['PerUserLimit'] ?? null,
            'MinCartTotal' => $validated['MinCartTotal'] ?? null,
            'StartDate' => $validated['StartDate'],
            'EndDate' => $validated['EndDate'],
        ]);

        // İlişkileri güncelle
        $campaign->categories()->sync($request->categories ?? []);
        $campaign->subCategories()->sync($request->subCategories ?? []);
        $campaign->products()->sync($request->products ?? []);

        return redirect()->route('admin.campaigns.index')
                        ->with('success', 'Kampanya başarıyla güncellendi!');
    }

    // Kampanya silme
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        // İlişkiler otomatik temizlenir (cascade)
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
                        ->with('success', 'Kampanya başarıyla silindi!');
    }

    // Kampanya durumunu değiştir (aktif/pasif)
    public function toggleStatus($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->IsActive = !$campaign->IsActive;
        $campaign->save();

        return redirect()->back()
                        ->with('success', 'Kampanya durumu güncellendi!');
    }
}
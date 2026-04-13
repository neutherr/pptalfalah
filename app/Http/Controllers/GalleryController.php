<?php

namespace App\Http\Controllers;

use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = GalleryCategory::orderBy('order')->withCount('items')->get();

        $itemsQuery = GalleryItem::active()->with('category');

        if ($request->filled('category')) {
            $itemsQuery->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $items    = $itemsQuery->orderBy('order')->paginate(12);
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.gallery', compact('categories', 'items', 'settings'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\GalleryItem;
use App\Models\HeroSection;
use App\Models\PpdbPeriod;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroSlides = HeroSection::active()->get();

        $latestArticles = Article::published()
            ->with('category', 'author')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        $featuredGallery = GalleryItem::active()
            ->featured()
            ->with('category')
            ->limit(6)
            ->get();

        $activePpdb = PpdbPeriod::active()
            ->with([
                'waves'        => fn ($q) => $q->where('is_active', true)->orderBy('order'),
                'requirements' => fn ($q) => $q->orderBy('order')->limit(5),
            ])
            ->first();

        $programs = Program::active()->get();

        $settings = SiteSetting::pluck('value', 'key');

        // Missions array (from site_settings, indexed 1-5)
        $missions = [];
        for ($i = 1; $i <= 5; $i++) {
            $title = $settings["mission_{$i}_title"] ?? null;
            if ($title) {
                $missions[] = [
                    'number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'title'  => $title,
                    'desc'   => $settings["mission_{$i}_desc"] ?? '',
                ];
            }
        }

        return view('home', compact(
            'heroSlides',
            'latestArticles',
            'featuredGallery',
            'activePpdb',
            'programs',
            'settings',
            'missions'
        ));
    }
}

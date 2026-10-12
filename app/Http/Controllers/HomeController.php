<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\GalleryItem;
use App\Models\HeroSection;
use App\Models\PpdbPeriod;
use App\Models\Program;
use App\Models\SiteSetting;
use App\Support\HomepageContent;
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
                'waves' => fn ($q) => $q->where('is_active', true)->orderBy('order'),
                'requirements' => fn ($q) => $q->orderBy('order')->limit(5),
            ])
            ->first();

        $programs = Program::active()->get();

        $settings = SiteSetting::pluck('value', 'key');
        $homepage = HomepageContent::fromSettings($settings->all());

        $missions = [];
        for ($i = 1; $i <= HomepageContent::MISSION_COUNT; $i++) {
            $title = trim($homepage["mission_{$i}_title"]);
            $description = trim($homepage["mission_{$i}_desc"]);
            if ($title !== '' || $description !== '') {
                $missions[] = [
                    'number' => str_pad($i, 2, '0', STR_PAD_LEFT),
                    'title' => $title,
                    'desc' => $description,
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
            'homepage',
            'missions'
        ));
    }
}

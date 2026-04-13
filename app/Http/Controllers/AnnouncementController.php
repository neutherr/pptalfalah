<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\SiteSetting;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $pinned        = Announcement::published()->pinned()->orderByDesc('published_at')->get();
        $announcements = Announcement::published()->orderByDesc('published_at')->paginate(10);
        $settings      = SiteSetting::pluck('value', 'key');

        return view('pages.announcements.index', compact('pinned', 'announcements', 'settings'));
    }

    public function show(string $slug): View
    {
        $announcement = Announcement::published()->where('slug', $slug)->firstOrFail();
        $settings     = SiteSetting::pluck('value', 'key');

        return view('pages.announcements.show', compact('announcement', 'settings'));
    }
}

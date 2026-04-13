<?php

namespace App\Http\Controllers;

use App\Models\DownloadFile;
use App\Models\SiteSetting;

use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $settings  = SiteSetting::pluck('value', 'key');
        $downloads = DownloadFile::active()->get();

        return view('pages.contact', compact('settings', 'downloads'));
    }
}

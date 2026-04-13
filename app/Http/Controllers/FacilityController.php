<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::active()->get();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.facilities.index', compact('facilities', 'settings'));
    }
}

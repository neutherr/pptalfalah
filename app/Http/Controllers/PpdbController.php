<?php

namespace App\Http\Controllers;

use App\Models\PpdbFee;
use App\Models\PpdbPeriod;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        $activePeriod = PpdbPeriod::active()
            ->with([
                'waves'        => fn ($q) => $q->orderBy('order'),
                'requirements' => fn ($q) => $q->orderBy('order'),
                'fees'         => fn ($q) => $q->orderBy('order'),
            ])
            ->first();

        $allPeriods = PpdbPeriod::orderByDesc('academic_year')->get();
        $settings   = SiteSetting::pluck('value', 'key');

        return view('pages.ppdb', compact('activePeriod', 'allPeriods', 'settings'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\SiteSetting;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(): View
    {
        $activePeriod = PpdbPeriod::active()
            ->with([
                'waves' => fn ($q) => $q->orderBy('order'),
                'requirements' => fn ($q) => $q->orderBy('order'),
            ])
            ->first();

        $allPeriods = PpdbPeriod::orderByDesc('academic_year')->get();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.ppdb', compact('activePeriod', 'allPeriods', 'settings'));
    }

    public function register(): View
    {
        return view('pages.ppdb-register', [
            'openWave' => PpdbPeriod::currentOpenWave(),
        ]);
    }

    public function proof(string $token): View
    {
        $registration = PpdbRegistration::query()
            ->with(['period', 'wave'])
            ->where('public_token', $token)
            ->firstOrFail();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.ppdb-proof', compact('registration', 'settings'));
    }
}

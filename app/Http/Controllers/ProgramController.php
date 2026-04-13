<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        $programs = Program::active()->get();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.programs.index', compact('programs', 'settings'));
    }

    public function show(string $slug): View
    {
        $program = Program::active()->where('slug', $slug)->firstOrFail();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.programs.show', compact('program', 'settings'));
    }
}

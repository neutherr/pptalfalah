<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\SiteSetting;
use Illuminate\View\View;

class AgendaController extends Controller
{
    public function index(): View
    {
        $agendas  = Agenda::published()->orderByDesc('start_datetime')->paginate(9);
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.agendas.index', compact('agendas', 'settings'));
    }

    public function show(string $slug): View
    {
        $agenda   = Agenda::published()->where('slug', $slug)->firstOrFail();
        $settings = SiteSetting::pluck('value', 'key');

        return view('pages.agendas.show', compact('agenda', 'settings'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Page;
use App\Models\Program;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect([
            ['loc' => route('home'), 'lastmod' => null],
            ['loc' => route('fasilitas'), 'lastmod' => null],
            ['loc' => route('programs.index'), 'lastmod' => null],
            ['loc' => route('ppdb'), 'lastmod' => null],
            ['loc' => route('gallery'), 'lastmod' => null],
            ['loc' => route('articles.index'), 'lastmod' => null],
            ['loc' => route('agendas.index'), 'lastmod' => null],
            ['loc' => route('announcements.index'), 'lastmod' => null],
            ['loc' => route('contact'), 'lastmod' => null],
        ]);

        Page::published()->select('slug', 'updated_at')->get()->each(
            fn (Page $page) => $urls->push([
                'loc' => $page->slug === 'profil' ? route('profil') : route('page.show', $page->slug),
                'lastmod' => $page->updated_at?->toAtomString(),
            ])
        );

        Program::active()->whereNotNull('slug')->select('slug', 'updated_at')->get()->each(
            fn (Program $program) => $urls->push([
                'loc' => route('programs.show', $program->slug),
                'lastmod' => $program->updated_at?->toAtomString(),
            ])
        );

        Article::published()->select('slug', 'updated_at')->get()->each(
            fn (Article $article) => $urls->push([
                'loc' => route('articles.show', $article->slug),
                'lastmod' => $article->updated_at?->toAtomString(),
            ])
        );

        Agenda::published()->select('slug', 'updated_at')->get()->each(
            fn (Agenda $agenda) => $urls->push([
                'loc' => route('agendas.show', $agenda->slug),
                'lastmod' => $agenda->updated_at?->toAtomString(),
            ])
        );

        Announcement::published()->select('slug', 'updated_at')->get()->each(
            fn (Announcement $announcement) => $urls->push([
                'loc' => route('announcements.show', $announcement->slug),
                'lastmod' => $announcement->updated_at?->toAtomString(),
            ])
        );

        return response()->view('sitemap', [
            'urls' => $urls->unique('loc')->values(),
        ], 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}

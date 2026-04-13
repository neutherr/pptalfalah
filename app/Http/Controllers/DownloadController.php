<?php

namespace App\Http\Controllers;

use App\Models\DownloadFile;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download(DownloadFile $file): StreamedResponse|RedirectResponse
    {
        if (! $file->is_active) {
            abort(404);
        }

        $file->incrementDownloads();

        $path = storage_path('app/public/' . $file->file_path);

        if (! file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $downloadName = \Illuminate\Support\Str::slug($file->name) . '.' . $extension;

        return response()->streamDownload(function () use ($path) {
            readfile($path);
        }, $downloadName);
    }
}

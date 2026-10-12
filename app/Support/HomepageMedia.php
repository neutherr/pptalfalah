<?php

namespace App\Support;

class HomepageMedia
{
    /** Only supported HTTPS videos are playable; arbitrary pages are never embedded. */
    public static function video(string $url): ?array
    {
        $url = trim($url);
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        if (strtolower($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass'])) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';
        $youtubeHosts = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com'];
        if ($host === 'youtu.be' || in_array($host, $youtubeHosts, true)) {
            $id = null;
            if ($host === 'youtu.be') {
                $id = trim($path, '/');
            } elseif ($path === '/watch') {
                parse_str($parts['query'] ?? '', $query);
                $id = $query['v'] ?? null;
            } elseif (preg_match('~^/(?:embed|shorts|live)/([^/]+)/?$~', $path, $matches)) {
                $id = $matches[1];
            }

            return is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/', $id)
                ? ['type' => 'youtube', 'url' => 'https://www.youtube-nocookie.com/embed/'.$id]
                : null;
        }

        return preg_match('/\.(mp4|webm)$/i', $path)
            ? ['type' => 'file', 'url' => $url]
            : null;
    }
}

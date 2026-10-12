# Who We Are media

Extend Konten Beranda with a media type (image/video), an optional public image upload (JPEG/PNG/WebP, max 2 MB), and a video URL. Match the gallery's URL-based video workflow: accept YouTube watch/share/embed/shorts links and HTTPS MP4/WebM files. Canonicalize YouTube embeds to youtube-nocookie.com. Do not accept arbitrary iframe HTML or unsupported URLs.

Render native video controls or a titled YouTube iframe, without autoplay, in a responsive 16:9 frame. Images retain the existing frame; the current plant image remains the fallback. Reuse the uploaded image as the native video poster. Keep all settings in site_settings; no migration needed. Text preset loading must preserve both persisted and pending media inputs.

1. Add failing feature tests for video rendering, fallback, URL validation, image uploads and media retention when loading the text preset.
2. Implement shared media URL parsing, CMS fields and frontend rendering with escaped URLs.
3. Run focused/full tests, format check, build and independent review. Document usage and merge/push to main following the user's existing instruction.

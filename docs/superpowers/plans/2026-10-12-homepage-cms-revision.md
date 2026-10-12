# Homepage CMS revision

Approved by the user on 12 October 2026, including implementation and merge to main.

## Design

Keep the existing site_settings table. Add a dedicated Filament Konten Beranda page with named fields for hero_title, site_tagline, hero_description, vision_headline, school_motto, and eight optional mission titles/descriptions. Keep legacy settings readable until saved. Blank missions are omitted; descriptions do not require a title. All new text is rendered escaped, with line breaks supported.

The page offers a Muat revisi klien action that fills the form with the client's supplied copy, clears old mission headings, and requires Simpan perubahan to persist. Saving is transactional and limited to the known homepage keys. No schema migration or production data replacement is necessary. Hero images remain in Hero Section.

## Execution

- [x] Add HTTP and Livewire regression tests for independent hero/vision, editable subtitle/motto, eight description-only missions, legacy content, and admin save/preset behavior. Run and confirm failure.
- [x] Implement the shared content mapping/preset, Filament page and Blade form. Update HomeController and the homepage view. Run focused tests.
- [x] Verify formatting, full tests, compiled views and review the diff. Document admin usage and deployment.
- [ ] Commit, merge the tested branch into main and push. Verify origin/main matches HEAD.

## Validation

Run `php artisan test --filter=HomepageContentTest` for the regression cycle, `php vendor/bin/pint --test` on changed PHP files, `php artisan test`, and `php artisan view:cache`. Review desktop/mobile wrapping for the longer hero and vision text. Run `git diff --check` before merge.

## Review notes

Independent code review found no actionable defects. Desktop and 390px mobile preview confirmed the supplied copy fits the page. Corrected the pre-existing example test's missing RefreshDatabase setup; changed the SEO assertion to compare rendered text because the editable subtitle now safely escapes HTML.

Composer audit reports 52 advisories in 18 existing packages. composer.json and composer.lock are unchanged. Dependency upgrades are outside this CMS revision; deployment must account for this existing security debt separately.

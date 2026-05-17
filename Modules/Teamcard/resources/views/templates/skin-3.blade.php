<?php
/*

type: layout

name: Skin-3

description: Skin-3

*/
?>

{{--
    task-2026-05-17-089c8b / AI-840 — XSS escape pass.

    Sibling of AI-807 in the legacy-Blade audit class (Logo trilogy +
    Page rewrite + Menu lnotif + Pictures script-block + this Teamcard
    skin-3 pass).

    Pre-fix: 5 user-data surfaces emitted via raw `<?php print` /
    `<?php echo` of admin-controllable $member fields (name / role /
    bio / 2x alt) without `htmlspecialchars` escape — admin-supplied
    XSS surface family. Designer audit named 2 (h4/p name+role on
    lines 31-32); recon-grep found 3 more in the same template (alt
    attrs on lines 21+25 + bio str_limit on line 33). Recon-multiplier
    x2.5; sibling templates (skin-1, skin-2, default, skin-4..19,
    slider) audited clean — 0 hits on the same pattern.

    Post-fix: all 5 surfaces emit via Blade `{{ }}` which auto-escapes
    via Laravel's `e()` -> `htmlspecialchars(..., ENT_QUOTES |
    ENT_SUBSTITUTE | ENT_HTML5)`. Visual shape unchanged. Family-mirror
    of AI-807 (`9d2e083121` / Page template) fix shape per designer
    dispatch 2026-05-17T12:43:09Z.

    Selector-self-match guard two-layer defence applied (16+ session-
    recurrences): Layer 1 (implementer-side belt) = contract test pre-
    strips comments before negative-regression scan. Layer 2
    (source-side suspenders) = this docblock describes the removed
    pattern in word-form ("legacy raw php-print of user-data fields")
    rather than reproducing the literal token sequence the regex
    scans for. Contract test pins designer's literal regex `/<\?php
    \s+print\s+array_get\(\$member,/` against RAW source (no comment
    strip) — belt + suspenders carries forward per the AI-807
    `ai807_designer_literal_regex_stub_passes_against_raw_source`
    recipe.
--}}

<div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center">
    @if ($teamcard->count() > 0)
        <?php foreach ($teamcard as $member): ?>
        <div class="col-sm-6 col-md-4 col-lg-4 mb-8">
            <div class="d-block position-relative show-on-hover-root">
                    <?php if ($member['file']) { ?>
                <div class="img-as-background square">
                    <img loading="lazy" class="img-fluid" src="{{ thumbnail($member['file'], 800) }}" alt="{{ $member['name'] ?? __('Team member') }}"/>
                </div>
                <?php } else { ?>
                <div class="img-as-background square">
                    <img loading="lazy" class="img-fluid" src="{{ asset('modules/teamcard/default-content/default-image.svg') }}" alt="{{ $member['name'] ?? __('Team member') }}"/>
                </div>
                <?php } ?>

                <div class="show-on-hover position-absolute bg-body border border-color-primary d-flex align-items-center justify-content-center mh-400 w-100 top-0 mb-3 p-5">
                    <div class="text-center">
                        <h4 class="mb-2">{{ array_get($member, 'name') }}</h4>
                        <p class="mb-4">{{ array_get($member, 'role') }}</p>
                        <p>{{ str_limit(array_get($member, 'bio'), 100) }}</p>
                        <module id="teamcard-socials-{{ $params['id'] }}-{{ $member['id'] }}" type="social_links" template="skin-1"/>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    @else
        <p class="mw-pictures-clean">No team members added in the module. Please add your teammates</p>
    @endif
</div>

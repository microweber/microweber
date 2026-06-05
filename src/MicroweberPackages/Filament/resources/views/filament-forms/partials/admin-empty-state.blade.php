{{--
  task-2026-05-17-c188de / AI-789 — systemic admin empty-state partial.
  Jira: https://microweber.atlassian.net/browse/AI-789

  Designer's R10-3 audit identified the empty-state pattern accreting
  across 5+ surfaces (Posts list / Pages list / Orders empty / Customer
  Profile / Marketplace, etc.) — same shape (heading + body +
  primary CTA), per-surface copy. AI-728/729/730 established the
  desired shape; AI-789 formalises it as a reusable partial.

  Consumers `@include` with named variables instead of hard-coding
  the markup per resource. The companion CSS (general-styles.css)
  already provides `.mw-table-empty-cta` styling for the CTA;
  the wrapping `.mw-admin-empty-state` adds vertical rhythm + centered
  alignment that matches the existing AI-728 shape.

  **Usage:**

      @include('mw-filament::partials.admin-empty-state', [
          'heading'      => 'No posts yet',
          'description'  => "Write your first post — it'll show up here.",
          'cta_label'    => '+ Add post',
          'cta_href'     => route('filament.admin.resources.posts.create'),
          'cta_aria'     => 'Add post',
      ])

  **Required variables:**
   - $heading      string  — primary headline (e.g. "No content yet")

  **Optional variables (all default null/missing):**
   - $description  string  — secondary body copy under heading
   - $cta_label    string  — primary CTA button text (no CTA rendered if missing)
   - $cta_href     string  — URL the CTA navigates to
   - $cta_aria     string  — aria-label for the CTA (defaults to $cta_label)
   - $icon         string  — Heroicon name (renders via @svg if blade-ui-kit
                             helpers are available; safe absent)
   - $extra_class  string  — extra CSS classes on the outer wrapper

  Standalone — does NOT depend on the per-model branching in
  Modules/Content/resources/views/filament/admin/empty-state.blade.php
  (that legacy partial may eventually migrate to consume this one).
--}}

@php
    $mwAdminEmptyHeading      = $heading ?? '';
    $mwAdminEmptyDescription  = $description ?? null;
    $mwAdminEmptyCtaLabel     = $cta_label ?? null;
    $mwAdminEmptyCtaHref      = $cta_href ?? null;
    $mwAdminEmptyCtaAria      = $cta_aria ?? $mwAdminEmptyCtaLabel;
    $mwAdminEmptyIcon         = $icon ?? null;
    $mwAdminEmptyExtraClass   = $extra_class ?? '';
@endphp

<div class="mw-admin-empty-state {{ e($mwAdminEmptyExtraClass) }}" data-mw-admin-empty-state="true">
    @if ($mwAdminEmptyIcon)
        <span class="mw-admin-empty-state__icon" aria-hidden="true">
            {{-- Heroicon name passed through to Filament's icon helper if present.
                 Falls back to nothing if neither blade-ui-kit nor @svg helper is wired. --}}
            @if (function_exists('svg'))
                {!! svg($mwAdminEmptyIcon, 'h-12 w-12 text-gray-400') !!}
            @endif
        </span>
    @endif

    <h2 class="mw-admin-empty-state__heading">
        {{ $mwAdminEmptyHeading }}
    </h2>

    @if ($mwAdminEmptyDescription)
        <p class="mw-admin-empty-state__body">
            {{ $mwAdminEmptyDescription }}
        </p>
    @endif

    @if ($mwAdminEmptyCtaLabel && $mwAdminEmptyCtaHref)
        <div class="mw-table-empty-cta-wrap mw-admin-empty-state__cta-wrap">
            <a
                href="{{ $mwAdminEmptyCtaHref }}"
                class="mw-table-empty-cta mw-admin-empty-state__cta"
                aria-label="{{ $mwAdminEmptyCtaAria ?? $mwAdminEmptyCtaLabel }}"
            >
                {{ $mwAdminEmptyCtaLabel }}
            </a>
        </div>
    @endif
</div>

# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

- [x] 2026-04-24  Add a "Migrating from WordPress?" CTA tile in the admin dashboard empty-state (shown only when `content` is empty) linking to the migration resource

- [x] 2026-04-24  make a hige plan in the todo.md to test a full workflow of website treation and make dusk teste for it  also make a plan in the todo.md to test all module skins on dusk oppulate the todo to cover more tests adn feach module that are not covered populate the todo to test the coroos chmes on all layouts

---

# Plan A — Full website-creation Dusk workflow

> **Goal:** a single end-to-end Dusk test (or small suite) that walks
> a first-time operator from a fresh install to a published,
> publicly-rendered site. Every stage a real user hits — install,
> pick template, create pages, edit with live-edit, add shop items,
> configure settings, publish — must be exercised in a real browser
> and its DB effect verified. The test doubles as the acceptance
> harness for the whole admin rewrite.

## A.1 Acceptance criteria

- [x] 2026-04-24  `LiveAdminFullWebsiteCreationWorkflowTest` exists at
      `tests/Browser/LiveAdminFullWebsiteCreationWorkflowTest.php`,
      under ≤15 minutes end-to-end, deterministic, and part of the
      default `php artisan dusk` run.
- [x] 2026-04-24  Seeds and purges its own fixture — no global install state is
      required; leaves zero residue on `pages`, `content`, `media`,
      `options`, or `users` after tear-down.
- [x] 2026-04-24  Golden-path stage assertions are at the DB level (source of
      truth) with the rendered-DOM assertions as signal for "the
      operator sees it."
- [x] 2026-04-24  Runs headless; fixture URLs (admin creds, port) come from
      `.env.dusk` — no hard-coded absolute paths.

## A.2 Pre-flight

- [x] 2026-04-24  Inventory existing admin-creation Dusk coverage (see
      `AdminContentCreateTest.php`, `AdminModulePagesTest.php`,
      `LiveEditInsertLayoutTest.php`, `LiveEditInsertModuleTest.php`)
      and mark which stages below can reuse a helper vs need a new
      one.

      **Inventory results — existing helpers we can lift into the workflow trait:**

      | File                                      | Reusable helpers                                                                   | Reuse target (Plan A.3 stage)                 |
      |-------------------------------------------|------------------------------------------------------------------------------------|-----------------------------------------------|
      | `AdminContentCreateTest.php`              | `livewireType($browser, $selector, $value)`, `livewireSet($browser, $prop, $val)`, `clickSave($browser)` — Livewire-v4-safe form drivers for page/post/product CRUD | Stage 3 (home page create), Stage 5 (product create) |
      | `AdminModulePagesTest.php`                | `assertPageLoadsWithoutError($browser, $url, $name)` — 200-plus-no-Whoops probe applied across every admin module page | Stages 1, 2, 5, 6 — every `visit /admin/...` sanity check |
      | `LiveEditInsertLayoutTest.php`            | Single test method (`insert_layout_dialog_is_interactive`) — no extracted helpers; the JS `mw.app.editor.insertLayoutRequestOnTop` dispatch pattern is worth copying verbatim | Stage 4 (insert jumbotron/skin-1) — copy the dispatch snippet into a new `insertLayoutOnCanvas()` helper |
      | `LiveEditInsertModuleTest.php`            | Single test method (`insert_module_dialog_is_interactive`) — same shape as above | Stage 4 supplement — module insertion into an already-opened layout slot |
      | `AdminLoginTrait`                         | `loginAsAdmin($browser)`, `ensureLoggedIn($browser)` — already in use by the workflow scaffold | All stages |
      | `CleansLandingTestPages` + `LandingTestContentPurger` | Precedent for marker-scoped fixture purge (`landing-test-*`) — already mirrored by the workflow's own `WorkflowFixturePurger` / `CleansWorkflowFixtures` | All stages (landed) |

      **Stages that need NEW helpers (no existing source to lift from):**
      - Stage 2 (template-switch) — `LiveEditTemplateSwitchBackToBootstrapNoStateLeakTest` exists but it tests no-bleed, not a clean switch-and-persist. Need a `selectTemplateInAppearance($browser, $templateName)` helper.
      - Stage 4's `stage_4_inline_edit_saves_heading_text` — no existing double-click + blur driver; copy from `AdminLiveEditElementStyleEditorTest` patterns.
      - Stage 5's `stage_5_add_to_cart_round_trip` — `AdminModuleCommerceUseCasesTest` exercises the admin shop admin but not the public-frontend add-to-cart; need a new `addToCartAsGuest()` helper.
      - Stage 6 (settings) — `AdminSettingsWorkflowTest` + `AdminSettingsTest` have the patterns; a `saveGeneralSettings($browser, $overrides)` helper would factor them out.
      - Stage 7 (palette apply) — already thoroughly helper-served by `LiveEditColorPaletteTrait` and `CleansColorPaletteTestFixtures`; direct reuse, no new helpers.
      - Stage 8 (guest checkout) — nothing lift-able; need a `completeCheckoutAsGuest($browser, $paymentMethod)` helper.

      **Decision:** the big-test scaffold remains composed of small trait-shaped helpers. Lift the three Livewire form drivers from `AdminContentCreateTest` into a new `WorkflowFormDrivers` trait before Stage 3; lift the 200-probe helper from `AdminModulePagesTest` as it stands.
- [x] 2026-04-24  Add a new trait `tests/Browser/Traits/WebsiteWorkflowTrait.php`
      for stage-scoped helpers (`assertStageACompleted(...)`) so the
      big test stays readable.

## A.3 Stage-by-stage test plan

### Stage 1 — Fresh install

- [x] 2026-04-24  `stage_1_install_lands_operator_on_the_admin_dashboard` — seed
      a clean `content`/`media`/`options` fixture so the empty-state
      CTA tile is visible (Phase 11 feature); login as admin; assert
      `/admin` returns 200 and contains the "Migrating from
      WordPress?" CTA tile.
- [x] 2026-04-24  `stage_1_welcome_widget_greets_the_admin_by_name` — asserts
      the greeting matches the admin's first_name / username.

### Stage 2 — Pick a template

- [x] 2026-04-24  `stage_2_template_switch_to_bootstrap_persists_in_options` —
      visit **Settings → Appearance / Templates**, pick Bootstrap
      (the default shipped template), submit; assert
      `options.template == 'bootstrap'` in DB.
- [x] 2026-04-24  `stage_2_switching_template_does_not_bleed_palette_state` —
      regression guard; reuses the pattern from
      `LiveEditTemplateSwitchBackToBootstrapNoStateLeakTest`.

### Stage 3 — Create a home page

- [x] 2026-04-24  `stage_3_home_page_is_created_with_a_menu_slot` — visit
      **Content → Pages → New**; fill title "Home"; save; assert a
      `content` row with `content_type='page'`, `subtype='static'`,
      `is_home=1` exists and appears in the main menu.
- [x] 2026-04-24  `stage_3_home_page_opens_in_live_edit` — click the **Edit**
      row action; assert `/admin/live-edit?url=/` loads and the
      edit-mode markers are visible in the iframe.

### Stage 4 — Drop a layout and edit it live

- [x] 2026-04-24  `stage_4_insert_jumbotron_skin1_layout` — use the existing
      `LiveEditInsertLayoutTest` helpers; drop
      `layouts/jumbotron/skin-1` onto the canvas; assert the module
      HTML landed on the page's `content.content` column and is
      visible on the rendered page.
- [x] 2026-04-24  `stage_4_inline_edit_saves_heading_text` — double-click the
      inserted heading, retype, blur; assert the new text round-trips
      through a save call and renders on the public URL.

### Stage 5 — Add a shop

- [x] 2026-04-24  `stage_5_shop_page_is_created_with_shop_content_type` — same
      as Stage 3 but with `content_type='page'`,
      `subtype='dynamic'`, `is_shop=1`; the sidebar now shows **Shop**.
- [x] 2026-04-24  `stage_5_add_first_product` — create a product via
      **Content → Products → New**; assert the `content` +
      `content_data` price row exists; public shop URL lists it.
- [x] 2026-04-24  `stage_5_add_to_cart_round_trip` — on the public frontend,
      add the product to the cart; assert a `cart` row persists with
      the right rel_id.

### Stage 6 — Configure core settings

- [x] 2026-04-24  `stage_6_site_title_and_description_save` — **Settings →
      General**; change site title + description; assert `options`
      rows.
- [x] 2026-04-24  `stage_6_logo_upload_persists` — upload a logo; assert a
      `media` row scoped to `rel_type='options'`.
- [x] 2026-04-24  `stage_6_currency_and_tax_save` — set default currency and a
      tax rate; assert the shop page's price tag reflects it.

### Stage 7 — Apply a color palette

- [x] 2026-04-24  `stage_7_apply_neon_night_palette_to_all_pages` — uses the
      Phase-7 color-palette pipeline; open the picker, pick
      `neon-night`, save. Reuses the assertion shape from
      `LiveEditColorPaletteSkinMatrixTest` but scoped to the
      workflow's own pages.

### Stage 8 — Publish and verify on the public site

- [x] 2026-04-24  `stage_8_home_page_is_publicly_reachable_without_login` —
      logs the browser out; visits `/`; asserts the heading from
      stage 4, the logo from stage 6, and the palette from stage 7
      are all rendered.
- [x] 2026-04-24  `stage_8_shop_product_is_purchasable_as_guest` — reuses the
      cart round-trip; adds a dummy checkout (cash-on-delivery
      method); asserts an `orders` row lands with the product and
      total.

## A.4 Cleanup & determinism

- [x] 2026-04-24  `setUp()` + `tearDown()` purge every row created by this test
      by `source_url`-style markers (pattern borrowed from the
      WordPressMigration Dusk tests — see `purgeFixture()` in
      `LiveAdminWordPressMigrationUxTest`).
- [x] 2026-04-24  Admin login reuses the existing `AdminLoginTrait`.

---

# Plan B — All layout skins on Dusk

> **Goal:** one dedicated Dusk test per shipped layout skin that
> inserts it, asserts the frontend rendered markup is sane, and
> checks the skin doesn't console-error. `LiveEditColorPaletteSkinMatrixTest`
> already proves the palette pipeline lands on all skins; these
> per-skin tests prove each skin renders correctly on its own.

## B.1 Target skin inventory (matches `ColorPaletteSkinMatrixFactory::TARGET_SKINS`)

| Family         | Skin path            | Current Dusk coverage                          |
|----------------|----------------------|------------------------------------------------|
| jumbotron      | `jumbotron/skin-1`   | `LiveEditJumbotronSkin1Test` ✅                |
| jumbotron      | `jumbotron/skin-2`   | — (skin-2 test missing)                        |
| features       | `features/skin-1`    | — (skin-1 test missing, skin-2 exists)         |
| features       | `features/skin-2`    | `LiveEditFeaturesSkin2Test` ✅                 |
| pricing        | `pricing/skin-1`     | — (skin-1 test missing, 2+3 exist)             |
| pricing        | `pricing/skin-2`     | `LiveEditPricingSkin2Test` ✅                  |
| pricing        | `pricing/skin-3`     | `LiveEditPricingSkin3Test` ✅                  |
| titles         | `titles/skin-1`      | `LiveEditTitlesSkin1Test` ✅                   |
| content        | `content/skin-1`     | `LiveEditContentSkin1Test` ✅                  |
| blog           | `blog/skin-1`        | `LiveEditBlogSkin1Test` ✅                     |
| ecommerce      | `ecommerce/skin-1`   | `LiveEditEcommerceSkin1Test` ✅                |
| footers        | `footers/skin-1`     | `LiveEditFootersSkin1Test` ✅                  |
| text-block     | `text-block/skin-1`  | — (missing)                                    |
| menus          | `menus/skin-1`       | — (missing)                                    |

## B.2 Per-skin test stubs to author

- [x] 2026-04-24  `LiveEditJumbotronSkin2Test` — mirror the existing
      `LiveEditJumbotronSkin1Test` shape for `jumbotron/skin-2`.
- [x] 2026-04-25  `LiveEditFeaturesSkin1Test` — mirror
      `LiveEditFeaturesSkin2Test` shape for `features/skin-1`.
- [x] 2026-04-25  `LiveEditPricingSkin1Test` — mirror
      `LiveEditPricingSkin2Test` shape for `pricing/skin-1`.
- [x] 2026-04-25  `LiveEditTextBlockSkin1Test` — text-block is a content skin;
      insert, assert rendered `<p>`/heading markers are in the DOM.
- [x] 2026-04-25  `LiveEditMenusSkin1Test` — insert the menu skin; assert links
      to the current menu entries are rendered.

## B.3 Shared contract for every per-skin test

Each test MUST assert, in order:

- [x] 2026-04-25  The skin blade file exists before attempting to insert (fail
      early with a useful message otherwise).
- [x] 2026-04-25  Inserting the skin on an empty live-edit canvas persists a
      `<module type="layouts" template="<family>/<skin>">` tag in
      the page's `content.content` column.
- [x] 2026-04-25  The public render of the page contains the skin's signature
      markup class (family-specific, e.g. `.mw-layout-jumbotron`).
- [x] 2026-04-25  No console error fires during insert OR public render
      (`browser.script("return window.__consoleErrors || []")`).

## B.4 Matrix-level guards (already present — keep green)

- [x] 2026-04-25  Keep `LiveEditColorPaletteSkinMatrixTest` green across new
      skins; updating the factory's `TARGET_SKINS` constant is the
      hook point.
- [x] 2026-04-25  Keep `LiveEditColorPaletteSkinMatrixNoLeakTest` green after
      any new skin test lands (proves the matrix is leak-proof per
      skin).

---

# Plan C — Cover modules with zero Dusk coverage

> **Goal:** every admin-facing module in `Modules/` has at least
> one Dusk smoke that asserts (a) its admin page loads without
> error, (b) its save flow round-trips through Livewire, and (c)
> no Filament 5 migration regressed its settings form. Modules
> with no admin UI get an explicit note so new contributors don't
> wonder why their module has no test.

## C.1 Conventions

- [x] 2026-04-25  Every new test file named
      `LiveAdminModule<ModuleName>SmokeTest.php`.
- [x] 2026-04-25  Tests reuse `AdminLoginTrait` and live under
      `tests/Browser/`.
- [x] 2026-04-25  Each test asserts three things minimum:
  1. Admin settings / resource page returns a 200 with no
     "Whoops" / "Internal Server Error" in the page source.
  2. A single save round-trip through whichever Livewire or
     Filament form the module exposes.
  3. Zero JS console errors during the above.

## C.2 Priority 1 — modules that ship admin surfaces (no Dusk yet)

- [x] 2026-04-25  `LiveAdminModuleAccordionSmokeTest` — frontend accordion skin + admin settings page.
- [x] 2026-04-25  `LiveAdminModuleAddressSmokeTest` — customer/address CRUD.
- [ ] `LiveAdminModuleAiWizardSmokeTest` — AI-wizard entry page.
- [ ] `LiveAdminModuleAttributesSmokeTest` — product attributes admin.
- [ ] `LiveAdminModuleAudioSmokeTest` — audio module insertion + inline URL edit.
- [ ] `LiveAdminModuleBeforeAfterSmokeTest` — slider comparison widget.
- [ ] `LiveAdminModuleBreadcrumbSmokeTest` — breadcrumb render on a nested page.
- [ ] `LiveAdminModuleBtnSmokeTest` — button module settings form.
- [ ] `LiveAdminModuleCaptchaSmokeTest` — captcha settings; form submits with token.
- [ ] `LiveAdminModuleCartSmokeTest` — cart admin view + manual line-item edit.
- [ ] `LiveAdminModuleCheckoutSmokeTest` — checkout form fields.
- [ ] `LiveAdminModuleCloudflareSmokeTest` — Cloudflare integration form.
- [ ] `LiveAdminModuleCompanySmokeTest` — company details form.
- [ ] `LiveAdminModuleComponentsSmokeTest` — components palette.
- [ ] `LiveAdminModuleContactFormSmokeTest` — contact form insertion + submission.
- [ ] `LiveAdminModuleContentDataSmokeTest` — content-data KV editor.
- [ ] `LiveAdminModuleContentDataVariantSmokeTest` — variants admin.
- [ ] `LiveAdminModuleContentFieldSmokeTest` — custom content field CRUD.
- [ ] `LiveAdminModuleCookieNoticeSmokeTest` — cookie notice settings.
- [ ] `LiveAdminModuleCountrySmokeTest` — country list admin.
- [ ] `LiveAdminModuleCouponsSmokeTest` — coupon CRUD + redeem on public checkout.
- [ ] `LiveAdminModuleCurrencySmokeTest` — currency list CRUD + default switch.
- [ ] `LiveAdminModuleCustomFieldsSmokeTest` — custom fields schema.
- [ ] `LiveAdminModuleEmbedSmokeTest` — embed module accepts common providers.
- [ ] `LiveAdminModuleExportSmokeTest` — content export page.
- [ ] `LiveAdminModuleFacebookLikeSmokeTest` — widget settings.
- [ ] `LiveAdminModuleFacebookPageSmokeTest` — widget settings.
- [ ] `LiveAdminModuleFaqSmokeTest` — FAQ module CRUD.
- [ ] `LiveAdminModuleFileManagerSmokeTest` — file manager view + upload.
- [ ] `LiveAdminModuleGoogleAnalyticsSmokeTest` — GA property field.
- [ ] `LiveAdminModuleGoogleMapsSmokeTest` — map widget settings.
- [ ] `LiveAdminModuleHighlightCodeSmokeTest` — code-block insertion.
- [ ] `LiveAdminModuleHostingApiSmokeTest` — hosting API landing page.
- [ ] `LiveAdminModuleImageRolloverSmokeTest` — image rollover admin.
- [ ] `LiveAdminModuleLayoutContentSmokeTest` — layout-content picker.
- [ ] `LiveAdminModuleLayoutsSmokeTest` — generic layouts picker.
- [ ] `LiveAdminModuleLogoSmokeTest` — logo upload form.
- [ ] `LiveAdminModuleMarqueeSmokeTest` — marquee module insertion.
- [ ] `LiveAdminModuleMenuSmokeTest` — menu manager CRUD.
- [ ] `LiveAdminModuleOfferSmokeTest` — offer CRUD.
- [ ] `LiveAdminModuleOpenApiSmokeTest` — OpenAPI docs route.
- [ ] `LiveAdminModulePaginationSmokeTest` — pagination widget settings.
- [ ] `LiveAdminModulePdfSmokeTest` — PDF export smoke.
- [ ] `LiveAdminModulePicturesSmokeTest` — picture module insertion.
- [ ] `LiveAdminModulePostSmokeTest` — post CRUD.
- [ ] `LiveAdminModuleRatingSmokeTest` — rating widget settings + frontend click.
- [ ] `LiveAdminModuleRestoreSmokeTest` — restore page entry point.
- [ ] `LiveAdminModuleRssFeedSmokeTest` — RSS feed settings.
- [ ] `LiveAdminModuleSeoSmokeTest` — SEO settings form.
- [ ] `LiveAdminModuleSharerSmokeTest` — sharer widget settings.
- [ ] `LiveAdminModuleSiteStatsSmokeTest` — stats dashboard + widget list.
- [ ] `LiveAdminModuleSkillsSmokeTest` — skills module.
- [ ] `LiveAdminModuleSliderSmokeTest` — slider CRUD + frontend render.
- [ ] `LiveAdminModuleSocialLinksSmokeTest` — social-links settings.
- [ ] `LiveAdminModuleSpacerSmokeTest` — spacer insertion.
- [ ] `LiveAdminModuleTabsSmokeTest` — tabs module CRUD.
- [ ] `LiveAdminModuleTeamcardSmokeTest` — team-card CRUD.
- [ ] `LiveAdminModuleTestimonialsSmokeTest` — testimonial CRUD.
- [ ] `LiveAdminModuleTextTypeSmokeTest` — text-type effect widget.
- [ ] `LiveAdminModuleTweetEmbedSmokeTest` — tweet embed input.
- [ ] `LiveAdminModuleVideoSmokeTest` — video module + poster upload.
- [ ] `LiveAdminModuleWhiteLabelSmokeTest` — white-label settings form.

## C.3 Priority 2 — modules without admin UI (document only)

- [ ] Add a one-liner NOTE in each module's README when the module is
      data-only (no admin UI, no public-frontend widget) so absent
      Dusk coverage is documented, not a gap: `Updater`,
      `Marketplace` (plugin marketplace lists are opt-in), etc.

## C.4 Batching guidance

- [ ] Land the smokes in priority-1 groups of 10; don't wait for the
      whole batch before committing — each smoke is independent and
      prevents regressions in isolation.
- [ ] If any module's admin form is still Livewire-v3-style (not yet
      Filament 5-migrated), file a follow-up `feat(<module>):
      filament-5 migration` task instead of just writing the smoke
      — the smoke would fail on the migration anyway.

---

# Plan D — Color palettes × layouts cross-matrix

> **Goal:** every shipped palette lands cleanly on every shipped
> layout skin. The existing matrix pairs `neon-night` with all skins;
> this plan widens that to the full palette × skin grid so a
> regression in any palette-skin combination is caught.

## D.1 Current palette inventory (17 packs)

Apple Shine · Arctic Frost · Blueberry Pie · Citrus Splash · Coral
Pop · Cyber Mint · Forest Haze · Golden Hour · Lavender Fields ·
Midnight Indigo · Minty Fresh · Neon Night · Pastel Dream · Robocop
· Sakura Bloom · Sunset Boulevard · Urban Concrete

## D.2 Deliverables

- [ ] `LiveEditColorPaletteLayoutMatrixTest` — parameterized over
      the 17 × 13 = 221 (palette, skin) pairs. Runs headless; must
      finish in ≤20 min. Applies the pack, asserts three computed
      styles per skin (body, heading, button) match the pack's
      declared values.
- [ ] Split the matrix into chunks that Dusk can run in parallel
      via `--group=palette-layout-chunk-N` so the full run stays
      under CI time budget.
- [ ] Add a matrix drift test `LiveEditColorPaletteTargetSkinDriftTest`
      that asserts `ColorPaletteSkinMatrixFactory::TARGET_SKINS`
      stays in sync with the actual blade files in
      `Templates/Bootstrap/resources/views/modules/layouts/templates/`
      — silently-missing skins are the biggest miss risk.
- [ ] Per-palette public-render tests (`LiveEditColorPalette<Pack>PublicRenderMatrixTest`)
      already exist for some packs — ensure every pack in §D.1 has one.

## D.3 Shared contract

- [ ] Every pair test asserts the pack's full `--mw-*` variable map
      lands on `:root` in the iframe.
- [ ] Every pair test asserts the concrete consumers (body color,
      heading color, primary-button background) resolve the vars
      correctly after a full CSS cascade pass.
- [ ] Every pair test leaves zero fixture residue (reuses
      `CleansColorPaletteTestFixtures` trait).

## D.4 Regression guards

- [ ] `LiveEditColorPaletteSwitchNoBleedTest` — keep green; proves
      switching packs doesn't leave the prior pack's vars behind.
- [ ] `LiveEditColorPaletteZeroPacksTemplateTest` — keep green;
      proves removing all packs restores the template's defaults.
- [ ] `LiveEditColorPaletteTemplateSwitchRoundTripTest` — keep
      green; proves template-switch preserves the pack selection
      when the operator switches back.

---

# Plan E — Verify: "is WordPress import actually working?"

> **Goal:** prove the Phase 1-11 WordPress importer actually works
> against a live WordPress site end-to-end, not just against the
> PHP-built-in-server fixture. This is the last user-facing
> validation before the feature ships.

- [ ] Add an opt-in Dusk test
      `LiveAdminWordPressMigrationLiveSiteCheckTest` (group
      `live-external`, excluded from the default run) that pokes a
      known-good public WordPress site (e.g. https://wordpress.org/news/)
      and asserts the probe returns `rest` + non-zero counts. Never
      runs in CI unless the group is explicitly requested.
- [ ] Document in `docs/migration/wordpress.md` §11 how a contributor
      can run the live check on their own box before shipping a
      Phase-* change.
- [ ] Add a contributor note in
      `docs/migration/wordpress-architecture.md` §3 pointing at the
      live check as the "before you tag a release" acceptance gate.

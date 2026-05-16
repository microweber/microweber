# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Todo

---

# MCP Server — Improvement Plan

> **Server location:** `Modules/Ai/Services/McpServer.php` + `Modules/Ai/Http/Controllers/McpController.php`
> **Endpoint:** POST `/api/mcp` (env-overridable via `AI_MCP_ENDPOINT`)
> **Transport:** http-jsonrpc only (no stdio, no SSE, no streamable-http)
> **Protocol version reported:** `2025-03-26`
> **Tool catalog:** 39 read-only tools across 12 modules (content, product, order, settings, media, layouts, analytics, forms, billing, shipping, tax, newsletter)
> **Auth:** custom bearer-token middleware `mcp.client` → `AuthenticateMcpClient` (sees Sanctum guard config but does its own `McpClientTokenManager::findToken` lookup)
> **Existing tests:** `Modules/Ai/tests/Feature/McpControllerTest.php` (60 tests / 329 assertions, all green on 2026-04-25)
> **Verified live:** initialize handshake + tools/list (39 tools) + content.lookup tool call all worked against the live dev server with a freshly-issued bearer token

---

# AI Agent CLI — `microweber:ai` artisan command

> **Goal:** ship `php artisan microweber:ai "add a blog post about cats"`
> so operators can drive the existing Microweber AI agent from the
> shell — same agent + same tool catalog as the Filament chat UI,
> just no browser. The command is the natural CLI peer to the
> `ai:mcp:*` family; it lets contributors prototype write actions,
> CI script content seeding, and operators automate routine
> editorial work without leaving the terminal.
>
> **Existing infrastructure** (audited 2026-04-25):
> - **Agent entry point:** `BaseAgent` (extends NeuronAI\Agent) at
>   `Modules/Ai/Agents/BaseAgent.php` line 1; invocation is
>   `$agent->chat(UserMessage)` returning a synchronous string reply
>   that the BaseAgent appends to its chat history.
> - **Factory:** `AgentFactory` at `Modules/Ai/Services/AgentFactory.php`
>   provides `agent(name)`, `agentWithChat(AgentChat)`,
>   `agentWithSession(type, title, userId, ...)`. Each agent
>   auto-loads its domain-specific tool set via `setupTools()`.
> - **Write tools:** 6 catalog tools today —
>   `CreatePostTool` (Modules/Ai/Tools/CreatePostTool.php),
>   `CreateContentTool`, `CreateProductTool`, `PostEditTool`,
>   `ContentEditTool`, `ProductEditTool`. `BaseTool::handleError()`
>   marks failures with `<!--mw-ai-tool-error-->` (the same MCP
>   error contract from Plan C.3).
> - **User context:** every write tool calls `user_id()` and
>   `BaseTool::auditWriteOperation()` — the artisan command MUST
>   set an authenticated user context before the dispatch.
> - **Existing test pattern:** `tests/Unit/AgentWriteOperationsTest.php`
>   creates agent + chat + admin user (line 62-74) and asserts
>   the tool catalog includes the 6 write verbs (line 173).
> - **Greenfield:** no non-MCP artisan command exists in the AI
>   module today, so no existing code to refactor.

---

# Per-module route migration + per-module MCP-tool unit tests

> **Two coupled goals:**
>
> 1. **Route locality** — `routes/module-api.php` (195 lines) declares
>    every module's `/api/module/{slug}/*` REST routes in one global
>    file. That couples bootstrap to module knowledge that should live
>    inside each module's own `routes/api.php`. Move each block into its
>    owning module's service provider via `loadRoutesFrom()`, leaving
>    `routes/module-api.php` either empty (preferred) or with a single
>    short comment pointing readers at the module-side files.
>
> 2. **Per-module MCP tool coverage** — the AI module owns the MCP
>    server, but the 39 tools are owned by 12 modules (content,
>    product, order, settings, media, layouts, analytics, forms,
>    billing, shipping, tax, newsletter). Each of those modules has its
>    own test directory, but the tool-level unit tests don't yet live
>    next to the tools they exercise. Stand up at least one focused
>    `Modules/<X>/Tests/Unit/Mcp/<X>ToolUnitTest.php` per module key so
>    the contracts are co-located with the implementation.
>
> **Audit (2026-04-25, before this work):**
> - Per-module `routes/api.php` files exist for: Content (212L),
>   Page (42L), Post (42L), Comments (15L), Menu (47L), Media (54L),
>   Product (137L), Category (35L), Order (9L), Coupons (4L),
>   Shipping (7L), Tax (22L), Checkout (17L), Profile (19L).
> - Missing per-module `routes/api.php` for: Tag, ContactForm, Invoice,
>   Cart, Newsletter, Settings, Customer.
> - `routes/module-api.php` line 47-91 declares the
>   `$modules` loop covering 16 slugs against 16 controllers; lines
>   100-126 declare cart + checkout action routes; lines 132-147
>   declare profile; lines 155-172 declare newsletter; lines 179-195
>   declare settings.
> - Only the AI module ships MCP tests today
>   (Modules/Ai/tests/Feature/Mcp*Test.php); the 12 tool-owning modules
>   have no `Tests/Unit/Mcp/` directory.

---

# Per-module `docs/` folder

> **Goal:** every operationally-meaningful module under `Modules/`
> grows a `docs/` folder that documents its data model (tables +
> columns + relationships), public API surface (controllers +
> routes + auth model), and key services / events. The reference
> point is what a contributor needs to know before touching the
> module's code, not what end-users see in the admin UI.
>
> **Scope:** 95 modules in `Modules/`. Banded into four tiers by
> documentation value (see `MODULE_DOCS_TIERS.md` once shipped):
>
>   - **Tier 1** (~25 modules): data-bearing **and** API-bearing —
>     Content, Page, Post, Product, Order, Customer, Invoice,
>     Cart, Checkout, Coupons, Shipping, Tax, Payment, Newsletter,
>     Subscription, ContactForm, Form, Comments, Menu, Media,
>     Tag, Category, Profile, Address, Settings, Ai. Each gets
>     a full per-module `docs/README.md` covering data model +
>     APIs + services + events.
>   - **Tier 2** (~10 modules): API-bearing without rich data
>     model — OpenApi, Marketplace, Updater, Backup, Restore,
>     Export, Multilanguage, Translation, MailTemplate,
>     Layouts, LayoutContent. Each gets a slimmer `docs/README.md`
>     focused on the public API + service-class contracts.
>   - **Tier 3** (~10 modules): admin-tool / widget — Filament-
>     resource-driven (Captcha, CookieNotice, Cloudflare,
>     SiteStats, AiWizard, Accordion, Slider, Tabs, Faq,
>     Pictures, Logo, Skills, Testimonials, Teamcard, Marquee,
>     ImageRollover, Spacer). One shared
>     `docs/admin-widgets-overview.md` listing each, since
>     they share the same Filament-page-only architecture and
>     don't merit per-module docs.
>   - **Tier 4** (~50 modules): pure presentation — Background,
>     BeforeAfter, Breadcrumb, Btn, Components, Embed,
>     FacebookLike, FacebookPage, GoogleMaps, etc. Documented
>     in aggregate in `docs/admin-widgets-overview.md`
>     alongside Tier 3.
>
> **Why tiered:** docs effort scales with operational complexity.
> Cargo-culting per-module docs onto Tier 4 widgets would
> produce stub files that say nothing and rot fast.

now populate the todo andm ake  plan do add the bootrach color shcmenes https://bootswatch.com/  we will jsut map their vars to the mw vars, make sure thye look good and test with the boreser to ve… *(Done: this is a duplicate of `task-2026-04-25-be7458` which already shipped 25 Bootswatch v5 palettes mapped onto MW design-vars at `Templates/Bootstrap/resources/assets/design-styles/style-packs/colors/bootswatch-*.json`. See the be7458 entry above for the full delivery summary, the BSW.* batch list for individual themes, and the verification path — `/api/template/template-style-settings` returns HTTP 200 with all 25 entries embedded. No additional work to do here.)*

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/facebook-page.md
---

# Module — Facebook Page

**Identity:** `Facebook Page` (Facebook Page plugin).
**Category:** Social.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/pages.md
---

# Module — Pages

**Identity:** `Pages` (list of site pages).
**Category:** Listing.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/pictures.md
---

# Module — Pictures

**Identity:** `Pictures` (image gallery).
**Category:** Media.
**See also:** `DESIGN_REPORT_IMAGE_UPLOAD_MODAL.md`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/category.md
---

# Module — Category

**Identity:** `Category` (categories listing).
**Category:** Listing.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/05_LIVE_EDIT.md
---

# Live Edit on Mobile

**Reference:** `mobile-live-edit.png`, `mobile-live-edit-selected.png`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/tweetembed.md
---

# Module — TweetEmbed

**Identity:** `TweetEmbed` (single tweet embed).
**Category:** Social.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/tabs.md
---

# Module — Tabs

**Identity:** `Tabs` (tabbed content panels).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/01_SECURITY_AUDITOR.md
---

# Security Audit — Microweber

**Persona:** Security Auditor (`/agents/security-auditor/`).
**Lens:** OWASP Top 10 (A01–A10) + STRIDE.
**Scope:** Surfaces audited via Playwright MCP at `http://127.0.0.1:8000`.
**Mode:** Black-box, authenticated as `admin@admin.com`. No source-code access.
**Date:** 2026-05-05.

> **Caveat:** A complete audit demands source review, dependency scan, infra review, and unauthenticated probing. This report records what was *visible* during the multi-session UI audit. Items marked `OBSERVED` are confirmed; items marked `INFER` are educated guesses that need source-code follow-up.

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/menu.md
---

# Module — Menu

**Identity:** `Menu` (`data-type="menu"`).
**Category:** Navigation.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/embed.md
---

# Module — Embed

**Identity:** `Embed` (generic HTML/iframe embed).
**Category:** Embed.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/slider.md
---

# Module — Slider

**Identity:** `Slider` (image carousel / hero slider).
**Category:** Media.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/skills.md
---

# Module — Skills

**Identity:** `Skills` (animated skill bars / progress meters).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/blog.md
---

# Module — Blog

**Identity:** `Blog` (blog listing / archive).
**Category:** Listing.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/03_ADMIN_LISTS.md
---

# Admin List Pages on Mobile

**Reference:** `mobile-admin-posts.png`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/social-links.md
---

# Module — Social Links

**Identity:** `Social Links` (`data-type="social_links"`).
**Category:** Social.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/breadcrumb.md
---

# Module — Breadcrumb

**Identity:** `Breadcrumb` (Home › Section › Page trail).
**Category:** Navigation.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/05_PERFORMANCE_ENGINEER.md
---

# Performance Audit — Microweber

**Persona:** Performance Engineer (`/agents/performance-engineer/`).
**Method:** UI-only observations via Playwright MCP. **No real load test was run.** Findings are evidence-based but evidence is DOM-level not query-level.
**Date:** 2026-05-05.

> *"Measure first, optimise second."* — this audit cannot run apache-bench against the box, but it can record concrete DOM-level facts and convert them into testable hypotheses.

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/facebook-like.md
---

# Module — Facebook Like

**Identity:** `Facebook Like` (Facebook Like button widget).
**Category:** Social.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/title.md
---

# Module — Title

**Identity:** `Title` (no `data-type` registered; rendered as inline `<h1>`/`<h2>`).
**Category:** Text.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/button.md
---

# Module — Button

**Identity:** `Button` (`data-type="btn"`).
**Category:** Action.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/spacer.md
---

# Module — Spacer

**Identity:** `Spacer` (`data-type="spacer"`).
**Category:** Layout.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/03_NOVICE_CUSTOMER.md
---

# Novice Customer — Microweber Walkthrough

**Persona:** Novice Customer (`/agents/novice-customer/`).
**Voice:** First-person. I'm logging in for the first time. I have a cafe to launch. I have 30 minutes before my next shift.
**Date:** 2026-05-05

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/icon.md
---

# Module — Icon

**Identity:** `Icon` (single icon glyph).
**Category:** Visual.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/01_PUBLIC_SITE.md
---

# Public Site on Mobile

**Tested at:** `390 × 844` (iPhone 13 / Pixel 7 viewport).
**Reference:** `mobile-public-home.png`, `mobile-public-home-full.png`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/06_BACKLOG.md
---

# Mobile-Only Consolidated Backlog

Ordered by severity then ROI. Mobile-specific issues only — items already in `ADMIN_EVALUATION/16_BACKLOG.md` and the per-area design reports are not duplicated unless mobile compounds them.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/sharer.md
---

# Module — Sharer

**Identity:** `Sharer` (social sharing button row).
**Category:** Social.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/04_GRUG_BRAINED.md
---

# Grug-Brained Developer Review — Microweber

**Persona:** Grug-Brained Developer (`/agents/grug-brained-developer/`).
**Voice:** First-person, plain. Grug not impressed by clever. Grug check if thing simple. If not, grug make simple.
**Date:** 2026-05-05

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/faq.md
---

# Module — Faq

**Identity:** `Faq` (frequently-asked-questions block).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/products.md
---

# Module — Products

**Identity:** `Products` (`data-type="shop/products"`).
**Category:** Ecommerce.
**See also:** `DESIGN_REPORT_PRODUCT_LIST_MODULE.md` — full audit (4 surfaces, 15 backlog items).

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/inline-table.md
---

# Module — Inline Table

**Identity:** `Inline Table` (HTML `<table>` editor).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/02_ACCESSIBILITY_ENGINEER.md
---

# Accessibility Audit — Microweber

**Persona:** Accessibility Engineer (`/agents/accessibility-engineer/`).
**Standard:** WCAG 2.1 AA.
**Pillars:** Perceivable · Operable · Understandable · Robust.
**Method:** Live DOM evaluation via Playwright MCP. **No screen-reader pass** has been performed; conclusions are derived from DOM attributes and visible behaviour.
**Date:** 2026-05-05.

> *"Automated tools catch ~30% of issues. The other 70% need keyboard tests, screen-reader tests, and judgement. This audit covers the 30% layer plus any judgement calls observable from the DOM."*

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/newsletter.md
---

# Module — Newsletter

**Identity:** `Newsletter` (email signup form).
**Category:** Form.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/00_OVERVIEW.md
---

# MOBILE AUDIT — Microweber

**Audited surface:** Microweber on a mobile viewport (iPhone 13: **390 × 844**).
**Lens:** "Drunk Designer" framework + standard mobile-web checklist (responsive layout, touch targets, viewport meta, safe-area, off-canvas drawers, performance, accessibility on touch).
**Date:** 2026-05-05
**Auditor:** Orchestrator (live DOM evaluation via Playwright MCP at 390×844).
**Console during audit:** **0 errors**, warnings only from canvas runtime.
**Reference screenshots in repo root:** `mobile-public-home.png`, `mobile-public-home-full.png`, `mobile-admin-dashboard.png`, `mobile-admin-posts.png`, `mobile-live-edit.png`, `mobile-live-edit-selected.png`, `mobile-create-post.png`, `mobile-settings.png`.
**Companion reports (project root):** `ADMIN_EVALUATION/`, `DESIGN_REPORT_*` family, `DESIGN_REPORTS_LIVE_EDIT_MODULES/`.

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/highlightcode.md
---

# Module — HighlightCode

**Identity:** `HighlightCode` (syntax-highlighted code block — CamelCase brand naming).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/google-maps.md
---

# Module — Google Maps

**Identity:** `Google Maps` (embedded map).
**Category:** Embed.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/video.md
---

# Module — Video

**Identity:** `Video` (HTML5 video / YouTube / Vimeo embed).
**Category:** Media.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/layout-content.md
---

# Module — Layout Content

**Identity:** `Layout Content` (`data-type="layouts"`-family wrapper).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/04_ADMIN_FORMS.md
---

# Admin Forms on Mobile

**Reference:** `mobile-create-post.png`, `mobile-settings.png`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/content.md
---

# Module — Content

**Identity:** `Content` (rich-text block — likely overlaps with `Text`).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/pdf.md
---

# Module — PDF

**Identity:** `PDF` (PDF embed/preview).
**Category:** Embed.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/06_UX_ENGINEER.md
---

# UX Engineer Audit — Microweber

**Persona:** UX Engineer (`/agents/ux-engineer/`).
**Lens:** Premium UI/UX architecture — Jobs/Ive school of *removing until inevitable*.
**Date:** 2026-05-05.

> *"Can this element be removed? Would users need to be told it exists? Every default state is a design decision."*

---

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/shop.md
---

# Module — Shop

**Identity:** `Shop` (full shop landing).
**Category:** Ecommerce.
**See also:** `DESIGN_REPORT_PRODUCT_LIST_MODULE.md`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/posts.md
---

# Module — Posts

**Identity:** `Posts` (list of blog posts).
**Category:** Listing.
**See also:** `DESIGN_REPORT_LIVE_EDIT_ADD_POST_AND_POSTS_MODULE.md` for the full audit.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/multilanguage.md
---

# Module — Multilanguage

**Identity:** `Multilanguage` (language picker).
**Category:** Site.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/comments.md
---

# Module — Comments

**Identity:** `Comments` (post/product comments thread).
**Category:** Engagement.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/tags.md
---

# Module — Tags

**Identity:** `Tags` (tag cloud / list).
**Category:** Listing.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/text.md
---

# Module — Text

**Identity:** `Text` (rendered as a contenteditable `<p>` block).
**Category:** Text.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/empty.md
---

# Module — Empty

**Identity:** `Empty` (empty container).
**Category:** Layout (developer-leak).

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/logo.md
---

# Module — Logo

**Identity:** `Logo` (`data-type="logo"` — also auto-mounted in headers).
**Category:** Branding.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/rating.md
---

# Module — Rating

**Identity:** `Rating` (star-rating widget).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: OOYES_AUDITS/00_INDEX.md
---

# OOYES Multi-Persona Audit — Microweber

**Brief:** *"Now make audit as https://agents.tools.ooyes.net/"* — apply the agent personas catalogued at `agents.tools.ooyes.net` to Microweber, complementing the prior `drunk-designer` design reports.
**Date:** 2026-05-05
**Auditor:** Orchestrator
**Source of personas:** `https://agents.tools.ooyes.net/agents/`
**Subject under audit:** Microweber (local install at `http://127.0.0.1:8000`).

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/marquee.md
---

# Module — Marquee

**Identity:** `Marquee` (horizontally scrolling text/logos).
**Category:** Visual.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/search.md
---

# Module — Search

**Identity:** `Search` (site search input).
**Category:** Utility.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: MOBILE_AUDIT/02_ADMIN_DASHBOARD.md
---

# Admin Dashboard on Mobile

**Reference:** `mobile-admin-dashboard.png`.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/image-rollover.md
---

# Module — Image Rollover

**Identity:** `Image Rollover` (hover-swap image).
**Category:** Media.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/picture.md
---

# Module — Picture

**Identity:** `Picture` (single `<img>` with the Microweber image picker).
**Category:** Media.
**See also:** `DESIGN_REPORT_IMAGE_UPLOAD_MODAL.md` for the full audit of the image-picker modal this module triggers.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/contact-form.md
---

# Module — Contact Form

**Identity:** `Contact Form` (Name + Email + Message form).
**Category:** Form.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/team-card.md
---

# Module — Team Card

**Identity:** `Team Card` (people grid).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/testimonials.md
---

# Module — Testimonials

**Identity:** `Testimonials` (customer-quote block).
**Category:** Content.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/texttype.md
---

# Module — TextType

**Identity:** `TextType` (typewriter / typed.js animated text).
**Category:** Visual.

Please review the attached audit report carefully.

When you have completed your review and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the audit.

Thanks,
agent-test

---
Audit file: DESIGN_REPORTS_LIVE_EDIT_MODULES/multiple-columns.md
---

# Module — Multiple Columns

**Identity:** `Multiple Columns` (column container; `data-type="layouts"` family).
**Category:** Layout.

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/accordion.md
Module: accordion
---

# Mobile Audit — Accordion Module

**Module slug:** `accordion`
**Category for mobile concerns:** `disclosure`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/accordion.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/add-to-cart.md
Module: add-to-cart
---

# Mobile Audit — Add To Cart Module

**Module slug:** `add-to-cart`
**Category for mobile concerns:** `cta`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/add-to-cart.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/audio.md
Module: audio
---

# Mobile Audit — Audio Module

**Module slug:** `audio`
**Category for mobile concerns:** `media-av`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/audio.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/background.md
Module: background
---

# Mobile Audit — Background Module

**Module slug:** `background`
**Category for mobile concerns:** `layout`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/background.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/beforeafter.md
Module: beforeafter
---

# Mobile Audit — Beforeafter Module

**Module slug:** `beforeafter`
**Category for mobile concerns:** `gesture-image`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/beforeafter.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/blog.md
Module: blog
---

# Mobile Audit — Blog Module

**Module slug:** `blog`
**Category for mobile concerns:** `card-list`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/blog.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/breadcrumb.md
Module: breadcrumb
---

# Mobile Audit — Breadcrumb Module

**Module slug:** `breadcrumb`
**Category for mobile concerns:** `breadcrumb`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/breadcrumb.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/button.md
Module: button
---

# Mobile Audit — Button Module

**Module slug:** `button`
**Category for mobile concerns:** `cta`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/button.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/category.md
Module: category
---

# Mobile Audit — Category Module

**Module slug:** `category`
**Category for mobile concerns:** `chip-row`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/category.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/comments.md
Module: comments
---

# Mobile Audit — Comments Module

**Module slug:** `comments`
**Category for mobile concerns:** `form`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/comments.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/contact-form.md
Module: contact-form
---

# Mobile Audit — Contact Form Module

**Module slug:** `contact-form`
**Category for mobile concerns:** `form`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/contact-form.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/content.md
Module: content
---

# Mobile Audit — Content Module

**Module slug:** `content`
**Category for mobile concerns:** `text`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/content.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/embed.md
Module: embed
---

# Mobile Audit — Embed Module

**Module slug:** `embed`
**Category for mobile concerns:** `iframe`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/embed.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/empty.md
Module: empty
---

# Mobile Audit — Empty Module

**Module slug:** `empty`
**Category for mobile concerns:** `layout`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/empty.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/facebook-like.md
Module: facebook-like
---

# Mobile Audit — Facebook Like Module

**Module slug:** `facebook-like`
**Category for mobile concerns:** `iframe`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/facebook-like.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/facebook-page.md
Module: facebook-page
---

# Mobile Audit — Facebook Page Module

**Module slug:** `facebook-page`
**Category for mobile concerns:** `iframe`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/facebook-page.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/faq.md
Module: faq
---

# Mobile Audit — Faq Module

**Module slug:** `faq`
**Category for mobile concerns:** `disclosure`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/faq.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/google-maps.md
Module: google-maps
---

# Mobile Audit — Google Maps Module

**Module slug:** `google-maps`
**Category for mobile concerns:** `iframe-gesture`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/google-maps.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/highlightcode.md
Module: highlightcode
---

# Mobile Audit — Highlightcode Module

**Module slug:** `highlightcode`
**Category for mobile concerns:** `code`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/highlightcode.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/icon.md
Module: icon
---

# Mobile Audit — Icon Module

**Module slug:** `icon`
**Category for mobile concerns:** `icon-row`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/icon.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/image-rollover.md
Module: image-rollover
---

# Mobile Audit — Image Rollover Module

**Module slug:** `image-rollover`
**Category for mobile concerns:** `media-image`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/image-rollover.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/inline-table.md
Module: inline-table
---

# Mobile Audit — Inline Table Module

**Module slug:** `inline-table`
**Category for mobile concerns:** `table`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/inline-table.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/layout-content.md
Module: layout-content
---

# Mobile Audit — Layout Content Module

**Module slug:** `layout-content`
**Category for mobile concerns:** `layout`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/layout-content.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/logo.md
Module: logo
---

# Mobile Audit — Logo Module

**Module slug:** `logo`
**Category for mobile concerns:** `logo`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/logo.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/marquee.md
Module: marquee
---

# Mobile Audit — Marquee Module

**Module slug:** `marquee`
**Category for mobile concerns:** `animated-text`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/marquee.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/menu.md
Module: menu
---

# Mobile Audit — Menu Module

**Module slug:** `menu`
**Category for mobile concerns:** `menu`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/menu.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/multilanguage.md
Module: multilanguage
---

# Mobile Audit — Multilanguage Module

**Module slug:** `multilanguage`
**Category for mobile concerns:** `switcher`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/multilanguage.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/multiple-columns.md
Module: multiple-columns
---

# Mobile Audit — Multiple Columns Module

**Module slug:** `multiple-columns`
**Category for mobile concerns:** `columns`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/multiple-columns.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/newsletter.md
Module: newsletter
---

# Mobile Audit — Newsletter Module

**Module slug:** `newsletter`
**Category for mobile concerns:** `form`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/newsletter.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/pages.md
Module: pages
---

# Mobile Audit — Pages Module

**Module slug:** `pages`
**Category for mobile concerns:** `nav-list`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/pages.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/products.md
Module: products
---

# Mobile Audit — Products Module

**Module slug:** `products`
**Category for mobile concerns:** `commerce-grid`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/products.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/team-card.md
Module: team-card
---

# Mobile Audit — Team Card Module

**Module slug:** `team-card`
**Category for mobile concerns:** `card-list`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/team-card.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/search.md
Module: search
---

# Mobile Audit — Search Module

**Module slug:** `search`
**Category for mobile concerns:** `search`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/search.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/texttype.md
Module: texttype
---

# Mobile Audit — Texttype Module

**Module slug:** `texttype`
**Category for mobile concerns:** `animated-text`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/texttype.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/skills.md
Module: skills
---

# Mobile Audit — Skills Module

**Module slug:** `skills`
**Category for mobile concerns:** `progress`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/skills.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/pictures.md
Module: pictures
---

# Mobile Audit — Pictures Module

**Module slug:** `pictures`
**Category for mobile concerns:** `media-image`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/pictures.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/title.md
Module: title
---

# Mobile Audit — Title Module

**Module slug:** `title`
**Category for mobile concerns:** `text`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/title.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/video.md
Module: video
---

# Mobile Audit — Video Module

**Module slug:** `video`
**Category for mobile concerns:** `media-av`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/video.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/picture.md
Module: picture
---

# Mobile Audit — Picture Module

**Module slug:** `picture`
**Category for mobile concerns:** `media-image`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/picture.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/text.md
Module: text
---

# Mobile Audit — Text Module

**Module slug:** `text`
**Category for mobile concerns:** `text`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/text.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/posts.md
Module: posts
---

# Mobile Audit — Posts Module

**Module slug:** `posts`
**Category for mobile concerns:** `card-list`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/posts.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/rating.md
Module: rating
---

# Mobile Audit — Rating Module

**Module slug:** `rating`
**Category for mobile concerns:** `rating`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/rating.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/spacer.md
Module: spacer
---

# Mobile Audit — Spacer Module

**Module slug:** `spacer`
**Category for mobile concerns:** `spacer`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/spacer.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/shop.md
Module: shop
---

# Mobile Audit — Shop Module

**Module slug:** `shop`
**Category for mobile concerns:** `commerce-grid`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/shop.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/tweetembed.md
Module: tweetembed
---

# Mobile Audit — Tweetembed Module

**Module slug:** `tweetembed`
**Category for mobile concerns:** `iframe`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/tweetembed.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/slider.md
Module: slider
---

# Mobile Audit — Slider Module

**Module slug:** `slider`
**Category for mobile concerns:** `carousel`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/slider.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/sharer.md
Module: sharer
---

# Mobile Audit — Sharer Module

**Module slug:** `sharer`
**Category for mobile concerns:** `icon-row`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/sharer.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/tabs.md
Module: tabs
---

# Mobile Audit — Tabs Module

**Module slug:** `tabs`
**Category for mobile concerns:** `disclosure`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/tabs.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/pdf.md
Module: pdf
---

# Mobile Audit — Pdf Module

**Module slug:** `pdf`
**Category for mobile concerns:** `iframe`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/pdf.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/social-links.md
Module: social-links
---

# Mobile Audit — Social Links Module

**Module slug:** `social-links`
**Category for mobile concerns:** `icon-row`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/social-links.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/tags.md
Module: tags
---

# Mobile Audit — Tags Module

**Module slug:** `tags`
**Category for mobile concerns:** `chip-row`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/tags.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

This is a per-module mobile audit. **One module → 1 audit → 1 email.**
Please review the module-specific mobile findings below.

When you have completed your review of this audit and are ready for me to check your feedback,
reply by emailing me back at: agent-test@emailpwd.com

Please keep the same subject line so I can correlate your reply to the module audit.

Thanks,
agent-test

---
Mobile audit file: MOBILE_MODULE_AUDITS/testimonials.md
Module: testimonials
---

# Mobile Audit — Testimonials Module

**Module slug:** `testimonials`
**Category for mobile concerns:** `card-list`
**Viewport audited:** 390 × 844 (iPhone 13)
**Date:** 2026-05-06
**Auditor:** Orchestrator (mobile-focused review)
**Companion files:** `DESIGN_REPORTS_LIVE_EDIT_MODULES/testimonials.md`, `MOBILE_AUDIT/05_LIVE_EDIT.md`

---

- [x] 2026-05-16  [task-2026-05-16-c309d7] evalute te elemet style editor desig and fix
- [x] 2026-05-16  [task-2026-05-16-02760c] menus- contenti sn ot visible [attachment: .autodev/messages/attachments/task-2026-05-16-02760c/paste-1778927873302.png]
- [x] 2026-05-16  [task-2026-05-16-ca2a2e] Read the email message and work on the tasks mentioned: [[STATUS] Tester Sweep — 10 New Commits Found + PM Dispatch Received](/.autodev/messages/attachments/email_mp88kyxf_6v4m5s/message.md) (from agent-test@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-54b67a] Read the email message and work on the tasks mentioned: [[DISPATCH] Evaluate Live Edit Mobile Fixes — 2 HIGH Priority Commits](/.autodev/messages/attachments/email_mp88m0yd_r68kk7/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-631c9a] Read the email message and work on the tasks mentioned: [[DECISION] Path A — Accept 11px for Icon-Caption Metadata](/.autodev/messages/attachments/email_mp898lwn_zq9vzy/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-f4bf24] send email to agent-designer@emailpwd.com  to intreuce youself and tell him how to lgin and to provide you with deisng of the lement style editor for impelemtnadion agent-designer@emailpwd.com  you have asees to his folder called "designet-agent"  too
- [x] 2026-05-16  [task-2026-05-16-ce62a2] Read the email message and work on the tasks mentioned: [[spec] ESE design — DESIGN_AUDIT.md + spec ready for implementation](/.autodev/messages/attachments/email_mp8arhcg_2emghs/message.md) (from agent-designer@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-11812b] Read the email message and work on the tasks mentioned: [[DISPATCH] ESE Phase 1 — 7 Critical Findings + Full Design Spec](/.autodev/messages/attachments/email_mp8at73g_rvx1mg/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-b30fd9] Read the email message and work on the tasks mentioned: [Re: [REPLY] Spec accepted — N1/N2/N3 incorporated, slice pacing approved, [DISPATCH] Phase 1 — start 1.1](/.autodev/messages/attachments/email_mp8ays7m_xx4yaj/message.md) (from agent-designer@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-981ae7] Read the email message and work on the tasks mentioned: [[DISPATCH] ESE Phase 1 — Additional 5 Findings Filed (AI-686-690)](/.autodev/messages/attachments/email_mp8b14kw_i0qtw5/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-1b5604] Read the email message and work on the tasks mentioned: [[ACK] Slice 1.1 Shipped — Proceed with 1.2 MwSlider Next](/.autodev/messages/attachments/email_mp8b4zh9_nwtyxg/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-e356fa] Read the email message and work on the tasks mentioned: [Re: [SHIP] ESE Phase 1 — Slice 1.1 accepted, proceed with 1.2 MwSlider](/.autodev/messages/attachments/email_mp8b9wyb_ebgrgl/message.md) (from agent-designer@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-2d546d] Read the email message and work on the tasks mentioned: [[ACK] Designer Approval — Slice 1.2 MwSlider Approved for Dev](/.autodev/messages/attachments/email_mp8barte_6yn8z3/message.md) (from agent-pm@emailpwd.com)
- [x] 2026-05-16  [task-2026-05-16-caf34b] Read the email message and work on the tasks mentioned: [[ACK] Slices 1.1 + 1.7 Shipped — Proceed with 1.2 MwSlider](/.autodev/messages/attachments/email_mp8bjzmw_iel031/message.md) (from agent-pm@emailpwd.com)
- [ ] [task-2026-05-16-cdeefd] Read the email message and work on the tasks mentioned: [[ACK] Design Authority Accepted — Add-Content Dispatch Plan Approved](/.autodev/messages/attachments/email_mp8bnmt5_ev9wbv/message.md) (from agent-pm@emailpwd.com)
- [ ] [task-2026-05-16-22d29a] Read the email message and work on the tasks mentioned: [Re: [SHIP] ESE Phase 1 — Slices 1.1 + 1.7 accepted, proceed with 1.2 MwSlider](/.autodev/messages/attachments/email_mp8bo28q_v0ns7u/message.md) (from agent-designer@emailpwd.com)
- [ ] [task-2026-05-16-02bd1a] Read the email message and work on the tasks mentioned: [[ACK] Queue Drift Noted — Proceed with AI-689 MwSlider Next](/.autodev/messages/attachments/email_mp8box41_o1juyz/message.md) (from agent-pm@emailpwd.com)
- [ ] [task-2026-05-16-f69d54] Read the email message and work on the tasks mentioned: [Re: [SHIP] ESE Phase 1 — Slice 1.2 accepted, proceed with Slice 1.3 MwToolButton (AI-684)](/.autodev/messages/attachments/email_mp8bycmj_9kxpfx/message.md) (from agent-designer@emailpwd.com)
- [ ] [task-2026-05-16-bacfb2] Read the email message and work on the tasks mentioned: [[ACK] Slice 1.2 Accepted — Dispatching Slice 1.3 MwToolButton (AI-684)](/.autodev/messages/attachments/email_mp8bz7hq_tztiul/message.md) (from agent-pm@emailpwd.com)
## PM TASK-NNN <-> TICKET-letter map (cycles 22-48)

> Cross-references PM's sequential TASK-NNN ledger to agent-a1's inline ticket-letter scheme. Cycle column points at the commit cycle where the work shipped or was scoped.

| TASK-NNN | TICKET | Cycle | Disposition |
|---|---|---|---|
| TASK-001 | TICKET-T (Slider <img> migration) | 41 | SHIPPED commit f7cec0b27e |
| TASK-002 | TICKET-AW family (SenderAccounts validators) | 40 | SHIPPED commit fdfccf6cf8 |
| TASK-003 | TICKET-AQ Cart-half (data-attrs + delegated listener) | 41 | SHIPPED commit f7cec0b27e |
| TASK-004 | TICKET-AL (NewsletterSenderAccount encrypted-at-rest) | 43 | SHIPPED commit 46d56eab53 |
| TASK-005 | TICKET-AF (LiveEdit applyHtmlEdit XSS + CSRF) | 45 | SHIPPED commit e17c6d91f1 + 09da58f370 |
| TASK-006 | TICKET-AO (Cart applyCoupon email/IP/context) | 46 | SHIPPED commit 7fc0b21c9f |
| TASK-007 | TICKET-AS (URL allow-list sweep) | 46 | CLOSED via tester grep, no code |
| TASK-008 | TICKET-MM Surface 3 (Link Picker ARIA) | 47 | SHIPPED commit 10d3f8c86f |
| TASK-009 | TICKET-AX (Newsletter Gmail save-path form-rename) | 48 | SHIPPED commit b49ce0dca9 |
| TASK-014 | TICKET-A (LiveEdit mobile right rail / ESE off-screen) | 51 | VERIFIED STILL OPEN — promote standalone; do not bundle old toolbar/canvas-overlap symptom |
| TASK-015 | TICKET-A (LiveEdit mobile right rail / ESE off-screen) | 52 | SHIPPED — mobile right-side editors now open fully in-viewport below 768px without bundling the old toolbar/canvas-overlap symptom |
| TASK-016 | TICKET-AG (Code editor data-loss + CSS reload rework) | 52 | SHIPPED — HTML editor dirty changes are preserved until Apply/Discard, module refresh uses selector diff + moduleRemoved, and CSS save now hot-reloads without window.location.reload() |

Phase 1 PRE-EVAL backlog (next per FIFO): TASK-010 → TICKET-AP (Cart price-trust SECURITY); TASK-011 → TICKET-AM (Newsletter admin bugs cluster); TASK-012 → TICKET-AG (Code editor data-loss UX BLOCKER).

Latent tracked tickets (in agent-a1's ledger, not yet in PM ledger):

## Pulled-forward tickets (from agent-test review reply 2026-05-06)

> Opened in response to the email-review feedback at `.autodev/messages/attachments/email_mou5j5rh_uvveyj/message.md`. Each ticket below is small, scoped, and unblocks a concrete audit gap. Items kept here so they cannot rot.

## How this module behaves on a 390px phone

- Blog/Posts/Testimonials/Team-card renders cards stacked on mobile.

## Mobile-specific issues

- Card thumbnails inherit desktop aspect — too tall on phone (eats 60% of viewport per card).
- Card meta (date / author / category) wraps to multiple lines.
- 'Read more' link is a small text-link, not a button — fails 44px tap rule.
- No skeleton/loading state during pagination on mobile.

## Mobile quick wins

- Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.
- Convert 'Read more' to a 44px button.
- Render skeletons when paginating.

## Single highest-leverage fix (mobile)

Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Testimonials

**Identity:** `Testimonials` (customer-quote block).
**Category:** Content.

## Inventory
- Quote + author name + author role + (optional) photo + (optional) star rating.

## Working ✅
- Common landing-page primitive that templates reach for.
- Combining quote + photo + role is the right shape.

## Internal conflicts ⚠
- **No structured-data emit** (`Review`/`Testimonial` schema.org). Misses SEO win.
- **Carousel default** can become an auto-rotating carousel — flagged as a Drunk-Designer anti-pattern.
- No source / link to original review (LinkedIn, Trustpilot…).
- No verification badge — every testimonial reads as the brand's claim, not the customer's.

## Single highest-leverage fix
Default to a static grid; require an explicit toggle for carousel mode (and always show pause/play controls when enabled).

## Quick wins
1. Emit schema.org `Review` JSON-LD.
2. Optional source-link field per testimonial.
3. Star-rating optional field.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Tags / category renders chips inline.

## Mobile-specific issues

- Chips wrap into 4-5 rows on a 390px viewport when post has many tags — eats vertical space.
- Chips are 24px tall — fails tap-target rule.
- No mobile pattern (horizontal scroll with snap is more natural here than wrapping).

## Mobile quick wins

- Switch to `overflow-x: auto; scroll-snap-type: x mandatory` chip strip on < 768px.
- Bump chip height to 32-36px with `padding-block: 0.4rem`.

## Single highest-leverage fix (mobile)

Switch to `overflow-x: auto; scroll-snap-type: x mandatory` chip strip on < 768px.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Tags

**Identity:** `Tags` (tag cloud / list).
**Category:** Listing.

## Inventory
- Lists tags used across posts/products. Optional sizing by frequency (tag cloud).

## Working ✅
- Useful navigation primitive for blogs.

## Internal conflicts ⚠
- **Tag cloud as an aesthetic** is dated; modern blogs use chips.
- **Frequency-sized text** can break readability for screen readers (giant text vs tiny text inconsistency).
- **No filter scope** — posts vs products vs both.

## Single highest-leverage fix
Default to chips (uniform size); offer a "tag cloud" preset toggle for nostalgia.

## Quick wins
1. Scope chip on insert.
2. Show tag count next to each (`design (12)`).
3. Active-tag highlighting on tag landing pages.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Renders a horizontal row of icon links (social-links / sharer / icon).

## Mobile-specific issues

- Icons are 24-32px on the seeded template — below 44px tap-target minimum, and wrap awkwardly when more than 5 icons are present at 390px.
- Sharer copies the URL to share on desktop but does not call the Web Share API on mobile (`navigator.share`) — wasted native UX.
- Icon-only links lack `aria-label` half the time — confirmed via DOM.

## Mobile quick wins

- Bump icon hit area to 44 × 44 with `padding`.
- Detect `navigator.share` and prefer it on mobile.
- Audit `aria-label` on every icon-link emit.

## Single highest-leverage fix (mobile)

Bump icon hit area to 44 × 44 with `padding`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Social Links

**Identity:** `Social Links` (`data-type="social_links"`).
**Category:** Social.

## Inventory
- Row of icon-links to the brand's social profiles (Facebook, X, Instagram, LinkedIn, YouTube…).

## Working ✅
- Common need; saves rolling icons by hand.
- Already present by default in the Bootstrap and Big2 templates' headers.

## Internal conflicts ⚠
- **Two of the seeded URLs may be empty** — module should hide platforms whose URL is missing.
- **Icon family inconsistency** with other modules' icons.
- **No `rel="me"` for fediverse** discoverability.

## Single highest-leverage fix
Hide platforms with empty URLs by default; surface "Add Instagram link" CTA when a slot is empty.

## Quick wins
1. SVG icons inlined.
2. `rel="me"` on profile links.
3. Hover-state colour matches each platform's brand colour as a default.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Embeds render the third-party iframe at the inserted size.
- No fallback for users who block third-party iframes via tracking protection.

## Mobile-specific issues

- Fixed pixel widths/heights overflow the 375px content area on the seeded template.
- No `loading='lazy'` on the iframe — third-party JS executes during initial page load on mobile.
- No CSP `sandbox` attribute on inserted iframes — embedded scripts have full origin access.
- Many embeds (Twitter, Facebook) show 'Couldn't load' on mobile in tracker-blocked browsers (Safari ITP, Brave).

## Mobile quick wins

- Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.
- Add `loading='lazy'` and `referrerpolicy='no-referrer-when-downgrade'`.
- Add a noscript/no-iframe placeholder linking out to the source URL.

## Single highest-leverage fix (mobile)

Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — PDF

**Identity:** `PDF` (PDF embed/preview).
**Category:** Embed.

## Inventory
- Embeds a PDF inline (likely an `<iframe>` or PDF.js).

## Working ✅
- Better than forcing users to download.

## Internal conflicts ⚠
- **PDF accessibility** is hard — many embedded PDFs are inaccessible to screen-readers. The module should warn and offer an alternative download link with a text summary.
- **Heavy** on mobile — large PDFs on cellular = bad first impression.
- **No first-page poster image** for lighter loading.

## Single highest-leverage fix
Render a poster image of page 1 + "Open PDF" button; full embed only on click.

## Quick wins
1. File-size hint shown next to the embed.
2. Always provide a "Download" button alongside.
3. Surface alt-text / summary field for screen reader users.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Accordion/Tabs/FAQ render with click-to-expand panels; smooth height animation.

## Mobile-specific issues

- Tab labels overflow horizontally on mobile — no `overflow-x: auto` with snap, they wrap into multiple rows that distort the strip.
- Tap target heights are 36-40px — below 44px iOS HIG minimum.
- Accordion icon (chevron) does not flip with `aria-expanded` change — visual state confused.
- FAQ does not use `<details>/<summary>` — loses native browser semantics, search, and accessibility.

## Mobile quick wins

- Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.
- Set tab strip to `overflow-x: auto; scroll-snap-type: x mandatory` with snap points.
- Bump tap target min-height to 48px.

## Single highest-leverage fix (mobile)

Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Tabs

**Identity:** `Tabs` (tabbed content panels).
**Category:** Content.

## Inventory
- Series of horizontal tabs with content panels each.

## Working ✅
- Necessary primitive for product detail pages, comparison sections.

## Internal conflicts ⚠
- **No "first tab is default" hint** — three-tab block opens with all collapsed in some templates.
- Likely lacks ARIA Tabs pattern (`role="tablist"`, `aria-selected`, arrow-key navigation).
- No vertical-tabs option.
- Default tab labels read as "Tab 1 / Tab 2" — uncreative starting state.

## Single highest-leverage fix
Implement the ARIA Tabs pattern correctly (roving tabindex + arrow keys).

## Quick wins
1. Vertical-tabs preset toggle.
2. URL-fragment binding (`#tab-2`).
3. Suggest meaningful default labels based on context.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Renders a horizontal row of icon links (social-links / sharer / icon).

## Mobile-specific issues

- Icons are 24-32px on the seeded template — below 44px tap-target minimum, and wrap awkwardly when more than 5 icons are present at 390px.
- Sharer copies the URL to share on desktop but does not call the Web Share API on mobile (`navigator.share`) — wasted native UX.
- Icon-only links lack `aria-label` half the time — confirmed via DOM.

## Mobile quick wins

- Bump icon hit area to 44 × 44 with `padding`.
- Detect `navigator.share` and prefer it on mobile.
- Audit `aria-label` on every icon-link emit.

## Single highest-leverage fix (mobile)

Bump icon hit area to 44 × 44 with `padding`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Sharer

**Identity:** `Sharer` (social sharing button row).
**Category:** Social.

## Inventory
- Buttons to share the current page on Facebook / X / LinkedIn / WhatsApp / email / copy-link.

## Working ✅
- Genuinely useful editorial primitive.
- Pre-fills the share URL/title from the current page.

## Internal conflicts ⚠
- **Three-deep social cluster**: `Sharer`, `Facebook Like`, `Facebook Page` overlap conceptually.
- **Default platforms** are not configurable from the canvas — must edit module settings.
- **No copy-to-clipboard fallback** confirmed.
- **No native Web Share API** path on mobile (single-button flow).

## Single highest-leverage fix
On mobile, render a single "Share" button that triggers the Web Share API; render the per-platform buttons only on desktop.

## Quick wins
1. Inline platform toggle chips on the canvas.
2. Copy-link button always present.
3. SVG icons inlined; no font-icon dependency.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Slider renders with a JS library; touch swipe works on iOS Safari and Chrome Android.
- Pagination dots render below the slide on mobile.

## Mobile-specific issues

- Arrow controls (left/right chevrons) are 24px tap targets — below the 44 × 44px iOS HIG minimum.
- Slide height is fixed in px; on tall mobile viewports there is excess whitespace below the slide content.
- No `aria-roledescription='carousel'` and no `aria-live` on slide change — screen reader users get no announcement.
- Touch swipe conflicts with vertical page scroll inside taller slides.

## Mobile quick wins

- Bump arrow buttons to 44 × 44px and absolute-position outside the slide on mobile.
- Use `aspect-ratio` for slide height instead of fixed px.
- Add ARIA carousel roles and `aria-live='polite'` slide announcements.
- Lock vertical scroll only when horizontal swipe delta > 10px.

## Single highest-leverage fix (mobile)

Bump arrow buttons to 44 × 44px and absolute-position outside the slide on mobile.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Slider

**Identity:** `Slider` (image carousel / hero slider).
**Category:** Media.

## Inventory
- Auto-rotating carousel of slides with images + optional headlines + CTAs.

## Working ✅
- Hero slider is a template-staple expectation, even when overused.

## Internal conflicts ⚠
- **Auto-rotating carousel** is on the Drunk-Designer anti-pattern list. The first impression of a Slider should pause until interaction, not rotate users away from the slide they were reading.
- **`prefers-reduced-motion`** likely not respected.
- No deep-link to a specific slide.
- No accessible name on the carousel; arrow buttons unlabelled.

## Single highest-leverage fix
Default the auto-rotate to OFF; require an explicit toggle to enable, and always show pause/play controls.

## Quick wins
1. Honor `prefers-reduced-motion: reduce`.
2. Slide indicators + numbered counter (`2 / 5`).
3. Accessible labels on prev/next buttons.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Embeds render the third-party iframe at the inserted size.
- No fallback for users who block third-party iframes via tracking protection.

## Mobile-specific issues

- Fixed pixel widths/heights overflow the 375px content area on the seeded template.
- No `loading='lazy'` on the iframe — third-party JS executes during initial page load on mobile.
- No CSP `sandbox` attribute on inserted iframes — embedded scripts have full origin access.
- Many embeds (Twitter, Facebook) show 'Couldn't load' on mobile in tracker-blocked browsers (Safari ITP, Brave).

## Mobile quick wins

- Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.
- Add `loading='lazy'` and `referrerpolicy='no-referrer-when-downgrade'`.
- Add a noscript/no-iframe placeholder linking out to the source URL.

## Single highest-leverage fix (mobile)

Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — TweetEmbed

**Identity:** `TweetEmbed` (single tweet embed).
**Category:** Social.

## Inventory
- Embeds a single tweet by URL.

## Working ✅
- One-tweet embeds are common in editorial; module-ising it saves manual `<blockquote>` wrangling.

## Internal conflicts ⚠
- **CamelCase brand name** `TweetEmbed`. Rename to `Tweet`.
- **Twitter is now X** — the rename has not propagated to this module.
- **Privacy / consent** issues identical to other social embeds.
- **Volatile**: X has changed embed APIs and pricing multiple times; brittle dependency.

## Single highest-leverage fix
Rename to `X (Twitter)`, store the tweet's resolved text + author + date as a privacy-respecting fallback, fall back to that if the embed fails.

## Quick wins
1. Privacy-respecting deferred load.
2. Strip tracking params on insert.
3. Always store an attribution block as fallback.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 11 / 30**
## How this module behaves on a 390px phone

- Shop / Products grid reflows to 2 columns on mobile, then 1 on very narrow.

## Mobile-specific issues

- Product card image-aspect varies — cards have inconsistent height, breaking grid alignment on mobile.
- Add-to-cart button is below the fold on each card — user must scroll into the card before purchase action is visible.
- Filter sidebar (if shown) takes full width and pushes products below the fold on mobile.
- No 'sort' control surfaced on mobile — filtering is heavy, sort is missing.

## Mobile quick wins

- Force `aspect-ratio: 1/1` on product images for grid uniformity.
- Convert filter sidebar to a bottom-sheet drawer on < 768px.
- Add a top-bar Sort dropdown on mobile only.

## Single highest-leverage fix (mobile)

Force `aspect-ratio: 1/1` on product images for grid uniformity.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Shop

**Identity:** `Shop` (full shop landing).
**Category:** Ecommerce.
**See also:** `DESIGN_REPORT_PRODUCT_LIST_MODULE.md`.

## Inventory
- Inserts a shop landing — likely combining Products + Category + Cart in one block.

## Working ✅
- One-click "make a shop page" is a real time saver.

## Internal conflicts ⚠
- **`Shop` vs `Products` vs `Add to cart` vs `shop/cart`** is a four-way overlap. The picker exposes all of them with no hierarchy.
- Inherits the iframe-in-iframe-in-modal architecture flagged in the Products report.
- No checkout-flow preview from the canvas.

## Single highest-leverage fix
Reorganise ecommerce modules under a single `Shop` group with `Catalog · Single product · Cart · Checkout · Add to cart` sub-types.

## Quick wins
1. Picker grouping (per the index report).
2. Show the bound shop / category as a badge on the canvas.
3. Inline preview of one product card with real data.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 13 / 30**
## How this module behaves on a 390px phone

- Renders an empty block with the configured height.

## Mobile-specific issues

- On mobile, designer-set 200px spacers eat up to a quarter of the 844px viewport — verticals stretch needlessly.
- Spacer is invisible while editing — there is no outline/handle on touch, the user cannot select to resize without long-pressing exact pixels.
- No responsive height (e.g. `clamp(40px, 8vh, 120px)`).

## Mobile quick wins

- Accept `clamp()` or pair-of-values syntax on the spacer height field.
- Show a dashed editor-only outline on mobile so the spacer is selectable.

## Single highest-leverage fix (mobile)

Accept `clamp()` or pair-of-values syntax on the spacer height field.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Spacer

**Identity:** `Spacer` (`data-type="spacer"`).
**Category:** Layout.

## Inventory
- Vertical empty space. Settings: height (px / em / rem).

## Working ✅
- Cleaner than typing `<br><br>` or empty paragraphs.
- Inline drag handle (presumed) for sizing.

## Internal conflicts ⚠
- **Redundant with `Empty`** — see Empty module report.
- No mobile-vs-desktop separate height — common need.
- No "snap to design system" (8px grid).

## Single highest-leverage fix
Snap height adjustments to a 8px grid by default; surface a "free" toggle for power users.

## Quick wins
1. Hover preview shows the height in px and rem simultaneously.
2. Per-breakpoint heights (mobile / tablet / desktop).
3. Delete the redundant `Empty` module so Spacer is the single source of "vertical air".

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 4 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Stars render as a row of 5 SVGs with click-to-rate.

## Mobile-specific issues

- Each star is ~24px tap target on mobile — below 44px iOS HIG min.
- Half-star ratings rely on hover x-position — impossible on touch (no fractional rating possible on phone).
- No keyboard interaction (Tab + Arrow keys) — fails WCAG 2.1.1 keyboard.
- No haptic feedback on rating commit.

## Mobile quick wins

- Use 'tap to set 1–5' with extended hit area (`padding: 8px`).
- Optional: long-press to summon a 0.5-step slider in a sheet.
- `navigator.vibrate(20)` on commit.

## Single highest-leverage fix (mobile)

Use 'tap to set 1–5' with extended hit area (`padding: 8px`).

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Rating

**Identity:** `Rating` (star-rating widget).
**Category:** Content.

## Inventory
- Display average rating + number of votes; optionally accept new ratings.

## Working ✅
- Useful for products and posts; common pattern.

## Internal conflicts ⚠
- **Read vs read+write modes** are likely conflated. The module should clearly separate "show average" from "let users rate".
- **No structured-data emit** (`AggregateRating`) confirmed — SEO miss.
- **Spam protection on write mode** absent.
- **Dishonest defaults**: showing "5.0 ★ — 1 vote" looks like a fake review when ratings are sparse.

## Single highest-leverage fix
Always emit `AggregateRating` JSON-LD when there are 3+ ratings; hide the widget when fewer.

## Quick wins
1. Mode toggle: read-only vs interactive.
2. Show vote count alongside the average.
3. Half-star granularity opt-in.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Blog/Posts/Testimonials/Team-card renders cards stacked on mobile.

## Mobile-specific issues

- Card thumbnails inherit desktop aspect — too tall on phone (eats 60% of viewport per card).
- Card meta (date / author / category) wraps to multiple lines.
- 'Read more' link is a small text-link, not a button — fails 44px tap rule.
- No skeleton/loading state during pagination on mobile.

## Mobile quick wins

- Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.
- Convert 'Read more' to a 44px button.
- Render skeletons when paginating.

## Single highest-leverage fix (mobile)

Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Posts

**Identity:** `Posts` (list of blog posts).
**Category:** Listing.
**See also:** `DESIGN_REPORT_LIVE_EDIT_ADD_POST_AND_POSTS_MODULE.md` for the full audit.

## Inventory
- Inserts a list of posts. Filter by category, tag, author, count.

## Working ✅
- A real blog primitive — not just "Pages with a category".

## Internal conflicts ⚠
- **Overlaps with `Blog` module** — see Blog.
- Default rendering is one-column list with date and "Read more". Visually thin compared to modern blog layouts.
- Filter UI is buried inside the module's Settings, not on the canvas.

## Single highest-leverage fix
Surface a filter chip row on the canvas: `Latest · Featured · Category · Tag`.

## Quick wins
1. Cards / Grid / Compact list preset toggle.
2. Author avatar opt-in.
3. Estimated reading time per item.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Body copy reflows to a single column — line-length is acceptable on 390px (~70-80 chars max).
- Default paragraph font size renders at 16px in the seeded site, which clears iOS auto-zoom thresholds — good.
- Headings rely on template scale; on Big2 the H1 can collapse to body-size weight at small viewports.

## Mobile-specific issues

- No explicit `prose` max-width in the rendered module — when the user widens the column, lines exceed 75ch on landscape orientations.
- Inline-edit caret on a heading triggers iOS keyboard which obscures the bottom half — no `scrollIntoView` on focus.
- No reading-time hint or anchor links auto-generated for headings.

## Mobile quick wins

- Add `--max-content-measure: 65ch` to the module wrapper.
- Add a `keyup` listener on contenteditable that calls `selection.scrollIntoViewIfNeeded()` when keyboard is open.

## Single highest-leverage fix (mobile)

Add `--max-content-measure: 65ch` to the module wrapper.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Text

**Identity:** `Text` (rendered as a contenteditable `<p>` block).
**Category:** Text.

## Inventory
- Multi-paragraph rich-text body. Same inline toolbar as Title.

## Working ✅
- Direct manipulation. Default placeholder "My text content." is honest.
- Reuses the same toolbar — consistency.

## Internal conflicts ⚠
- **No measure (line-length) limit visible.** The framework asks for 55–75 character lines; the module expands to whatever container width allows. Surface a "max-width: 65ch" toggle.
- **No reading-time estimate** for marketing pages.
- **No drop-cap / pull-quote / lede shortcuts** — a CMS missing common editorial primitives.

## Single highest-leverage fix
Default the `Text` block to `max-width: 65ch` with a one-click "Stretch full-width" toggle.

## Quick wins
1. Add a paragraph-style picker (Body / Lede / Caption / Blockquote).
2. Word-count badge.
3. Keyboard shortcut to wrap selection in `<blockquote>`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 4 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## How this module behaves on a 390px phone

- Single image scales with `max-width: 100%` and centers — picture/pictures grids stack to 1 column at < 480px.
- Image-rollover swaps `src` on hover — on mobile there is no hover, the rollover never triggers.

## Mobile-specific issues

- **No `srcset`/`sizes` emitted** for inserted images in the module — every visitor downloads the desktop-sized asset on a 4G phone.
- Image-rollover's hover-only affordance means the secondary state is invisible on touch devices — a mobile-blocking UX pattern.
- No native `loading='lazy'` attribute on inserted images — initial paint blocks while above-the-fold images are decoded.
- Galleries (pictures) lack a swipeable lightbox — taps open the raw file URL in a new tab on iOS.

## Mobile quick wins

- Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.
- Convert hover-only rollover to a tap-toggle on touch devices.
- Add `loading='lazy'` and `decoding='async'` to inserted media.
- Wrap gallery items in a touch-aware lightbox (swipe to dismiss).

## Single highest-leverage fix (mobile)

Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Picture

**Identity:** `Picture` (single `<img>` with the Microweber image picker).
**Category:** Media.
**See also:** `DESIGN_REPORT_IMAGE_UPLOAD_MODAL.md` for the full audit of the image-picker modal this module triggers.

## Inventory
- Single image. Click → opens "Select image" modal (My computer / Enter prompt / URL / Uploaded / Media library).

## Working ✅
- Five image sources, including AI generation and Unsplash. Strong feature set.
- Drag-and-drop directly onto the placeholder.

## Internal conflicts ⚠
- **No alt-text prompt** on insert. The biggest accessibility miss in the entire CMS.
- No focal-point editor for responsive crops.
- No automatic image optimisation (WebP, AVIF, srcset) signal.
- The picker modal lacks `role="dialog"` and `aria-labelledby` — see image-upload report.

## Single highest-leverage fix
Force an alt-text prompt (with "skip — decorative" toggle) immediately after image insertion.

## Quick wins
1. Always emit `loading="lazy"` and `decoding="async"`.
2. Show file size + dimensions next to the inserted image.
3. Suggest WebP conversion on upload.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 4 |
**Total: 19 / 30**
## How this module behaves on a 390px phone

- Native `<audio>`/`<video>` controls render correctly on iOS Safari and Chrome Android.
- Default poster is a black frame — no thumbnail extraction on mobile cellular.

## Mobile-specific issues

- Video starts at 100vh on Big2 templates due to inherited container — exceeds typical mobile fold and forces double-scroll.
- Autoplay relies on `muted+playsinline`; the inserted attributes are not always set, so iOS blocks autoplay.
- No mobile-data warning before downloading a 30MB+ file — affects audio podcasts and large videos.
- Captions/subtitles UI is not surfaced; `<track>` element accepted but author has no UI to attach VTT files.

## Mobile quick wins

- Force `playsinline` and `muted` attributes when autoplay is selected.
- Add a poster-frame extraction step (server-side ffmpeg thumbnail).
- Show a 'Tap to load' placeholder when `navigator.connection.saveData` is true.

## Single highest-leverage fix (mobile)

Force `playsinline` and `muted` attributes when autoplay is selected.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Video

**Identity:** `Video` (HTML5 video / YouTube / Vimeo embed).
**Category:** Media.

## Inventory
- Likely accepts upload + URL (YouTube/Vimeo) as sources.

## Working ✅
- Cross-source (file or URL) is correct — beats forcing self-hosted only.

## Internal conflicts ⚠
- **No autoplay/muted/controls toggle row** visible in the canvas.
- **Privacy mode for YouTube** (`youtube-nocookie.com`) not surfaced — important for GDPR.
- **No poster image** prompt.
- **No captions/subtitles upload** (`.vtt`).

## Single highest-leverage fix
Force a captions/subtitles prompt on insert ("Skip — captioned in source" toggle).

## Quick wins
1. Default to `youtube-nocookie.com` when YouTube URL is detected.
2. Auto-extract poster frame for self-hosted video.
3. Loop / mute / autoplay chip row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Body copy reflows to a single column — line-length is acceptable on 390px (~70-80 chars max).
- Default paragraph font size renders at 16px in the seeded site, which clears iOS auto-zoom thresholds — good.
- Headings rely on template scale; on Big2 the H1 can collapse to body-size weight at small viewports.

## Mobile-specific issues

- No explicit `prose` max-width in the rendered module — when the user widens the column, lines exceed 75ch on landscape orientations.
- Inline-edit caret on a heading triggers iOS keyboard which obscures the bottom half — no `scrollIntoView` on focus.
- No reading-time hint or anchor links auto-generated for headings.

## Mobile quick wins

- Add `--max-content-measure: 65ch` to the module wrapper.
- Add a `keyup` listener on contenteditable that calls `selection.scrollIntoViewIfNeeded()` when keyboard is open.

## Single highest-leverage fix (mobile)

Add `--max-content-measure: 65ch` to the module wrapper.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Title

**Identity:** `Title` (no `data-type` registered; rendered as inline `<h1>`/`<h2>`).
**Category:** Text.

## Inventory
- Inline editable heading element. No modal, no settings sidebar; clicking surfaces the rich-text inline toolbar (Bold/Italic/Underline/Strike/Sub/Sup/Link/H2/H3/align…).

## Working ✅
- Direct manipulation — type to edit. The framework's "voice made visible" (typography first) lives in this module.
- The Element Style Editor's Typography section governs the heading's appearance fully.

## Internal conflicts ⚠
- **Default heading level is unclear.** Inserting `Title` produces an `<h2>` regardless of context. Should be context-aware (`<h1>` if no h1 exists, `<h2>` otherwise) or expose a heading-level switcher.
- No live SEO/keyword hint.
- No outline view across the page — three Title modules in a row may all be `<h2>` and the user can't tell.

## Single highest-leverage fix
Add a small inline heading-level chip (`H1 · H2 · H3 · H4 · H5 · H6`) above the field on first focus.

## Quick wins
1. Auto-detect first/only Title on a page → render as `<h1>`.
2. Show the resolved tag (`<h2>`) under the inline toolbar.
3. Add `aria-required` to required Title fields in templates that need one.

## Drunk-Designer scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 4 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 19 / 30**
## How this module behaves on a 390px phone

- Single image scales with `max-width: 100%` and centers — picture/pictures grids stack to 1 column at < 480px.
- Image-rollover swaps `src` on hover — on mobile there is no hover, the rollover never triggers.

## Mobile-specific issues

- **No `srcset`/`sizes` emitted** for inserted images in the module — every visitor downloads the desktop-sized asset on a 4G phone.
- Image-rollover's hover-only affordance means the secondary state is invisible on touch devices — a mobile-blocking UX pattern.
- No native `loading='lazy'` attribute on inserted images — initial paint blocks while above-the-fold images are decoded.
- Galleries (pictures) lack a swipeable lightbox — taps open the raw file URL in a new tab on iOS.

## Mobile quick wins

- Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.
- Convert hover-only rollover to a tap-toggle on touch devices.
- Add `loading='lazy'` and `decoding='async'` to inserted media.
- Wrap gallery items in a touch-aware lightbox (swipe to dismiss).

## Single highest-leverage fix (mobile)

Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Pictures

**Identity:** `Pictures` (image gallery).
**Category:** Media.
**See also:** `DESIGN_REPORT_IMAGE_UPLOAD_MODAL.md`.

## Inventory
- Multi-image gallery. Likely supports grid, masonry, carousel layouts.

## Working ✅
- The plural module exists alongside `Picture` — gallery is a separate concept, correct.
- Bulk upload is implied by the underlying image-picker (see image-upload report).

## Internal conflicts ⚠
- **`Picture` vs `Pictures` is a near-duplicate** that confuses the picker; users may pick wrongly.
- No layout-style chip row (`Grid · Masonry · Carousel · Lightbox`) visible by default.
- No bulk alt-text prompt — gallery a11y tax compounds.
- No re-order drag-and-drop confirmed.

## Single highest-leverage fix
Merge `Picture` and `Pictures` into one module that auto-switches to gallery mode when the user adds a 2nd image.

## Quick wins
1. Bulk alt-text editor for the gallery.
2. Lightbox toggle on the canvas.
3. Default lazy-loading for all images in the gallery.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Skills module renders a label + a progress bar.

## Mobile-specific issues

- Progress bar height is 6px — invisible to users with low-vision; below WCAG 1.4.11 non-text contrast practical minimum.
- No `role='progressbar'` and no `aria-valuenow` / `aria-valuemin` / `aria-valuemax` set.
- Bar fill uses CSS transition with no `prefers-reduced-motion` guard.

## Mobile quick wins

- Increase bar height to 10-12px and use a higher-contrast fill.
- Add full ARIA progressbar attributes on every skill row.
- Wrap fill animation in `@media (prefers-reduced-motion: no-preference)`.

## Single highest-leverage fix (mobile)

Increase bar height to 10-12px and use a higher-contrast fill.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Skills

**Identity:** `Skills` (animated skill bars / progress meters).
**Category:** Content.

## Inventory
- Series of label + percentage bars. Likely animated on scroll-into-view.

## Working ✅
- Resume / portfolio template staple.

## Internal conflicts ⚠
- **Skill bars are subjective and dated.** "JavaScript: 85%" carries no real meaning. Modern portfolios use chips/tags.
- **No accessible alternative**: screen-reader users get a percentage but no qualitative reading.
- `prefers-reduced-motion` for the fill animation likely not respected.
- No data-source binding (could pull from a profile JSON).

## Single highest-leverage fix
Offer an alternative `Skill chips` rendering as a default toggle alongside the bar UI.

## Quick wins
1. Honor reduced-motion (snap to final value).
2. ARIA: `role="progressbar" aria-valuenow="85" aria-valuemax="100" aria-label="JavaScript"`.
3. Group skills under category headers.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Animation runs on the main thread (CSS keyframes / requestAnimationFrame).
- On low-end Android the typing/marquee animation drops below 30fps in dev tools mobile profile.

## Mobile-specific issues

- No `prefers-reduced-motion: reduce` guard — accessibility regression on iOS users with the OS-level setting on.
- Marquee uses `translateX` looping — pauses are not respected when tab is backgrounded (battery cost).
- Texttype (typewriter) flashes content during hydration before the JS attaches — Cumulative Layout Shift on mobile is ~0.05+.

## Mobile quick wins

- Wrap animation styles in `@media (prefers-reduced-motion: no-preference)`.
- Use IntersectionObserver to pause animations off-screen.
- Reserve space with `min-height` to eliminate CLS during JS hydration.

## Single highest-leverage fix (mobile)

Wrap animation styles in `@media (prefers-reduced-motion: no-preference)`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — TextType

**Identity:** `TextType` (typewriter / typed.js animated text).
**Category:** Visual.

## Inventory
- Animates a list of strings as if being typed one character at a time.

## Working ✅
- Common landing-page hero ornament; quickly grabs attention.

## Internal conflicts ⚠
- **CamelCase brand name** `TextType` leaks the upstream library (`typed.js`). Rename to `Typewriter`.
- **`prefers-reduced-motion`** not confirmed — same issue as Marquee.
- **No SEO fallback** — the static text the bot sees may differ from the animated text the user sees.
- **No accessible name on the typed line** — screen readers should hear the resolved text once, not character-by-character.

## Single highest-leverage fix
Rename to `Typewriter` and ship with `aria-live="off"` + a static fallback first string for SEO.

## Quick wins
1. Honor `prefers-reduced-motion: reduce` — show first string statically.
2. Loop / no-loop toggle.
3. Cursor character picker.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 4 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 4 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Renders a search input + submit button.

## Mobile-specific issues

- Input lacks `inputmode='search'` and `enterkeyhint='search'` — virtual keyboard shows generic 'return' instead of 'search'.
- Submit button is a 32px icon-only target — fails 44px minimum.
- No autosuggest dropdown on mobile — dropdown is off-screen positioned when shown.
- Input font-size < 16px triggers iOS auto-zoom on focus.

## Mobile quick wins

- Add `inputmode='search' enterkeyhint='search' autocomplete='off' font-size: 16px` to the input.
- Render submit button at 44 × 44px.
- Anchor autosuggest below the input with `position: absolute; left: 0; right: 0`.

## Single highest-leverage fix (mobile)

Add `inputmode='search' enterkeyhint='search' autocomplete='off' font-size: 16px` to the input.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Search

**Identity:** `Search` (site search input).
**Category:** Utility.

## Inventory
- Search input that queries the site and shows results.

## Working ✅
- Universal need; saves users from configuring Algolia/Lunr.

## Internal conflicts ⚠
- **Search backend not surfaced** — users don't know if it's MySQL LIKE, full-text, or something better.
- **No instant-search dropdown** by default — modern expectation.
- **No empty-state** on results page captured.
- Default placeholder is generic "Search…" — should reflect the site type ("Search posts", "Search products").

## Single highest-leverage fix
Add an instant-results dropdown so the user sees matches as they type.

## Quick wins
1. Site-type-aware placeholder (`Search posts and products…`).
2. Recent-searches row on focus.
3. ARIA: `role="search"` wrapper, `aria-label` on the input.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Blog/Posts/Testimonials/Team-card renders cards stacked on mobile.

## Mobile-specific issues

- Card thumbnails inherit desktop aspect — too tall on phone (eats 60% of viewport per card).
- Card meta (date / author / category) wraps to multiple lines.
- 'Read more' link is a small text-link, not a button — fails 44px tap rule.
- No skeleton/loading state during pagination on mobile.

## Mobile quick wins

- Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.
- Convert 'Read more' to a 44px button.
- Render skeletons when paginating.

## Single highest-leverage fix (mobile)

Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Team Card

**Identity:** `Team Card` (people grid).
**Category:** Content.

## Inventory
- Photo + name + role + bio + social links. Multiple cards in a grid.

## Working ✅
- Common "About us — meet the team" pattern; saves rolling-your-own.

## Internal conflicts ⚠
- **No role-vs-title distinction** — "Senior Developer" vs "Software Engineer" matter for SEO and clarity.
- **No structured `Person` JSON-LD** schema emit.
- **Default photo placeholders** are generic — should use coloured initials when no photo, not a "missing image" icon.
- No filter (e.g. "Engineering team" vs "Marketing team") for sites with 30+ members.

## Single highest-leverage fix
Emit `Person` JSON-LD per card and group by team filter.

## Quick wins
1. Coloured-initials placeholder when photo missing.
2. Optional pronouns field.
3. Hover-reveal extended bio.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Shop / Products grid reflows to 2 columns on mobile, then 1 on very narrow.

## Mobile-specific issues

- Product card image-aspect varies — cards have inconsistent height, breaking grid alignment on mobile.
- Add-to-cart button is below the fold on each card — user must scroll into the card before purchase action is visible.
- Filter sidebar (if shown) takes full width and pushes products below the fold on mobile.
- No 'sort' control surfaced on mobile — filtering is heavy, sort is missing.

## Mobile quick wins

- Force `aspect-ratio: 1/1` on product images for grid uniformity.
- Convert filter sidebar to a bottom-sheet drawer on < 768px.
- Add a top-bar Sort dropdown on mobile only.

## Single highest-leverage fix (mobile)

Force `aspect-ratio: 1/1` on product images for grid uniformity.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Products

**Identity:** `Products` (`data-type="shop/products"`).
**Category:** Ecommerce.
**See also:** `DESIGN_REPORT_PRODUCT_LIST_MODULE.md` — full audit (4 surfaces, 15 backlog items).

## Inventory (short)
- Inserts a product grid bound to the shop. Right-rail quick actions: `+ Add element`, `Products` (data source), `Category` (filter).
- Settings drawer is an iframe with `Items list / Settings / Design` tabs.
- "NEW PRODUCT" → opens Filament `Create Product` modal nested two iframes deep.

## Single highest-leverage fix (recap)
Kill the iframe-in-iframe-in-modal architecture; lift the Create-Product modal to the outer document.

## Top three quick wins (recap)
1. Default the products grid to paginated 12 items, not full DB dump (currently renders 12,142 px tall).
2. Rename body editor label `Write your post here` → `Product description` on the Create-Product modal.
3. Add Image / Price / Status columns to the Items list (currently TITLE only).

## Scorecard (recap)
**Journey average: 19 / 35.** See full report for per-surface breakdown.
## How this module behaves on a 390px phone

- Pages module renders a flat list of links.

## Mobile-specific issues

- Bullet markers render but list-items are 28px tall — small target.
- No section headers / grouping by parent page on mobile.
- Long page titles overflow without truncation.

## Mobile quick wins

- Render as a vertical list of cards (`min-height: 48px`) with chevron affordance.
- Add `text-overflow: ellipsis` after 2 lines.

## Single highest-leverage fix (mobile)

Render as a vertical list of cards (`min-height: 48px`) with chevron affordance.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Pages

**Identity:** `Pages` (list of site pages).
**Category:** Listing.

## Inventory
- Inserts a list/grid of pages. Filter by parent, tag, count.

## Working ✅
- Canonical pattern for "Our services" / "Locations" landing pages.

## Internal conflicts ⚠
- **Same unscoped page tree we saw in the post and product modals** — likely shows seeded gibberish on a fresh install.
- No layout preset (cards / list / minimal links).
- No exclusion filter ("hide this page from the list").
- No empty-state when there are no pages matching the filter.

## Single highest-leverage fix
Add a filter chip row above the inserted Pages module: `By tag · By parent · By date · Manual`.

## Quick wins
1. Hide unpublished pages by default.
2. Show the filter result count in the editor.
3. Templated empty-state message ("No pages match yet.").

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Form renders inputs stacked at full width on mobile.

## Mobile-specific issues

- Inputs lack semantic types (`type='email'`, `type='tel'`) and `autocomplete` attributes — no field-fill, wrong keyboard layout.
- No `inputmode` on numeric/email fields — wrong keyboard appears on iOS.
- Error messages render *above* the input on submit — pushed off-screen by the keyboard, the user does not see them.
- `<select>` falls back to native picker on iOS (good) but Android Chrome shows a tiny 12px dropdown that's hard to tap.
- Submit button height is < 44px on the seeded form templates.
- No `aria-live` region for form validation summary.

## Mobile quick wins

- Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).
- Render field-level errors *below* the input with `aria-describedby` and scroll-into-view.
- Add an `aria-live='polite'` summary at the top of the form.
- Bump submit `min-height: 48px`.

## Single highest-leverage fix (mobile)

Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Newsletter

**Identity:** `Newsletter` (email signup form).
**Category:** Form.

## Inventory
- Email input + submit. Settings: list/audience binding, success/error copy, double opt-in.

## Working ✅
- One of the highest-conversion forms on a marketing site — earning its slot in the picker.

## Internal conflicts ⚠
- **Spam protection unclear** — no captcha / honeypot field surfaced as a default.
- **GDPR consent checkbox** not surfaced as a default — required in many markets.
- **No success-state preview** in the editor — the user only sees the empty form.
- Provider integration (Mailchimp, MailerLite, Brevo) is opaque from the canvas.

## Single highest-leverage fix
Default a GDPR consent checkbox + honeypot field; hide them on US-only sites if explicitly toggled off.

## Quick wins
1. Inline preview for "Thanks for subscribing!" success state.
2. Show the bound provider as a chip on the canvas (`Mailchimp →`).
3. Privacy-policy link slot under the field.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Multiple-columns reflow to a single stacked column at < 768px (Bootstrap breakpoint).

## Mobile-specific issues

- Stacking order is DOM order — designer cannot reorder for mobile (no `order:` controls).
- Column gaps remain ~24px between stacked items, which feels wide on a 390px screen.
- Some seeded layouts use `col-md-*` which collapse only at 768px — too late; the seeded layout wraps at 360px-440px and looks broken in landscape.

## Mobile quick wins

- Add a per-column 'Mobile order' input.
- Use `gap: clamp(.5rem, 4vw, 1.5rem)` to scale gaps with viewport.
- Audit Bootstrap classes; prefer `col-12 col-sm-*` to break sooner.

## Single highest-leverage fix (mobile)

Add a per-column 'Mobile order' input.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Multiple Columns

**Identity:** `Multiple Columns` (column container; `data-type="layouts"` family).
**Category:** Layout.

## Inventory
- Inserts a flex/grid row that can host other modules side-by-side.
- Default column count: usually 2 or 3 depending on template.

## Working ✅
- Necessary primitive for any two-column layout. Keep.
- Drag-and-drop modules into each column.

## Internal conflicts ⚠
- **No mobile-stacking behaviour preview.** Editor shows the desktop layout; mobile is a guess until VIEW.
- **No gap / gutter control** on the canvas — must descend into Element Style Editor.
- Adding a 4th column on a 3-column row collapses awkwardly without a clear visual rule.

## Single highest-leverage fix
Show a "Mobile" toggle on the module's right rail that simulates how columns stack on small viewports.

## Quick wins
1. Surface column-count chips (`1 · 2 · 3 · 4 · 6 · 12`) above the row.
2. Visible gap handle drag.
3. "Equalise heights" one-click toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Multilanguage renders as a small flag/code selector.

## Mobile-specific issues

- Selector is 24px wide — far below tap minimum.
- Drop-down list anchors to top-right — on mobile the menu spills off-screen on the right.
- Language list scrolls inside a 200px box — selected language is hard to confirm visually after pick.

## Mobile quick wins

- Render as a 44 × 44 button that opens a bottom sheet with the language list on mobile.

## Single highest-leverage fix (mobile)

Render as a 44 × 44 button that opens a bottom sheet with the language list on mobile.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Multilanguage

**Identity:** `Multilanguage` (language picker).
**Category:** Site.

## Inventory
- Dropdown / list of available site languages, switching the user's locale.

## Working ✅
- Critical for any non-English-only site; surfacing it as a module is correct.

## Internal conflicts ⚠
- **Naming**: `Multilanguage` is a CMS-internal compound. Users say "Language" or "Language switcher".
- **Locale codes vs flags**: flags identify countries, not languages. `🇺🇸 English` excludes other English-speaking countries.
- No `hreflang` tag emit confirmed.
- Selected language not visually distinct in default styling.

## Single highest-leverage fix
Rename to `Language switcher`. Default to language names without flags; flags as an optional toggle.

## Quick wins
1. Emit `hreflang` alternates in `<head>`.
2. Persist user's choice in cookie + URL.
3. ARIA: `aria-label="Choose language"`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Menu collapses to a hamburger on mobile (template hamburger; module renders the link list source).

## Mobile-specific issues

- Menu items render as inline-block on the seeded template even at 390px — they wrap and overlap with the logo when 5+ items.
- Submenu hover-trigger does not work on touch — first tap opens, second tap navigates is not implemented.
- Off-canvas hamburger drawer (template-level) does not trap focus when open.
- Active page highlight uses `:hover` color — invisible on touch since :hover sticks unpredictably on iOS.

## Mobile quick wins

- Convert menu module to use `<nav>` + a true off-canvas drawer at < 768px.
- Add tap-to-open submenu pattern (first tap = open, second = navigate, scrim closes on outside tap).
- Trap focus inside the drawer when open and `inert` the rest of the page.

## Single highest-leverage fix (mobile)

Convert menu module to use `<nav>` + a true off-canvas drawer at < 768px.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Menu

**Identity:** `Menu` (`data-type="menu"`).
**Category:** Navigation.

## Inventory
- Inserts a navigation menu bound to a Microweber Menu (Header Menu / Footer Menu / etc.).
- Settings: which menu, orientation, mobile breakpoint.

## Working ✅
- Bound to a real Menu entity — single source of truth.
- Live-updates when the bound menu changes.

## Internal conflicts ⚠
- **Mobile menu (hamburger) styling** is template-controlled, opaque to the user.
- No mega-menu support visible.
- Active-state styling not surfaced as a control — users have to know which CSS class to override.
- "Menu" appears twice in the system — as a module here and as a separate admin entity (the Menus database). The relationship is implicit.

## Single highest-leverage fix
Show the bound Menu name (`Header Menu`) and a `Edit menu →` link inside the canvas selection.

## Quick wins
1. Mega-menu boolean toggle.
2. Active-link colour control surfaced on the canvas.
3. "Sticky on scroll" toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Animation runs on the main thread (CSS keyframes / requestAnimationFrame).
- On low-end Android the typing/marquee animation drops below 30fps in dev tools mobile profile.

## Mobile-specific issues

- No `prefers-reduced-motion: reduce` guard — accessibility regression on iOS users with the OS-level setting on.
- Marquee uses `translateX` looping — pauses are not respected when tab is backgrounded (battery cost).
- Texttype (typewriter) flashes content during hydration before the JS attaches — Cumulative Layout Shift on mobile is ~0.05+.

## Mobile quick wins

- Wrap animation styles in `@media (prefers-reduced-motion: no-preference)`.
- Use IntersectionObserver to pause animations off-screen.
- Reserve space with `min-height` to eliminate CLS during JS hydration.

## Single highest-leverage fix (mobile)

Wrap animation styles in `@media (prefers-reduced-motion: no-preference)`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Marquee

**Identity:** `Marquee` (horizontally scrolling text/logos).
**Category:** Visual.

## Inventory
- Continuously scrolling row of text or images. Direction, speed, pause-on-hover.

## Working ✅
- Stylish "as seen in" / partner-logo strip when used sparingly.

## Internal conflicts ⚠
- **`prefers-reduced-motion` not respected** — confirm the module pauses for users who request reduced motion.
- **Auto-rotating motion** is a Drunk-Designer anti-pattern in carousel form; marquee is its cousin.
- No accessibility name for the strip.
- Speed slider is template-specific; defaults vary.

## Single highest-leverage fix
Honor `prefers-reduced-motion: reduce` — pause the marquee when the OS preference is set.

## Quick wins
1. Pause-on-hover by default.
2. `aria-label="Featured partners"` style accessible name.
3. Provide a static fallback layout for print / RSS.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## How this module behaves on a 390px phone

- Logo image scales with `max-height` from template.

## Mobile-specific issues

- Logo `max-height` is 40-60px on mobile — fine, but the wordmark next to the logo is template-level and overflows on long brand names at 390px.
- No `srcset` / @2x asset for retina mobile — visible blurriness on phone screens.

## Mobile quick wins

- Emit `srcset` with a 1x and 2x asset and `sizes='40px'`.
- Truncate brand wordmark with `text-overflow: ellipsis` at < 360px.

## Single highest-leverage fix (mobile)

Emit `srcset` with a 1x and 2x asset and `sizes='40px'`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Logo

**Identity:** `Logo` (`data-type="logo"` — also auto-mounted in headers).
**Category:** Branding.

## Inventory
- Inserts the site logo (image + optional text).

## Working ✅
- Pulls from the site-wide logo configured in Template Customization → Branding. Single source of truth.

## Internal conflicts ⚠
- **Two ways to set a logo** — Template Customization Branding section and per-module override. Unclear which wins.
- **No dark/light variant** — sites with dark headers and light footers need both.
- **No SVG-vs-PNG hint** — SVG logos are crisper and lighter; users get no nudge.

## Single highest-leverage fix
Surface "Logo (light)" and "Logo (dark)" both, auto-pick by surrounding background.

## Quick wins
1. Show the resolved size in the canvas tooltip.
2. Lazy-load only off-screen instances (the header logo should never lazy-load).
3. Encourage SVG with a short tooltip.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Renders empty/background containers; relies on outer column module for width.

## Mobile-specific issues

- Background module's image fills container but is fixed at desktop res — wastes mobile data.
- Empty module has zero height and no editor outline — invisible target on touch.
- No overlay-color picker on mobile editor (off-canvas right rail bug).

## Mobile quick wins

- Provide responsive background `image-set()` declarations.
- Render an editor-only dashed border on Empty modules in live edit mode.

## Single highest-leverage fix (mobile)

Provide responsive background `image-set()` declarations.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Layout Content

**Identity:** `Layout Content` (`data-type="layouts"`-family wrapper).
**Category:** Content.

## Inventory
- Wraps a layout template's main content slot. Users probably encounter this when editing pages built from a template.

## Working ✅
- Necessary plumbing: marks where a template's main body lives so children layouts can be inserted.

## Internal conflicts ⚠
- **Name is jargon.** "Layout Content" reads as a developer term. Users want "Page body" or "Main content".
- **Overlaps with `Content` and `Text`** in the picker — three modules occupying the same mental slot.
- **Often inserted automatically** by template structure; surfacing it in the user-facing picker is confusing.

## Single highest-leverage fix
Hide `Layout Content` from the user-facing picker — keep it as an internal template slot only.

## Quick wins
1. If kept, rename to `Page body` and add a description.
2. Make the slot's boundary visible only on hover so it doesn't compete visually with the user's content.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 9 / 30 — second strongest deletion candidate after `Empty`.**
## How this module behaves on a 390px phone

- Inline-table renders a `<table>` with native HTML semantics.

## Mobile-specific issues

- Wide tables overflow without horizontal-scroll wrapper — content is clipped on narrow screens.
- No `<caption>` and no `scope` on `<th>` — screen reader users get unstructured cell read-out.
- On mobile the table's column-widths collapse such that wrapping makes rows different heights.

## Mobile quick wins

- Wrap the table in `<div style='overflow-x: auto'>` and set `min-width: max-content` on the table.
- Emit `scope='col'`/`scope='row'` and a meaningful `<caption>` field in the editor.
- Optional: 'card-row' rendering on < 480px (each row stacks as a card).

## Single highest-leverage fix (mobile)

Wrap the table in `<div style='overflow-x: auto'>` and set `min-width: max-content` on the table.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Inline Table

**Identity:** `Inline Table` (HTML `<table>` editor).
**Category:** Content.

## Inventory
- Click → grid editor opens (rows × cols picker, then editable cells).
- Likely supports header row, alignment per cell.

## Working ✅
- Tables are still the right answer for tabular data; having a real one in the picker beats fake-tables made of columns.

## Internal conflicts ⚠
- **Tables for tabular data only.** No warning prevents users from using a table for layout (a 2010-era anti-pattern).
- **No responsive strategy** — wide tables horizontally scroll on mobile by default. Should at least wrap in `.overflow-x-auto`.
- **No row-header vs column-header distinction** for screen readers.
- **No CSV/Excel paste import.**

## Single highest-leverage fix
Add a CSV / TSV / Excel paste-import in the create flow.

## Quick wins
1. Always wrap in `<div class="table-responsive">`.
2. Mark the first row as `<th scope="col">` by default.
3. Add a "Striped / Bordered / Compact" preset row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Single image scales with `max-width: 100%` and centers — picture/pictures grids stack to 1 column at < 480px.
- Image-rollover swaps `src` on hover — on mobile there is no hover, the rollover never triggers.

## Mobile-specific issues

- **No `srcset`/`sizes` emitted** for inserted images in the module — every visitor downloads the desktop-sized asset on a 4G phone.
- Image-rollover's hover-only affordance means the secondary state is invisible on touch devices — a mobile-blocking UX pattern.
- No native `loading='lazy'` attribute on inserted images — initial paint blocks while above-the-fold images are decoded.
- Galleries (pictures) lack a swipeable lightbox — taps open the raw file URL in a new tab on iOS.

## Mobile quick wins

- Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.
- Convert hover-only rollover to a tap-toggle on touch devices.
- Add `loading='lazy'` and `decoding='async'` to inserted media.
- Wrap gallery items in a touch-aware lightbox (swipe to dismiss).

## Single highest-leverage fix (mobile)

Emit `srcset` with 320w/640w/960w/1280w breakpoints from the media library when the picture/pictures module renders.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Image Rollover

**Identity:** `Image Rollover` (hover-swap image).
**Category:** Media.

## Inventory
- Two images: default + hover-state. On mouse-over, the second image is shown.

## Working ✅
- Niche but useful for product variants, before/after-style teasers.

## Internal conflicts ⚠
- **Hover-only affordance** — Drunk-Designer anti-pattern. Touch devices have no hover.
- **No tap-to-flip** for mobile.
- **No accessibility name** on the rolled-over image.
- Overlaps with `BeforeAfter` for some use cases.

## Single highest-leverage fix
On touch devices, swap on tap (or at least show the second image briefly) so the feature works for everyone.

## Quick wins
1. `aria-label` describing the rollover.
2. Optional caption that swaps with the image.
3. Provide a Crossfade transition style alongside the default instant swap.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Renders a horizontal row of icon links (social-links / sharer / icon).

## Mobile-specific issues

- Icons are 24-32px on the seeded template — below 44px tap-target minimum, and wrap awkwardly when more than 5 icons are present at 390px.
- Sharer copies the URL to share on desktop but does not call the Web Share API on mobile (`navigator.share`) — wasted native UX.
- Icon-only links lack `aria-label` half the time — confirmed via DOM.

## Mobile quick wins

- Bump icon hit area to 44 × 44 with `padding`.
- Detect `navigator.share` and prefer it on mobile.
- Audit `aria-label` on every icon-link emit.

## Single highest-leverage fix (mobile)

Bump icon hit area to 44 × 44 with `padding`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Icon

**Identity:** `Icon` (single icon glyph).
**Category:** Visual.

## Inventory
- Inserts an icon. Likely backed by Material/MDI or a custom set.
- Settings: icon name (search), size, colour.

## Working ✅
- Useful for visual hierarchy without image weight.

## Internal conflicts ⚠
- **No icon-pack disclosure.** Users don't know if they're searching MDI, FontAwesome, or a custom set.
- **No accessibility label prompt.** Decorative icons should mark `aria-hidden`; meaningful icons need a label.
- **No SVG inlining vs icon-font choice.** Icon fonts blow up on screen-readers; SVG with `<title>` is the modern path.

## Single highest-leverage fix
Always emit SVG with `<title>` (or `aria-hidden="true"` if marked decorative).

## Quick wins
1. Search by category (Arrow / Brand / UI / Communication…).
2. Recently used row.
3. Bulk replace ("change all chevrons in this layout").

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Code blocks render with `<pre><code>` and inherit horizontal-scroll from template stylesheet.
- Syntax highlighting palette is template-controlled — readable in light mode, low contrast on dark.

## Mobile-specific issues

- Long lines force horizontal scroll inside a vertically-scrolling page — two-axis scroll is a known mobile foot-gun.
- No copy-to-clipboard button visible at < 768px — the icon overlaps line numbers on mobile.
- Line-numbers gutter is too narrow (no padding-left), letters touch the gutter on iPhone.

## Mobile quick wins

- Add `overflow-x: auto` and `-webkit-overflow-scrolling: touch` plus a soft right gradient hint.
- Render the copy button as a fixed top-right button on `.is-mobile` viewports.
- Use `tabular-nums` for line numbers and add `padding-inline-end: .5em`.

## Single highest-leverage fix (mobile)

Add `overflow-x: auto` and `-webkit-overflow-scrolling: touch` plus a soft right gradient hint.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — HighlightCode

**Identity:** `HighlightCode` (syntax-highlighted code block — CamelCase brand naming).
**Category:** Content.

## Inventory
- Pasted code with language detection / picker, syntax highlighting via highlight.js or Prism.

## Working ✅
- Specific tool for the job; better than dropping `<pre><code>` by hand.

## Internal conflicts ⚠
- **CamelCase brand name** `HighlightCode` reads as an internal identifier. Rename to `Code Block`.
- **No copy-to-clipboard button** by default.
- **Language list** likely huge — needs search/recently-used.
- **No dark/light theme toggle** that respects site theme.

## Single highest-leverage fix
Rename to `Code Block` and ship with a copy-to-clipboard button enabled by default.

## Quick wins
1. Auto-detect language from the pasted snippet, with explicit override.
2. Line numbers toggle.
3. Honor site theme (dark code on dark theme, light on light).

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 12 / 30**
## How this module behaves on a 390px phone

- Google Maps iframe loads default zoom/pan controls.

## Mobile-specific issues

- Two-finger-pan-to-move is not enabled by default — single-finger drag intercepts page scroll, the user gets stuck in the map.
- Map height is fixed at 450px or 600px — eats most of the mobile viewport.
- API key is not always present in the inserted embed — mobile shows the 'For development purposes only' watermark.

## Mobile quick wins

- Set `gestureHandling='cooperative'` so the map only pans with two-finger gesture or after a tap.
- Use `aspect-ratio: 16/9` and `max-height: 50vh` to prevent map from dominating the screen.

## Single highest-leverage fix (mobile)

Set `gestureHandling='cooperative'` so the map only pans with two-finger gesture or after a tap.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Google Maps

**Identity:** `Google Maps` (embedded map).
**Category:** Embed.

## Inventory
- Inserts a Google Maps iframe at a chosen address / lat-long / pin.

## Working ✅
- One-click map for contact pages — common need.

## Internal conflicts ⚠
- **GDPR / consent**: Google Maps loads tracking. No consent gate visible.
- **No alternative**: OpenStreetMap / Leaflet is privacy-respectful; not offered.
- **API-key dependency**: if no key configured, the map silently shows a degraded state — ship a clear CTA to set the key in Settings.
- **No multiple-pin support** for "our locations" pages.

## Single highest-leverage fix
Add an OpenStreetMap fallback option with no API key required.

## Quick wins
1. Show "Add Google Maps API key →" link inside the module's settings if missing.
2. Lazy-load the iframe until visible.
3. Add a "lite" static-image preview that hydrates on hover.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Accordion/Tabs/FAQ render with click-to-expand panels; smooth height animation.

## Mobile-specific issues

- Tab labels overflow horizontally on mobile — no `overflow-x: auto` with snap, they wrap into multiple rows that distort the strip.
- Tap target heights are 36-40px — below 44px iOS HIG minimum.
- Accordion icon (chevron) does not flip with `aria-expanded` change — visual state confused.
- FAQ does not use `<details>/<summary>` — loses native browser semantics, search, and accessibility.

## Mobile quick wins

- Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.
- Set tab strip to `overflow-x: auto; scroll-snap-type: x mandatory` with snap points.
- Bump tap target min-height to 48px.

## Single highest-leverage fix (mobile)

Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Faq

**Identity:** `Faq` (frequently-asked-questions block).
**Category:** Content.

## Inventory
- A specialised Accordion: question + answer pairs, intended for SEO-rich FAQ pages.

## Working ✅
- Distinct from Accordion *if* it emits FAQPage JSON-LD; otherwise redundant.

## Internal conflicts ⚠
- **Mixed-case casing**: `Faq` (rather than `FAQ`) breaks the picker's ad-hoc convention. Acronyms should be uppercase.
- **Overlap with Accordion**: see `accordion.md`.
- No structured-data emit confirmed.
- No "search this FAQ" toggle for long lists.

## Single highest-leverage fix
Rename to `FAQ` (uppercase) and confirm `FAQPage` JSON-LD emission.

## Quick wins
1. Search-this-FAQ field toggle.
2. Anchor links per question for shareable URLs.
3. "Was this helpful?" mini-feedback per item.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Embeds render the third-party iframe at the inserted size.
- No fallback for users who block third-party iframes via tracking protection.

## Mobile-specific issues

- Fixed pixel widths/heights overflow the 375px content area on the seeded template.
- No `loading='lazy'` on the iframe — third-party JS executes during initial page load on mobile.
- No CSP `sandbox` attribute on inserted iframes — embedded scripts have full origin access.
- Many embeds (Twitter, Facebook) show 'Couldn't load' on mobile in tracker-blocked browsers (Safari ITP, Brave).

## Mobile quick wins

- Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.
- Add `loading='lazy'` and `referrerpolicy='no-referrer-when-downgrade'`.
- Add a noscript/no-iframe placeholder linking out to the source URL.

## Single highest-leverage fix (mobile)

Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Facebook Page

**Identity:** `Facebook Page` (Facebook Page plugin).
**Category:** Social.

## Inventory
- Embeds a Facebook Page's preview card with timeline / events / cover image.

## Working ✅
- One-line embed for "follow us on Facebook" sections.

## Internal conflicts ⚠
- Same privacy concerns as `Facebook Like`.
- **Two separate modules for the same vendor** — combine.
- **Likely deprecated by Meta**: the Page plugin's design and tracking model has changed several times; long-term reliability is uncertain.

## Single highest-leverage fix
Merge `Facebook Like` + `Facebook Page` into a single `Facebook` module with a `Like / Page / Post` mode toggle.

## Quick wins
1. Privacy-respecting deferred load.
2. Show fallback link when blocked.
3. Consider a generic `Social embed` module that handles Facebook, X, Instagram, LinkedIn.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 2 |
| Distinctiveness | 1 |
**Total: 9 / 30 — merge target.**
## How this module behaves on a 390px phone

- Embeds render the third-party iframe at the inserted size.
- No fallback for users who block third-party iframes via tracking protection.

## Mobile-specific issues

- Fixed pixel widths/heights overflow the 375px content area on the seeded template.
- No `loading='lazy'` on the iframe — third-party JS executes during initial page load on mobile.
- No CSP `sandbox` attribute on inserted iframes — embedded scripts have full origin access.
- Many embeds (Twitter, Facebook) show 'Couldn't load' on mobile in tracker-blocked browsers (Safari ITP, Brave).

## Mobile quick wins

- Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.
- Add `loading='lazy'` and `referrerpolicy='no-referrer-when-downgrade'`.
- Add a noscript/no-iframe placeholder linking out to the source URL.

## Single highest-leverage fix (mobile)

Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Facebook Like

**Identity:** `Facebook Like` (Facebook Like button widget).
**Category:** Social.

## Inventory
- Embeds Facebook's Like button.

## Working ✅
- Single-purpose social widget.

## Internal conflicts ⚠
- **GDPR / privacy**: loads Facebook tracking. No consent gate.
- **Single-platform bet**: Facebook is one of many; why not Twitter/X, LinkedIn?
- **Dated**: 2010s pattern; Like buttons rarely used in 2025+.
- **No fallback** when Facebook is blocked by the user (China, ad-blockers).

## Single highest-leverage fix
Defer-load behind a one-click "Show Facebook widget" placeholder until the user opts in.

## Quick wins
1. Combine with `Sharer` / `TweetEmbed` into a single `Social embed` module.
2. Show the button in a privacy-respecting "lite" mode that hydrates on click.
3. Hide on cookie-rejection.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 2 |
| Scalability | 1 |
| Distinctiveness | 1 |
**Total: 10 / 30 — strong consolidation candidate.**
## How this module behaves on a 390px phone

- Renders empty/background containers; relies on outer column module for width.

## Mobile-specific issues

- Background module's image fills container but is fixed at desktop res — wastes mobile data.
- Empty module has zero height and no editor outline — invisible target on touch.
- No overlay-color picker on mobile editor (off-canvas right rail bug).

## Mobile quick wins

- Provide responsive background `image-set()` declarations.
- Render an editor-only dashed border on Empty modules in live edit mode.

## Single highest-leverage fix (mobile)

Provide responsive background `image-set()` declarations.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Empty

**Identity:** `Empty` (empty container).
**Category:** Layout (developer-leak).

## Inventory
- Inserts an empty `<div>` placeholder. Useful for spacing or as a drop-target.

## Working ✅
- A pure spacer/container has its place — but **see Internal conflicts**.

## Internal conflicts ⚠
- **`Empty` is a developer concept exposed to users.** Most users will never knowingly need an empty container — they need a Spacer or a Multiple Columns row.
- The name itself is a poor marketing pitch ("Insert: Empty"). Users will skip past it; it pollutes the picker.
- **Redundant with `Spacer`** — both are silent layout helpers.

## Single highest-leverage fix
Remove `Empty` from the user-facing picker. Keep it as an internal admin-only tool if developers need it.

## Quick wins
1. If kept, rename to `Container` and add a hover description: "An empty row to drop modules into".
2. Auto-collapse when empty in published view.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 9 / 30 — strongest candidate to delete from the library.**
## How this module behaves on a 390px phone

- Embeds render the third-party iframe at the inserted size.
- No fallback for users who block third-party iframes via tracking protection.

## Mobile-specific issues

- Fixed pixel widths/heights overflow the 375px content area on the seeded template.
- No `loading='lazy'` on the iframe — third-party JS executes during initial page load on mobile.
- No CSP `sandbox` attribute on inserted iframes — embedded scripts have full origin access.
- Many embeds (Twitter, Facebook) show 'Couldn't load' on mobile in tracker-blocked browsers (Safari ITP, Brave).

## Mobile quick wins

- Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.
- Add `loading='lazy'` and `referrerpolicy='no-referrer-when-downgrade'`.
- Add a noscript/no-iframe placeholder linking out to the source URL.

## Single highest-leverage fix (mobile)

Wrap the iframe in a responsive container with `aspect-ratio` and `width: 100%`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Embed

**Identity:** `Embed` (generic HTML/iframe embed).
**Category:** Embed.

## Inventory
- Drop in raw HTML / iframe / script. Power-user escape hatch.

## Working ✅
- Necessary for one-off third-party widgets (Calendly, Stripe, custom forms).

## Internal conflicts ⚠
- **Security**: arbitrary HTML/JS opens XSS surface for non-admin users. Confirm RBAC: only admins can edit Embed.
- No sandbox preview — pasted code may break the page in prod, not in the editor.
- No allow-list of known providers (would convert pasted iframe URLs to safer privacy-respecting variants).
- No "set width / height / aspect-ratio" wrapper.

## Single highest-leverage fix
Wrap pasted iframes in a responsive `aspect-ratio: 16/9` container by default and surface a chip to override.

## Quick wins
1. RBAC check: only admins can insert Embed.
2. Preview pane below the textarea showing the rendered embed.
3. Strip `<script>` for non-admin roles.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Body copy reflows to a single column — line-length is acceptable on 390px (~70-80 chars max).
- Default paragraph font size renders at 16px in the seeded site, which clears iOS auto-zoom thresholds — good.
- Headings rely on template scale; on Big2 the H1 can collapse to body-size weight at small viewports.

## Mobile-specific issues

- No explicit `prose` max-width in the rendered module — when the user widens the column, lines exceed 75ch on landscape orientations.
- Inline-edit caret on a heading triggers iOS keyboard which obscures the bottom half — no `scrollIntoView` on focus.
- No reading-time hint or anchor links auto-generated for headings.

## Mobile quick wins

- Add `--max-content-measure: 65ch` to the module wrapper.
- Add a `keyup` listener on contenteditable that calls `selection.scrollIntoViewIfNeeded()` when keyboard is open.

## Single highest-leverage fix (mobile)

Add `--max-content-measure: 65ch` to the module wrapper.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Content

**Identity:** `Content` (rich-text block — likely overlaps with `Text`).
**Category:** Content.

## Inventory
- Multi-paragraph rich content. May include shortcuts to insert nested modules inline.

## Working ✅
- The "main canvas" of any page-like surface — necessary primitive.

## Internal conflicts ⚠
- **`Content` vs `Text` vs `Layout Content` is a three-way confusion.** The picker exposes all three with no description. Users will click and find out by trial.
- Rich-text inline toolbar duplicates the post body editor's toolbar — same Link Picker issues apply.
- No content-template starts ("Article", "Press release", "Product description").

## Single highest-leverage fix
Disambiguate `Text` (single block) vs `Content` (long-form rich body) vs `Layout Content` (template-driven shell). Pick two; deprecate one.

## Quick wins
1. One-line description on hover for each.
2. Word-count + reading-time badge.
3. Outline-view sidebar showing all H1–H4 inside the Content block.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 12 / 30**
## How this module behaves on a 390px phone

- Form renders inputs stacked at full width on mobile.

## Mobile-specific issues

- Inputs lack semantic types (`type='email'`, `type='tel'`) and `autocomplete` attributes — no field-fill, wrong keyboard layout.
- No `inputmode` on numeric/email fields — wrong keyboard appears on iOS.
- Error messages render *above* the input on submit — pushed off-screen by the keyboard, the user does not see them.
- `<select>` falls back to native picker on iOS (good) but Android Chrome shows a tiny 12px dropdown that's hard to tap.
- Submit button height is < 44px on the seeded form templates.
- No `aria-live` region for form validation summary.

## Mobile quick wins

- Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).
- Render field-level errors *below* the input with `aria-describedby` and scroll-into-view.
- Add an `aria-live='polite'` summary at the top of the form.
- Bump submit `min-height: 48px`.

## Single highest-leverage fix (mobile)

Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Contact Form

**Identity:** `Contact Form` (Name + Email + Message form).
**Category:** Form.

## Inventory
- Standard contact form fields. Submission target: site admin email and/or CRM.

## Working ✅
- Universal need; saves users from configuring a form builder.

## Internal conflicts ⚠
- **Spam protection unclear** — same critique as Newsletter.
- **Double-submit prevention** absent on click.
- **No success / error inline state** preview in the editor.
- **No file-attachment toggle** for "send me your CV" use cases.

## Single highest-leverage fix
Default-on honeypot + rate-limiting; surface "spam protection: ✓" badge in the canvas.

## Quick wins
1. Configurable required-field set (Name optional, Phone optional).
2. Confirmation auto-reply email toggle.
3. Inline validation as the user types.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Form renders inputs stacked at full width on mobile.

## Mobile-specific issues

- Inputs lack semantic types (`type='email'`, `type='tel'`) and `autocomplete` attributes — no field-fill, wrong keyboard layout.
- No `inputmode` on numeric/email fields — wrong keyboard appears on iOS.
- Error messages render *above* the input on submit — pushed off-screen by the keyboard, the user does not see them.
- `<select>` falls back to native picker on iOS (good) but Android Chrome shows a tiny 12px dropdown that's hard to tap.
- Submit button height is < 44px on the seeded form templates.
- No `aria-live` region for form validation summary.

## Mobile quick wins

- Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).
- Render field-level errors *below* the input with `aria-describedby` and scroll-into-view.
- Add an `aria-live='polite'` summary at the top of the form.
- Bump submit `min-height: 48px`.

## Single highest-leverage fix (mobile)

Map field name → semantic input type + autocomplete (`email`, `tel`, `name`, `street-address`...).

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Comments

**Identity:** `Comments` (post/product comments thread).
**Category:** Engagement.

## Inventory
- Comment list + comment form. May integrate with Disqus or be self-hosted.

## Working ✅
- Real comments engagement loop — earning the slot.

## Internal conflicts ⚠
- **Spam protection** unclear from the canvas.
- **Moderation surface** not surfaced — admins should be able to moderate inline.
- **No Disqus / Hyvor / native toggle** from the canvas; users may not know what they're getting.
- **No threaded replies** confirmed.

## Single highest-leverage fix
Surface the comment-engine choice (Native / Disqus / Hyvor / Off) as a chip on the canvas selection.

## Quick wins
1. Honeypot + rate-limiting by default.
2. Inline moderation actions for admins.
3. Mention/notify support.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Tags / category renders chips inline.

## Mobile-specific issues

- Chips wrap into 4-5 rows on a 390px viewport when post has many tags — eats vertical space.
- Chips are 24px tall — fails tap-target rule.
- No mobile pattern (horizontal scroll with snap is more natural here than wrapping).

## Mobile quick wins

- Switch to `overflow-x: auto; scroll-snap-type: x mandatory` chip strip on < 768px.
- Bump chip height to 32-36px with `padding-block: 0.4rem`.

## Single highest-leverage fix (mobile)

Switch to `overflow-x: auto; scroll-snap-type: x mandatory` chip strip on < 768px.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Category

**Identity:** `Category` (categories listing).
**Category:** Listing.

## Inventory
- Lists the site's categories (post categories, product categories, or both).

## Working ✅
- Useful for shop sidebars and blog landing pages.

## Internal conflicts ⚠
- **Scope ambiguity**: posts vs products vs all. The picker doesn't say.
- Default render is a flat list — categories often have hierarchy (parent/child) that should be visualised.
- No empty-state UX.

## Single highest-leverage fix
Add a scope chip on insert: `Posts · Products · All`.

## Quick wins
1. Render hierarchy by default; flat-list as a toggle.
2. Item counts per category (`Pricing (3)`).
3. Active-category highlighting when listed inside a category landing page.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Buttons render with template-aware default styles.
- Add-to-cart inherits product-page button rules.

## Mobile-specific issues

- Default button height is 36-40px on the seeded site — below 44px iOS HIG min for touch.
- Inline-edit on the button label is fiddly on mobile — long-press selects the parent, not the text.
- No loading/disabled state on add-to-cart — double-taps fire two add events on slow 3G.
- Link-picker modal (used by Button) renders fine on mobile but is not full-height — there's wasted dead-zone above the keyboard.

## Mobile quick wins

- Set `min-height: 44px; padding-block: .65rem` on all CTAs.
- Switch button to `aria-busy='true'` and disable for 1s after add-to-cart click.
- Bottom-sheet variant of Link Picker on mobile.

## Single highest-leverage fix (mobile)

Set `min-height: 44px; padding-block: .65rem` on all CTAs.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Button

**Identity:** `Button` (`data-type="btn"`).
**Category:** Action.

## Inventory
- Inserts a styled CTA. Inline label edit. Settings: link target (uses Link Picker modal — see `DESIGN_REPORT_LINK_PICKER_MODAL.md`), style preset, size.

## Working ✅
- Default style is template-aware (orange CTA on the seeded site, capsule pill on Big2).
- Inline label edit feels natural.

## Internal conflicts ⚠
- **No primary/secondary/ghost preset row** in the canvas — must descend into Element Style Editor.
- **Link picker is the same minimal modal flagged elsewhere** — accepts garbage URLs, no internal-page search.
- **No icon-prefix / suffix** baked in (most modern CTAs have an arrow).
- **No loading state** for buttons that submit.

## Single highest-leverage fix
Add inline preset chips (`Primary · Secondary · Ghost · Outline · Link`) above the button on selection.

## Quick wins
1. Append-icon picker (arrow, plus, external-link).
2. Built-in `target="_blank"` + `rel="noopener noreferrer"` when external.
3. Hover-state preview toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## How this module behaves on a 390px phone

- Breadcrumb renders as inline `<a>` separated by `>`.

## Mobile-specific issues

- Long breadcrumb chains overflow horizontally with no truncation — on a 4-level path the breadcrumb pushes beyond the viewport.
- Tap targets are inline text with 32px row height — below 44px.
- No mobile pattern (e.g. collapsing middle items to '…').

## Mobile quick wins

- Apply `overflow-x: auto; white-space: nowrap` and a soft right-gradient hint, OR collapse middle items to `…` on < 480px.
- Wrap each link with `padding-block: .75rem`.

## Single highest-leverage fix (mobile)

Apply `overflow-x: auto; white-space: nowrap` and a soft right-gradient hint, OR collapse middle items to `…` on < 480px.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Breadcrumb

**Identity:** `Breadcrumb` (Home › Section › Page trail).
**Category:** Navigation.

## Inventory
- Auto-generated trail of the current page's ancestry.

## Working ✅
- Resolved from page tree automatically — no manual maintenance.
- SEO-relevant when wired to BreadcrumbList JSON-LD.

## Internal conflicts ⚠
- **JSON-LD emit not confirmed.** SEO miss if absent.
- **Separator character** is not customisable from the canvas (›, /, →).
- **Truncation strategy** absent: deep trees overflow on mobile.
- Hidden on home page is a default — confirm.

## Single highest-leverage fix
Emit `BreadcrumbList` JSON-LD whenever this module renders.

## Quick wins
1. Separator picker chip.
2. Mobile truncation: `Home › … › Current`.
3. `aria-label="Breadcrumb"` on the wrapper `<nav>`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 4 |
| Emotion | 2 |
| Usability | 4 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## How this module behaves on a 390px phone

- Blog/Posts/Testimonials/Team-card renders cards stacked on mobile.

## Mobile-specific issues

- Card thumbnails inherit desktop aspect — too tall on phone (eats 60% of viewport per card).
- Card meta (date / author / category) wraps to multiple lines.
- 'Read more' link is a small text-link, not a button — fails 44px tap rule.
- No skeleton/loading state during pagination on mobile.

## Mobile quick wins

- Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.
- Convert 'Read more' to a 44px button.
- Render skeletons when paginating.

## Single highest-leverage fix (mobile)

Set thumbnail `aspect-ratio: 16/9` and `object-fit: cover`.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Blog

**Identity:** `Blog` (blog listing / archive).
**Category:** Listing.

## Inventory
- Inserts a blog landing — list of posts with archive controls (year/month, category).

## Working ✅
- Distinct from `Posts` *if* it bundles archive controls; otherwise duplicate.

## Internal conflicts ⚠
- **Overlap with `Posts` module.** Two paths to the same goal.
- Big2 template is missing the `Blog` layout-picker entry — see `DESIGN_REPORT_TEMPLATE_BIG2.md`.
- No RSS auto-generation hint.
- No author / category landing-page support.

## Single highest-leverage fix
Define `Blog = Posts + sidebar archive controls`. If the sidebar isn't there, the modules are equivalent — merge.

## Quick wins
1. Auto-generate `/feed.xml` whenever this module is on a page.
2. Reading-time, author, category badges per item.
3. Optional Featured Posts row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## How this module behaves on a 390px phone

- Before/After uses a draggable splitter handle.
- Image pair renders at intrinsic ratio.

## Mobile-specific issues

- Splitter handle is hard to grab on touch — drag area is < 30px wide.
- No `touch-action: pan-x` on the handle — dragging triggers vertical page scroll on iOS.
- No keyboard interaction (Left/Right arrow) — accessibility regression.
- On narrow phones the labels (Before/After) overlap the splitter when the user drags to either edge.

## Mobile quick wins

- Increase handle hit area with `padding: 12px` and a transparent extended box.
- Set `touch-action: pan-x` on the splitter and `touch-action: none` while dragging.
- Add Left/Right ArrowKey handlers stepping the splitter by 5%.

## Single highest-leverage fix (mobile)

Increase handle hit area with `padding: 12px` and a transparent extended box.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — BeforeAfter

**Identity:** `BeforeAfter` (image comparison slider — CamelCase brand naming).
**Category:** Media.

## Inventory
- Two images stacked behind a draggable slider; the user reveals one over the other.

## Working ✅
- Genuinely fun module — strong "memorable feeling" hit per the framework.

## Internal conflicts ⚠
- **CamelCase name `BeforeAfter`** is a code identifier, not a user-facing label. Rename to `Before / After`.
- Both images need identical aspect ratio for the slider to look right; the editor doesn't enforce or warn.
- No accessible alternative — screen reader users get nothing.
- No vertical-slider option — only horizontal.

## Single highest-leverage fix
Rename to `Before / After` (with the slash and spaces) and prompt for both images at insert.

## Quick wins
1. Aspect-ratio warning when images don't match.
2. Vertical-slider toggle.
3. `aria-label="Before/after image comparison slider"` on the container; provide both alt-texts as a fallback.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 4 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 4 |
**Total: 18 / 30**
## How this module behaves on a 390px phone

- Renders empty/background containers; relies on outer column module for width.

## Mobile-specific issues

- Background module's image fills container but is fixed at desktop res — wastes mobile data.
- Empty module has zero height and no editor outline — invisible target on touch.
- No overlay-color picker on mobile editor (off-canvas right rail bug).

## Mobile quick wins

- Provide responsive background `image-set()` declarations.
- Render an editor-only dashed border on Empty modules in live edit mode.

## Single highest-leverage fix (mobile)

Provide responsive background `image-set()` declarations.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Background

**Identity:** `Background` (background image/video container).
**Category:** Visual.

## Inventory
- A full-width section with a background image or video and optional overlay + content slot.

## Working ✅
- Hero-section staple; cleanly separates the "background art" from the "foreground content" concerns.

## Internal conflicts ⚠
- **Overlap with `Picture` + section padding** — same effect can be achieved with the Element Style Editor's Background section. Two paths to the same result.
- **Video background**: heavy on mobile; no `prefers-reduced-data` consideration.
- **Overlay opacity** control not surfaced inline.
- **Text readability** check missing — no AA contrast warning when the overlay is too thin.

## Single highest-leverage fix
On mobile + `prefers-reduced-data`, swap video backgrounds for a static poster image automatically.

## Quick wins
1. Inline overlay-opacity slider.
2. Auto-suggest darker overlay when text contrast fails AA.
3. Lazy-load the video below the fold.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 4 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 18 / 30**
## How this module behaves on a 390px phone

- Native `<audio>`/`<video>` controls render correctly on iOS Safari and Chrome Android.
- Default poster is a black frame — no thumbnail extraction on mobile cellular.

## Mobile-specific issues

- Video starts at 100vh on Big2 templates due to inherited container — exceeds typical mobile fold and forces double-scroll.
- Autoplay relies on `muted+playsinline`; the inserted attributes are not always set, so iOS blocks autoplay.
- No mobile-data warning before downloading a 30MB+ file — affects audio podcasts and large videos.
- Captions/subtitles UI is not surfaced; `<track>` element accepted but author has no UI to attach VTT files.

## Mobile quick wins

- Force `playsinline` and `muted` attributes when autoplay is selected.
- Add a poster-frame extraction step (server-side ffmpeg thumbnail).
- Show a 'Tap to load' placeholder when `navigator.connection.saveData` is true.

## Single highest-leverage fix (mobile)

Force `playsinline` and `muted` attributes when autoplay is selected.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Audio

**Identity:** `Audio` (HTML5 `<audio>` player).
**Category:** Media.

## Inventory
- Inserts an audio player with file upload or URL source.

## Working ✅
- Native HTML5 audio is light and accessible by default.

## Internal conflicts ⚠
- **No transcript field** — accessibility miss.
- **No podcast-friendly metadata** (episode, season, host) for podcast use cases.
- **No autoplay-blocker warning** when user enables autoplay.
- No waveform preview — modern audio players ship with one.

## Single highest-leverage fix
Add a transcript text-area paired with the audio file; surface in `<details>` below the player.

## Quick wins
1. Display duration/file-size next to the inserted player.
2. Visualise waveform on hover.
3. Warn on autoplay unless muted.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 15 / 30**
## How this module behaves on a 390px phone

- Buttons render with template-aware default styles.
- Add-to-cart inherits product-page button rules.

## Mobile-specific issues

- Default button height is 36-40px on the seeded site — below 44px iOS HIG min for touch.
- Inline-edit on the button label is fiddly on mobile — long-press selects the parent, not the text.
- No loading/disabled state on add-to-cart — double-taps fire two add events on slow 3G.
- Link-picker modal (used by Button) renders fine on mobile but is not full-height — there's wasted dead-zone above the keyboard.

## Mobile quick wins

- Set `min-height: 44px; padding-block: .65rem` on all CTAs.
- Switch button to `aria-busy='true'` and disable for 1s after add-to-cart click.
- Bottom-sheet variant of Link Picker on mobile.

## Single highest-leverage fix (mobile)

Set `min-height: 44px; padding-block: .65rem` on all CTAs.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Add to cart

**Identity:** `Add to cart` (ecommerce CTA button bound to a product).
**Category:** Ecommerce.

## Inventory
- Inserts a CTA. Likely binds to nearest product context or shows a product picker on first insert.

## Working ✅
- Discrete from generic Button — semantically meaningful for ecommerce.
- Inherits Element Style Editor styling cleanly.

## Internal conflicts ⚠
- **No quantity stepper** — every "add to cart" assumes 1.
- **No variant picker** (size/colour) before adding.
- **No success state** preview ("Added ✓" → mini cart) inside the editor.
- Outside a product context, the module's behaviour is unclear (does it open a product picker?).

## Single highest-leverage fix
Pair Add-to-cart with an inline quantity stepper and a (Variants → Size · Colour) chip row.

## Quick wins
1. Show inline preview of cart-state animation (toast / drawer).
2. Disabled state when bound product is out of stock — preview that in the editor.
3. Default label "Add to cart" should be editable inline.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## How this module behaves on a 390px phone

- Accordion/Tabs/FAQ render with click-to-expand panels; smooth height animation.

## Mobile-specific issues

- Tab labels overflow horizontally on mobile — no `overflow-x: auto` with snap, they wrap into multiple rows that distort the strip.
- Tap target heights are 36-40px — below 44px iOS HIG minimum.
- Accordion icon (chevron) does not flip with `aria-expanded` change — visual state confused.
- FAQ does not use `<details>/<summary>` — loses native browser semantics, search, and accessibility.

## Mobile quick wins

- Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.
- Set tab strip to `overflow-x: auto; scroll-snap-type: x mandatory` with snap points.
- Bump tap target min-height to 48px.

## Single highest-leverage fix (mobile)

Convert FAQ to native `<details>/<summary>` for free a11y and progressive enhancement.

## System-Level Mobile Context (applies to every module)

These findings are inherited from `MOBILE_AUDIT/05_LIVE_EDIT.md` and `MOBILE_AUDIT/00_OVERVIEW.md` and are reproduced here for completeness:

- 🐛 **Element Style Editor is unreachable on mobile.** The right rail renders at `x=410px` on a 390px viewport (`getBoundingClientRect = [410, 185, 310, 32]`, `overflowX: hidden` on body). No module's deep style controls (typography, spacing, animations, custom classes, AI editor) can be reached on a phone. **Fix once for the whole product: convert the right rail to a bottom-sheet drawer on viewports < 768px.**
- 🐛 **Live Edit toolbar overlaps canvas content.** The fixed `SAVE` button sits over the canvas header on mobile; the iframe needs `padding-top` to clear the toolbar.
- 🐛 **Live Edit toolbar wraps to two rows** at 390px because `device-toggle / ⋮ / VIEW / SAVE` do not all fit. Layout instability when menus open.
- ⚠ **No mobile-preview default.** Live Edit always opens at the desktop preview width even on a real phone; the device-toggle has the wrong default.
- ⚠ **Inline edits on a heading or input** trigger the iOS keyboard with no `scrollIntoView` — the cursor disappears under the keyboard.
- ⚠ **Module insert picker (`+ Add module`)** is full-screen on mobile (good) but there is no search/filter for modules — the full grid is hard to scan on 390px.
- ⚠ **Selecting an element on mobile gives no inline action toolbar** — desktop-style hover icons are invisible.
- ⚠ **Module `data-type` attribute is the only way to identify a module on the canvas** — the editor wraps the module DOM consistently.
- ⚠ **Public site horizontal scroll mismatch:** confirmed `viewportWidth = 390` but `documentElement.scrollWidth = 375` on the seeded home page — there is a ~15px right gutter discrepancy. Inspect template body padding.

The single highest-leverage fix for the Live Edit experience as a whole is to **make the Element Style Editor a bottom-sheet drawer on mobile** — every module's tunability depends on it.

---

## Source — existing module audit (desktop-focused) for reference

# Module — Accordion

**Identity:** `Accordion` (collapsible question/answer block).
**Category:** Content.

## Inventory
- Series of collapsible items with title + body.

## Working ✅
- Native semantics through `<details>`/`<summary>` are simple and accessible by default.

## Internal conflicts ⚠
- **Likely overlaps with `Faq` module** — two paths to the same outcome confuses users.
- No "expand all / collapse all" toggle.
- No deep-link support (`#item-3` opens that item).
- Animation defaults are template-controlled.

## Single highest-leverage fix
Merge `Accordion` and `Faq` — Faq is just an Accordion with stronger structured-data semantics.

## Quick wins
1. Emit FAQPage JSON-LD when the Accordion is on a content page.
2. Deep-link via URL fragment.
3. Keyboard navigation (Up/Down/Home/End) per WAI-ARIA Disclosure pattern.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Inserts a flex/grid row that can host other modules side-by-side.
- Default column count: usually 2 or 3 depending on template.

## Working ✅
- Necessary primitive for any two-column layout. Keep.
- Drag-and-drop modules into each column.

## Internal conflicts ⚠
- **No mobile-stacking behaviour preview.** Editor shows the desktop layout; mobile is a guess until VIEW.
- **No gap / gutter control** on the canvas — must descend into Element Style Editor.
- Adding a 4th column on a 3-column row collapses awkwardly without a clear visual rule.

## Single highest-leverage fix
Show a "Mobile" toggle on the module's right rail that simulates how columns stack on small viewports.

## Quick wins
1. Surface column-count chips (`1 · 2 · 3 · 4 · 6 · 12`) above the row.
2. Visible gap handle drag.
3. "Equalise heights" one-click toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## Inventory
- Animates a list of strings as if being typed one character at a time.

## Working ✅
- Common landing-page hero ornament; quickly grabs attention.

## Internal conflicts ⚠
- **CamelCase brand name** `TextType` leaks the upstream library (`typed.js`). Rename to `Typewriter`.
- **`prefers-reduced-motion`** not confirmed — same issue as Marquee.
- **No SEO fallback** — the static text the bot sees may differ from the animated text the user sees.
- **No accessible name on the typed line** — screen readers should hear the resolved text once, not character-by-character.

## Single highest-leverage fix
Rename to `Typewriter` and ship with `aria-live="off"` + a static fallback first string for SEO.

## Quick wins
1. Honor `prefers-reduced-motion: reduce` — show first string statically.
2. Loop / no-loop toggle.
3. Cursor character picker.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 4 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 4 |
**Total: 15 / 30**
## Inventory
- Quote + author name + author role + (optional) photo + (optional) star rating.

## Working ✅
- Common landing-page primitive that templates reach for.
- Combining quote + photo + role is the right shape.

## Internal conflicts ⚠
- **No structured-data emit** (`Review`/`Testimonial` schema.org). Misses SEO win.
- **Carousel default** can become an auto-rotating carousel — flagged as a Drunk-Designer anti-pattern.
- No source / link to original review (LinkedIn, Trustpilot…).
- No verification badge — every testimonial reads as the brand's claim, not the customer's.

## Single highest-leverage fix
Default to a static grid; require an explicit toggle for carousel mode (and always show pause/play controls when enabled).

## Quick wins
1. Emit schema.org `Review` JSON-LD.
2. Optional source-link field per testimonial.
3. Star-rating optional field.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## Inventory
- Photo + name + role + bio + social links. Multiple cards in a grid.

## Working ✅
- Common "About us — meet the team" pattern; saves rolling-your-own.

## Internal conflicts ⚠
- **No role-vs-title distinction** — "Senior Developer" vs "Software Engineer" matter for SEO and clarity.
- **No structured `Person` JSON-LD** schema emit.
- **Default photo placeholders** are generic — should use coloured initials when no photo, not a "missing image" icon.
- No filter (e.g. "Engineering team" vs "Marketing team") for sites with 30+ members.

## Single highest-leverage fix
Emit `Person` JSON-LD per card and group by team filter.

## Quick wins
1. Coloured-initials placeholder when photo missing.
2. Optional pronouns field.
3. Hover-reveal extended bio.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Standard contact form fields. Submission target: site admin email and/or CRM.

## Working ✅
- Universal need; saves users from configuring a form builder.

## Internal conflicts ⚠
- **Spam protection unclear** — same critique as Newsletter.
- **Double-submit prevention** absent on click.
- **No success / error inline state** preview in the editor.
- **No file-attachment toggle** for "send me your CV" use cases.

## Single highest-leverage fix
Default-on honeypot + rate-limiting; surface "spam protection: ✓" badge in the canvas.

## Quick wins
1. Configurable required-field set (Name optional, Phone optional).
2. Confirmation auto-reply email toggle.
3. Inline validation as the user types.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Single image. Click → opens "Select image" modal (My computer / Enter prompt / URL / Uploaded / Media library).

## Working ✅
- Five image sources, including AI generation and Unsplash. Strong feature set.
- Drag-and-drop directly onto the placeholder.

## Internal conflicts ⚠
- **No alt-text prompt** on insert. The biggest accessibility miss in the entire CMS.
- No focal-point editor for responsive crops.
- No automatic image optimisation (WebP, AVIF, srcset) signal.
- The picker modal lacks `role="dialog"` and `aria-labelledby` — see image-upload report.

## Single highest-leverage fix
Force an alt-text prompt (with "skip — decorative" toggle) immediately after image insertion.

## Quick wins
1. Always emit `loading="lazy"` and `decoding="async"`.
2. Show file size + dimensions next to the inserted image.
3. Suggest WebP conversion on upload.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 4 |
**Total: 19 / 30**
## Inventory
- Two images: default + hover-state. On mouse-over, the second image is shown.

## Working ✅
- Niche but useful for product variants, before/after-style teasers.

## Internal conflicts ⚠
- **Hover-only affordance** — Drunk-Designer anti-pattern. Touch devices have no hover.
- **No tap-to-flip** for mobile.
- **No accessibility name** on the rolled-over image.
- Overlaps with `BeforeAfter` for some use cases.

## Single highest-leverage fix
On touch devices, swap on tap (or at least show the second image briefly) so the feature works for everyone.

## Quick wins
1. `aria-label` describing the rollover.
2. Optional caption that swaps with the image.
3. Provide a Crossfade transition style alongside the default instant swap.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## Inventory

- Top toolbar: `+` (Add), hamburger, search input (icon-collapsed), Live Edit eye-icon (green), bell, avatar.
- Heading: `Welcome back, Wallace` + `Here's what's happening`.
- Statistics chart panel (full-width).
- KPI cards stacked vertically: `Emails 0`, `Last comments 127`, `Sales $1,695.58`, `Recent Orders 4`.

## Working ✅

- **Best-in-class responsive transition** in the admin: cards stack to one column, search collapses to a circle, sidebar starts collapsed.
- **No horizontal overflow.**
- Touch targets on toolbar icons ≥44px.
- Greeting stays personal and friendly ("Welcome back, Wallace").
- The chart adapts its width and remains readable at 390px.

## Internal conflicts ⚠

1. **Search becomes a circle** — useful real-estate save, but no way to know what to type without expanding it. Tap behaviour confirmed as expanding the input would be ideal.
2. **`+ Add` toolbar button is a tiny `+` icon** on mobile, shrunken from desktop's `+ Add`. The label is gone — first-time mobile admins may not discover this affordance.
3. **No bottom-tab navigation.** Mobile admins benefit from a fixed bottom tab (Dashboard / Posts / Orders / Settings) — this admin keeps the desktop sidebar metaphor as a hamburger.
4. **`View more →`** link inside the chart card is the only navigational target after stats — no other in-card links to drill into.
5. **No "swipe to refresh"** behaviour confirmed.

## Single highest-leverage fix
Add a fixed bottom-tab navigation on mobile (`Dashboard / Lists / Add / Settings / Account`) — replaces the hamburger as the primary nav.

## Quick wins
1. Restore the word `Add` next to the `+` icon at viewports ≥360px.
2. Bottom-tab is reachable with a thumb on a phone; sidebar drawer requires reach.
3. Tap on the search icon should auto-focus the input after expanding.

## Mobile usability score: **4 / 5** — strongest mobile surface in the product.
## Inventory
- Search input that queries the site and shows results.

## Working ✅
- Universal need; saves users from configuring Algolia/Lunr.

## Internal conflicts ⚠
- **Search backend not surfaced** — users don't know if it's MySQL LIKE, full-text, or something better.
- **No instant-search dropdown** by default — modern expectation.
- **No empty-state** on results page captured.
- Default placeholder is generic "Search…" — should reflect the site type ("Search posts", "Search products").

## Single highest-leverage fix
Add an instant-results dropdown so the user sees matches as they type.

## Quick wins
1. Site-type-aware placeholder (`Search posts and products…`).
2. Recent-searches row on focus.
3. ARIA: `role="search"` wrapper, `aria-label` on the input.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Continuously scrolling row of text or images. Direction, speed, pause-on-hover.

## Working ✅
- Stylish "as seen in" / partner-logo strip when used sparingly.

## Internal conflicts ⚠
- **`prefers-reduced-motion` not respected** — confirm the module pauses for users who request reduced motion.
- **Auto-rotating motion** is a Drunk-Designer anti-pattern in carousel form; marquee is its cousin.
- No accessibility name for the strip.
- Speed slider is template-specific; defaults vary.

## Single highest-leverage fix
Honor `prefers-reduced-motion: reduce` — pause the marquee when the OS preference is set.

## Quick wins
1. Pause-on-hover by default.
2. `aria-label="Featured partners"` style accessible name.
3. Provide a static fallback layout for print / RSS.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## Personas applied here (six lenses)

| # | Persona | URL slug | Why this lens for Microweber |
|---|---|---|---|
| 1 | Security Auditor | `/agents/security-auditor/` | OWASP Top 10 + STRIDE applied to a CMS that hosts user content + accepts payments |
| 2 | Accessibility Engineer | `/agents/accessibility-engineer/` | WCAG 2.1 AA — multiple confirmed gaps (no `role="dialog"`, no `aria-required`, generic action labels) |
| 3 | Novice Customer | `/agents/novice-customer/` | First-time admin friction: "where do I start?" gaps in dashboard + module library |
| 4 | Grug-Brained Developer | `/agents/grug-brained-developer/` | War on complexity — iframe-in-iframe-in-modal architecture is a textbook target |
| 5 | Performance Engineer | `/agents/performance-engineer/` | The Products module renders **12,142 px tall** by default; that's a measurable bottleneck |
| 6 | UX Engineer | `/agents/ux-engineer/` | Premium UI/UX lens — design tokens, hierarchy, theming, density |

Three more personas (`autodev`, `architect`, `qa-engineer`) effectively describe how this audit itself is being conducted; they are not applied as separate audits.

## Files in this folder

| # | File | Persona |
|---|---|---|
| 00 | `00_INDEX.md` | This file |
| 01 | `01_SECURITY_AUDITOR.md` | Security |
| 02 | `02_ACCESSIBILITY_ENGINEER.md` | A11y |
| 03 | `03_NOVICE_CUSTOMER.md` | First-time user |
| 04 | `04_GRUG_BRAINED.md` | Anti-complexity |
| 05 | `05_PERFORMANCE_ENGINEER.md` | Perf |
| 06 | `06_UX_ENGINEER.md` | Premium UX |

## How to read this folder

Each report uses **only that persona's voice and lens**. Where the same finding appears in multiple personas, each frames it through its own checklist (e.g. the missing `role="dialog"` is a "Robust" failure for the a11y engineer, a fix-in-an-afternoon for grug, and a hidden complexity tax for UX).

Findings here re-use the live DOM data captured in earlier sessions and recorded in:
- `DESIGN_REPORT_*.md` family (project root)
- `DESIGN_REPORTS_LIVE_EDIT_MODULES/` (52 module reports)
- `ADMIN_EVALUATION/` (17 admin evaluation files)
- `MOBILE_AUDIT/` (7 mobile files)

No new audit data was captured for this multi-persona pass. The contribution of this folder is **interpretation**: same facts, six new lenses.

## Top consensus findings (where 3+ personas agree)

1. **Iframe-in-iframe-in-modal architecture for Products** — flagged by Grug (complexity), UX (responsiveness), Performance (DOM weight), Novice (lost orientation).
2. **No `role="dialog"` / `aria-labelledby` on multiple modals** — a11y, security (UX gap can mask phishing-style overlays), UX.
3. **Seeded test data leaks into every picker** — novice (confusing), security (data exposure on shared installs), UX (broken first impression).
4. **Products module dumps the whole DB in one block** (12,142 px) — performance, novice, UX.
5. **Module-name typos and inconsistent casing** — UX, novice, grug.

## What's *not* covered here

- `architect`, `database-engineer`, `devops-engineer`, `tech-writer`, `data-engineer`, `ml-engineer`, `platform-engineer`, `incident-responder` — would require source-code, infra, and runtime access beyond what the audit had. Cited only when a specific finding could be expanded.
- `mobile-engineer` is largely covered by `MOBILE_AUDIT/`.
- `customer-persona` is similar to `novice-customer` and would duplicate findings.
- `stoic-path`, `qa-engineer`, `debugger`, `frontend-engineer` — partially overlap with completed work.
## Inventory
- Display average rating + number of votes; optionally accept new ratings.

## Working ✅
- Useful for products and posts; common pattern.

## Internal conflicts ⚠
- **Read vs read+write modes** are likely conflated. The module should clearly separate "show average" from "let users rate".
- **No structured-data emit** (`AggregateRating`) confirmed — SEO miss.
- **Spam protection on write mode** absent.
- **Dishonest defaults**: showing "5.0 ★ — 1 vote" looks like a fake review when ratings are sparse.

## Single highest-leverage fix
Always emit `AggregateRating` JSON-LD when there are 3+ ratings; hide the widget when fewer.

## Quick wins
1. Mode toggle: read-only vs interactive.
2. Show vote count alongside the average.
3. Half-star granularity opt-in.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Inventory
- Inserts the site logo (image + optional text).

## Working ✅
- Pulls from the site-wide logo configured in Template Customization → Branding. Single source of truth.

## Internal conflicts ⚠
- **Two ways to set a logo** — Template Customization Branding section and per-module override. Unclear which wins.
- **No dark/light variant** — sites with dark headers and light footers need both.
- **No SVG-vs-PNG hint** — SVG logos are crisper and lighter; users get no nudge.

## Single highest-leverage fix
Surface "Logo (light)" and "Logo (dark)" both, auto-pick by surrounding background.

## Quick wins
1. Show the resolved size in the canvas tooltip.
2. Lazy-load only off-screen instances (the header logo should never lazy-load).
3. Encourage SVG with a short tooltip.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Inserts an empty `<div>` placeholder. Useful for spacing or as a drop-target.

## Working ✅
- A pure spacer/container has its place — but **see Internal conflicts**.

## Internal conflicts ⚠
- **`Empty` is a developer concept exposed to users.** Most users will never knowingly need an empty container — they need a Spacer or a Multiple Columns row.
- The name itself is a poor marketing pitch ("Insert: Empty"). Users will skip past it; it pollutes the picker.
- **Redundant with `Spacer`** — both are silent layout helpers.

## Single highest-leverage fix
Remove `Empty` from the user-facing picker. Keep it as an internal admin-only tool if developers need it.

## Quick wins
1. If kept, rename to `Container` and add a hover description: "An empty row to drop modules into".
2. Auto-collapse when empty in published view.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 9 / 30 — strongest candidate to delete from the library.**
## Inventory
- Multi-paragraph rich-text body. Same inline toolbar as Title.

## Working ✅
- Direct manipulation. Default placeholder "My text content." is honest.
- Reuses the same toolbar — consistency.

## Internal conflicts ⚠
- **No measure (line-length) limit visible.** The framework asks for 55–75 character lines; the module expands to whatever container width allows. Surface a "max-width: 65ch" toggle.
- **No reading-time estimate** for marketing pages.
- **No drop-cap / pull-quote / lede shortcuts** — a CMS missing common editorial primitives.

## Single highest-leverage fix
Default the `Text` block to `max-width: 65ch` with a one-click "Stretch full-width" toggle.

## Quick wins
1. Add a paragraph-style picker (Body / Lede / Caption / Blockquote).
2. Word-count badge.
3. Keyboard shortcut to wrap selection in `<blockquote>`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 4 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## Inventory
- Lists tags used across posts/products. Optional sizing by frequency (tag cloud).

## Working ✅
- Useful navigation primitive for blogs.

## Internal conflicts ⚠
- **Tag cloud as an aesthetic** is dated; modern blogs use chips.
- **Frequency-sized text** can break readability for screen readers (giant text vs tiny text inconsistency).
- **No filter scope** — posts vs products vs both.

## Single highest-leverage fix
Default to chips (uniform size); offer a "tag cloud" preset toggle for nostalgia.

## Quick wins
1. Scope chip on insert.
2. Show tag count next to each (`design (12)`).
3. Active-tag highlighting on tag landing pages.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Comment list + comment form. May integrate with Disqus or be self-hosted.

## Working ✅
- Real comments engagement loop — earning the slot.

## Internal conflicts ⚠
- **Spam protection** unclear from the canvas.
- **Moderation surface** not surfaced — admins should be able to moderate inline.
- **No Disqus / Hyvor / native toggle** from the canvas; users may not know what they're getting.
- **No threaded replies** confirmed.

## Single highest-leverage fix
Surface the comment-engine choice (Native / Disqus / Hyvor / Off) as a chip on the canvas selection.

## Quick wins
1. Honeypot + rate-limiting by default.
2. Inline moderation actions for admins.
3. Mention/notify support.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## Inventory
- Dropdown / list of available site languages, switching the user's locale.

## Working ✅
- Critical for any non-English-only site; surfacing it as a module is correct.

## Internal conflicts ⚠
- **Naming**: `Multilanguage` is a CMS-internal compound. Users say "Language" or "Language switcher".
- **Locale codes vs flags**: flags identify countries, not languages. `🇺🇸 English` excludes other English-speaking countries.
- No `hreflang` tag emit confirmed.
- Selected language not visually distinct in default styling.

## Single highest-leverage fix
Rename to `Language switcher`. Default to language names without flags; flags as an optional toggle.

## Quick wins
1. Emit `hreflang` alternates in `<head>`.
2. Persist user's choice in cookie + URL.
3. ARIA: `aria-label="Choose language"`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Inventory
- Inserts a list of posts. Filter by category, tag, author, count.

## Working ✅
- A real blog primitive — not just "Pages with a category".

## Internal conflicts ⚠
- **Overlaps with `Blog` module** — see Blog.
- Default rendering is one-column list with date and "Read more". Visually thin compared to modern blog layouts.
- Filter UI is buried inside the module's Settings, not on the canvas.

## Single highest-leverage fix
Surface a filter chip row on the canvas: `Latest · Featured · Category · Tag`.

## Quick wins
1. Cards / Grid / Compact list preset toggle.
2. Author avatar opt-in.
3. Estimated reading time per item.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Inserts a shop landing — likely combining Products + Category + Cart in one block.

## Working ✅
- One-click "make a shop page" is a real time saver.

## Internal conflicts ⚠
- **`Shop` vs `Products` vs `Add to cart` vs `shop/cart`** is a four-way overlap. The picker exposes all of them with no hierarchy.
- Inherits the iframe-in-iframe-in-modal architecture flagged in the Products report.
- No checkout-flow preview from the canvas.

## Single highest-leverage fix
Reorganise ecommerce modules under a single `Shop` group with `Catalog · Single product · Cart · Checkout · Add to cart` sub-types.

## Quick wins
1. Picker grouping (per the index report).
2. Show the bound shop / category as a badge on the canvas.
3. Inline preview of one product card with real data.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 13 / 30**
## Discovery (what's there)

- **Tech**: Laravel + Filament + Livewire + Alpine + a custom Live Edit canvas.
- **Brand**: Microweber wordmark + electric-blue mark; no apparent design tokens file surfaced in the UI.
- **Scope**: Admin shell, Live Edit canvas, public templates, marketplace, ~90 modules.
- **Viewports tested**: 390 px, 1280 px, 1920 px.

---

## 15-Dimension Pass

### 1. Hierarchy — 2 / 5
- **Posts/Products toolbars** invert hierarchy: `Categories` button is dark-filled (visually dominant) while `+ New post` is the actual primary CTA.
- **Element Style Editor** flattens 10 sections into one scroll — no top-level grouping.
- **Settings hub** has 5 sections × 8 cards each — every card looks identical regardless of frequency-of-use.
- **Orders page** is the *one* surface that gets hierarchy right: stat cards → status tabs → list.

### 2. Spacing — 3 / 5
- Vertical rhythm in the dashboard is consistent.
- Live Edit canvas overlaps the toolbar on mobile — spacing not honoured under reflow.
- Element Style Editor accordions cram density inconsistently between sections.
- "Add new content" picker uses generous padding; "Add Layout" picker uses tight padding. No shared system.

### 3. Typography — 3 / 5
- Default heading font (Jost) is a strong choice.
- Body editor toolbar wraps to 3 rows on narrow viewports — too many controls, not enough hierarchy.
- Mixing `PUBLISHED` UPPERCASE pills with sentence-case copy elsewhere is jarring.
- No documented type scale visible (h1 / h2 / h3 / body / caption).

### 4. Colour — 3 / 5
- Primary blue is calm; CTAs use orange and blue interchangeably.
- Status pills appear in **green / blue / orange / red / pink** without a documented palette → "committee-driven colour" anti-pattern.
- Default Bootstrap orange CTA (`Contact us` button) clashes with the Big2 blue capsule (`Go Live Edit ↗`).

### 5. Alignment — 3 / 5
- Tabular surfaces (Posts, Products, Orders) align cleanly.
- Live Edit canvas elements drift visually from the chrome's grid.
- Form sections in Create Post align well; the "Where to put it" tree breaks the rhythm.

### 6. Components — 3 / 5
- Filament base components are solid.
- The Live-edit chooser cards are `<div role="button">` — not the same `<button>` primitive used elsewhere.
- Status pills have multiple visual variants (uppercase green Posts vs sentence-case Orders).
- Two `Save` button styles (form `Save` vs editor `SAVE`).

### 7. Iconography — 2 / 5
- Mix of filled, outlined, brand-coloured icons across modules.
- Icon-only buttons in Live Edit right rail with inconsistent labels.
- Module picker icons range from abstract decorations to literal pictograms — no cohesion.
- Folder/Shop icons in the right rail of a selected Products module: *no labels surfaced by default*.

### 8. Motion — 2 / 5
- Live Edit canvas has motion (animations, marquee) without `prefers-reduced-motion`.
- Slider module ships with auto-rotate as the default — anti-pattern.
- No micro-interactions on save/success — buttons just stop responding.

### 9. States — 1 / 5
- **Default / hover / focus / active / disabled / loading / error** — full state coverage not visible.
- The image-picker `OK` button stays in disabled state until selection but with no contrast change observed.
- Form fields lack visible inline-validation states.

### 10. Accessibility — 2 / 5
- Multiple modals miss `role="dialog"` + `aria-labelledby`.
- Required fields not always marked.
- Numeric inputs render literal `NaN` (Element Style Editor).
- Off-canvas Element Style Editor on mobile.

### 11. Responsiveness — 2 / 5
- Admin Dashboard responds beautifully.
- Live Edit Element Style Editor is rendered off-screen on mobile (`x = 410` on 390-wide viewport).
- Toolbar overlaps canvas content on mobile.
- Big2 template's home page renders as a 20-screen empty grid on mobile.

### 12. Density — 3 / 5
- List rows are tall (~250px on mobile, eats the viewport).
- Settings cards are spacious; could ship a "compact" mode for power users.
- Orders has a balanced density.

### 13. Theming — 2 / 5
- Light theme only confirmed.
- Template Customization exposes Primary / Secondary / Background / Text colours but no semantic tokens (success/danger/warning/info).
- No dark-mode preset for the admin.

### 14. Localisation — 2 / 5
- Multilanguage module exists.
- But: copy is partly inconsistent ("Refference image" typo, "Write your post here" wrong context, `Faqs` mixed-case).
- No RTL-readiness confirmed.

### 15. Personality — 3 / 5
- "Welcome back, Wallace" is a moment of warmth.
- The AI image generation tab is genuinely a differentiator.
- The Marketplace cards have personality.
- But the wordmark, the icon system, and the colour palette do not feel like one product.

---

## "Can this element be removed?" pass

The Jobs filter applied to specific surfaces:

| Element | Verdict |
|---|---|
| `+ Add` chooser modal (interrupts every "+ Add") | **Replace with split-button** — saves the modal entirely for the most-used path. |
| `Empty` module | **Remove** from user-facing picker. |
| `Layout Content` module | **Remove** from user-facing picker. |
| Funnel filter `0` badge when no filter | **Remove** when count is 0. |
| Two `New product` buttons on Products list | **Remove** the duplicate. |
| `Preview Frame Container` label on Template Customization | **Remove** — leftover dev label. |
| `Kitchen Sink` Settings link | **Remove** from non-dev installs. |
| `Other` Settings link | **Rename or remove** — dead-end label. |
| Numeric weight axis alongside named-weight axis on Typography | **Remove** one — pick a single weight vocabulary. |

That's nine elements identifiable as "would the user notice their absence?" → "no."

---

## Phase Plan

### Phase 1 — Critical (usability blockers)

1. Fix `NaN` in Typography input.
2. Add `role="dialog"` / `aria-labelledby` to all modals.
3. Convert the Element Style Editor right rail to a bottom-sheet on mobile.
4. Fix Big2 footer literal + broken home page.
5. Hide `Go Live Edit` from anonymous visitors.
6. Add `Save` button to Template Customization (or rewrite the banner).
7. Fix Products list `$0.00`.

### Phase 2 — Refinement (cohesion)

1. Stand up a documented design-token file: colour, type scale, spacing, radius, shadow, motion, breakpoints.
2. Standardise the list-page pattern on Orders' shape.
3. Sweep all module names; pick one casing convention.
4. Audit + de-dupe the picker (52 → ~30).
5. Define a 5-colour status palette and apply across all status pills.
6. Lift the Products iframe-in-iframe-in-modal to a single document.

### Phase 3 — Polish (micro-interactions)

1. Save-button success animation.
2. Hover/focus/active state coverage.
3. `prefers-reduced-motion` honoured site-wide.
4. Dark-mode preset.
5. Empty-state illustrations for first-time users.

---

## UX_FEEDBACK.md-style notes for build agents

Each line: file → component → property → old → new.

```
ADMIN posts list                   primary toolbar     `Categories` button   filled-dark   ghost-chip
ADMIN posts list                   filter funnel        badge "0"             "0"           hidden
ADMIN posts list                   row action button   aria-label            "Actions"     "Actions for {title}"
ADMIN products list                header              `New product` btn (2nd) present       removed
ADMIN settings page                header              search input          missing       added
LIVE EDIT element style editor     viewport-rule       right-rail            position:right (always) bottom-sheet @ <768px
LIVE EDIT toolbar                  iframe canvas       padding-top           none          equal to toolbar height
LIVE EDIT chooser cards            html element        <div role="button">   div            <button>
LIVE EDIT body editor              field label         "Write your post here" (in product modal) wrong-context "Product description"
PUBLIC site                        Big2 footer         template literal      "© Big2 Header. /" rendered correctly
TEMPLATE CUSTOMIZATION             primary action      Save button           absent        added
IMAGE PICKER                       AI tab              field label           "Refference image" "Reference image"
LINK PICKER                        modal               role="dialog"         missing       added
```

---

## UX Verdict

> *"Microweber has the right ingredients. It does not yet have the discipline. Orders shows what good looks like. Get the design-token file written, lock the list-page pattern, and a sprint of nothing-but-removal will lift the entire admin from competent to crafted."*

**Premium-readiness score:** **38 / 75** (15 dimensions × 5). Solid mid-tier.
## Inventory
- Embeds a PDF inline (likely an `<iframe>` or PDF.js).

## Working ✅
- Better than forcing users to download.

## Internal conflicts ⚠
- **PDF accessibility** is hard — many embedded PDFs are inaccessible to screen-readers. The module should warn and offer an alternative download link with a text summary.
- **Heavy** on mobile — large PDFs on cellular = bad first impression.
- **No first-page poster image** for lighter loading.

## Single highest-leverage fix
Render a poster image of page 1 + "Open PDF" button; full embed only on click.

## Quick wins
1. File-size hint shown next to the embed.
2. Always provide a "Download" button alongside.
3. Surface alt-text / summary field for screen reader users.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Multi-paragraph rich content. May include shortcuts to insert nested modules inline.

## Working ✅
- The "main canvas" of any page-like surface — necessary primitive.

## Internal conflicts ⚠
- **`Content` vs `Text` vs `Layout Content` is a three-way confusion.** The picker exposes all three with no description. Users will click and find out by trial.
- Rich-text inline toolbar duplicates the post body editor's toolbar — same Link Picker issues apply.
- No content-template starts ("Article", "Press release", "Product description").

## Single highest-leverage fix
Disambiguate `Text` (single block) vs `Content` (long-form rich body) vs `Layout Content` (template-driven shell). Pick two; deprecate one.

## Quick wins
1. One-line description on hover for each.
2. Word-count + reading-time badge.
3. Outline-view sidebar showing all H1–H4 inside the Content block.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 12 / 30**
## Inventory

### Create Post (`/admin/posts/create`) at 390px
- Heading `Create Post` (centered).
- Primary action row: `Live edit` + `SAVE` side-by-side.
- Tabs: `Content · Custom Fields · SEO · Advanced` (horizontally scrollable on mobile).
- Title field full-width.
- Body editor with toolbar: B I U S sub sup link H2 H3 align list ordered table attach undo redo — wraps to 3 rows.
- Sections stack: Permalink · Media · Published · Where to put it · Tags · Menus.

### Settings hub (`/admin/settings`)
- Section heading `Website Settings`.
- Single column of cards (icon + title + description) — clean.

## Working ✅

- **Forms collapse cleanly** to a single column.
- **Title input is full-width**, comfortable to tap.
- **Body editor toolbar wraps** rather than horizontal-scrolls.
- **Settings hub** translates beautifully to mobile.
- **Tab strip** under the form heading scrolls horizontally — preserves IA without hiding tabs.
- **`Live edit` and `SAVE`** stay in primary positions.

## Internal conflicts ⚠

1. **Body editor toolbar wraps to 3 rows on mobile**, eating vertical space before the user has typed a single character. A "minimal mode" toolbar (only B, I, link, headings) would save half the height.
2. **Page tree under "Where to put it"** still shows hundreds of seeded gibberish entries (same desktop bug; the mobile screen makes it worse because they're scrolled past one at a time).
3. **Date picker UI** in Published section — mobile native `<input type="datetime-local">` would be better than a custom widget.
4. **No sticky header** for `Live edit` / `SAVE` when the form scrolls — the user has to scroll to top to save.
5. **Tab labels truncated** on narrow viewports? Verify whether `Custom Fields` collapses to `Custom F…`.
6. **Form fields don't have ARIA error-state styling** confirmed.
7. **No keyboard shortcuts surfaced** — though irrelevant on phone, useful on a tablet.

## Single highest-leverage fix
Make the `Live edit` + `SAVE` buttons sticky to the bottom of the viewport on mobile so the user can save from anywhere in the form.

## Quick wins
1. Minimal-toolbar mode for the body editor on mobile.
2. Sticky bottom-action bar with Save and Cancel.
3. Use native datetime input where browser supports it.
4. Audit tab-label truncation on viewports <360px.

## Mobile usability score: **4 / 5** — clean stack, only the editor toolbar and date-picker hold it back.
## Inventory
- Wraps a layout template's main content slot. Users probably encounter this when editing pages built from a template.

## Working ✅
- Necessary plumbing: marks where a template's main body lives so children layouts can be inserted.

## Internal conflicts ⚠
- **Name is jargon.** "Layout Content" reads as a developer term. Users want "Page body" or "Main content".
- **Overlaps with `Content` and `Text`** in the picker — three modules occupying the same mental slot.
- **Often inserted automatically** by template structure; surfacing it in the user-facing picker is confusing.

## Single highest-leverage fix
Hide `Layout Content` from the user-facing picker — keep it as an internal template slot only.

## Quick wins
1. If kept, rename to `Page body` and add a description.
2. Make the slot's boundary visible only on hover so it doesn't compete visually with the user's content.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 1 |
**Total: 9 / 30 — second strongest deletion candidate after `Empty`.**
## Inventory
- Likely accepts upload + URL (YouTube/Vimeo) as sources.

## Working ✅
- Cross-source (file or URL) is correct — beats forcing self-hosted only.

## Internal conflicts ⚠
- **No autoplay/muted/controls toggle row** visible in the canvas.
- **Privacy mode for YouTube** (`youtube-nocookie.com`) not surfaced — important for GDPR.
- **No poster image** prompt.
- **No captions/subtitles upload** (`.vtt`).

## Single highest-leverage fix
Force a captions/subtitles prompt on insert ("Skip — captioned in source" toggle).

## Quick wins
1. Default to `youtube-nocookie.com` when YouTube URL is detected.
2. Auto-extract poster frame for self-hosted video.
3. Loop / mute / autoplay chip row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Inserts a Google Maps iframe at a chosen address / lat-long / pin.

## Working ✅
- One-click map for contact pages — common need.

## Internal conflicts ⚠
- **GDPR / consent**: Google Maps loads tracking. No consent gate visible.
- **No alternative**: OpenStreetMap / Leaflet is privacy-respectful; not offered.
- **API-key dependency**: if no key configured, the map silently shows a degraded state — ship a clear CTA to set the key in Settings.
- **No multiple-pin support** for "our locations" pages.

## Single highest-leverage fix
Add an OpenStreetMap fallback option with no API key required.

## Quick wins
1. Show "Add Google Maps API key →" link inside the module's settings if missing.
2. Lazy-load the iframe until visible.
3. Add a "lite" static-image preview that hydrates on hover.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Pasted code with language detection / picker, syntax highlighting via highlight.js or Prism.

## Working ✅
- Specific tool for the job; better than dropping `<pre><code>` by hand.

## Internal conflicts ⚠
- **CamelCase brand name** `HighlightCode` reads as an internal identifier. Rename to `Code Block`.
- **No copy-to-clipboard button** by default.
- **Language list** likely huge — needs search/recently-used.
- **No dark/light theme toggle** that respects site theme.

## Single highest-leverage fix
Rename to `Code Block` and ship with a copy-to-clipboard button enabled by default.

## Quick wins
1. Auto-detect language from the pasted snippet, with explicit override.
2. Line numbers toggle.
3. Honor site theme (dark code on dark theme, light on light).

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 1 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 1 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 12 / 30**
## TL;DR — Single Highest-Leverage Fix

> **The Live Edit Element Style Editor panel renders at `x = 410px` on a 390px-wide viewport — it's literally off-screen.** The whole right rail (Typography, Background, Spacing, Container, Border, Rounded corners, Animations, Shadow, Classes, AI Style Editor) is invisible to a user editing on a phone. Confirmed via DOM: `getBoundingClientRect()` returns `[410, 185, 310, 32]` while `viewportWidth = 390`. A user on mobile can select an element but cannot style it. **The single biggest mobile bug in the product.**

The public site has its own crisis (broken layout on Big2 — see Big2 report) but the Live Edit mobile experience is the deeper architectural problem.

---

## Files in this folder

| # | File | What it covers |
|---|---|---|
| 00 | `00_OVERVIEW.md` | This file — top findings, scorecard |
| 01 | `01_PUBLIC_SITE.md` | Anonymous visitor view on mobile |
| 02 | `02_ADMIN_DASHBOARD.md` | Admin home & sidebar collapse behaviour |
| 03 | `03_ADMIN_LISTS.md` | Posts / Products list pages on mobile |
| 04 | `04_ADMIN_FORMS.md` | Create Post / Settings forms on mobile |
| 05 | `05_LIVE_EDIT.md` | Live Edit canvas + the Element Style Editor bug |
| 06 | `06_BACKLOG.md` | Consolidated mobile-only backlog |

---

## Top 10 Mobile Findings

1. **🐛 Element Style Editor panel renders off-screen** at `x=410` on a 390px viewport. The right rail is unreachable. **Critical.**
2. **🐛 Public home page** (Bootstrap template) shows correctly with stories — but on the **Big2 template** it renders as a vertical wall of empty grey image placeholders for many screens (confirmed in `mobile-public-home-full.png`). Fix tied to the Big2 template bug already flagged.
3. **🐛 `Go Live Edit` admin chip is visible to anonymous visitors on mobile** (top-left, blue pill). Same bug we caught on desktop, but more obvious on mobile because of where it sits.
4. **🐛 Live Edit toolbar overlaps canvas content**: the `SAVE` green pill overlaps the published header's "111" link area. Confirmed in `mobile-live-edit.png`.
5. **Live Edit toolbar is itself wrapped to two rows** when device-toggle, dots, and View+Save don't fit, causing layout instability.
6. **Admin Dashboard scales gracefully** ✅ — KPI cards stack vertically, search collapses to icon-only. Best mobile surface in the product.
7. **Admin list pages**: `Categories` button still visually dominates `+ New post`; same hierarchy inversion as desktop, more painful when only ~390px wide.
8. **Form fields on Create Post** look fine — Title field full-width, body editor toolbar wraps. Mostly OK.
9. **Settings hub** collapses to single-column cards cleanly ✅.
10. **Anonymous public site shows admin chrome.** The "Go Live Edit" button is one of three admin signals visible to a logged-in admin browsing the public site — they should disappear in `VIEW` mode.

---

## Library-Level Mobile Scorecard

| Surface | Mobile usability | Notes |
|---|---:|---|
| Public site (Bootstrap) | 3 / 5 | Functional; admin chrome leaks |
| Public site (Big2) | 1 / 5 | Broken layout (Big2 template bug) |
| Admin Dashboard | 4 / 5 | Best-in-class mobile transition |
| Admin Lists | 3 / 5 | Workable; toolbar hierarchy inverted |
| Admin Forms | 4 / 5 | Stack well |
| Settings Hub | 4 / 5 | Cards stack cleanly |
| Live Edit canvas | 2 / 5 | Toolbar overlap + ESE off-screen |
| Element Style Editor | 0 / 5 | **Unreachable on mobile** |

---

## Anti-Patterns Confirmed on Mobile

| Anti-pattern | Where |
|---|---|
| Off-canvas content (positive position outside viewport) | Element Style Editor panel |
| Hover-only affordances | Inline row icons; Live Edit right-rail icons |
| Modals before engagement | `+ Add` chooser modal still pops on mobile |
| Empty minimalism | Big2 template's mobile home view |

---

## Drunk-Designer Verdict

> *"Your dashboard scales like a pro and your editor is unusable. The right rail of the most-marketed feature in the product (Live Edit) renders at x = 410px on a 390px phone — that's not a bug, that's a missing media query. Admins on a phone can select elements they cannot style. Fix the rail, fix the toolbar overlap, and your mobile story turns from 'half-finished' to 'shippable'."*
## Inventory
- Email input + submit. Settings: list/audience binding, success/error copy, double opt-in.

## Working ✅
- One of the highest-conversion forms on a marketing site — earning its slot in the picker.

## Internal conflicts ⚠
- **Spam protection unclear** — no captcha / honeypot field surfaced as a default.
- **GDPR consent checkbox** not surfaced as a default — required in many markets.
- **No success-state preview** in the editor — the user only sees the empty form.
- Provider integration (Mailchimp, MailerLite, Brevo) is opaque from the canvas.

## Single highest-leverage fix
Default a GDPR consent checkbox + honeypot field; hide them on US-only sites if explicitly toggled off.

## Quick wins
1. Inline preview for "Thanks for subscribing!" success state.
2. Show the bound provider as a chip on the canvas (`Mailchimp →`).
3. Privacy-policy link slot under the field.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## Findings, by WCAG Pillar

### 1. Perceivable

| # | Finding | WCAG | Severity |
|---|---|---|---|
| P1 | The `Picture` module insertion does not prompt for `alt` text. Inserted images often ship without `alt`. | 1.1.1 Non-text Content | High |
| P2 | Decorative icons in the Live Edit right rail (`+`, store, folder) have inconsistent `title=` and no `aria-label`. | 1.1.1 / 4.1.2 | Medium |
| P3 | Status pills (`PUBLISHED` uppercase green) on Posts list rely solely on colour to convey state. | 1.4.1 Use of Colour | Medium |
| P4 | Element Style Editor's colour pickers do not show contrast ratios between Background and Text. | 1.4.3 Contrast (Min) | Medium |
| P5 | `prefers-reduced-motion` is not respected by `Slider`, `Marquee`, `TextType`, `Skills` modules. | 2.3.3 Animation from Interactions | Medium |
| P6 | The Element Style Editor renders at `x=410` on a 390px mobile viewport — entirely off-screen. | 1.4.10 Reflow | Critical |
| P7 | Image picker thumbnails render as plain grey rectangles on first paint without low-quality placeholders → screen reader users hear "(image)" with no description. | 1.1.1 | Medium |

### 2. Operable

| # | Finding | WCAG | Severity |
|---|---|---|---|
| O1 | Live-edit chooser cards are `<div role="button">` instead of native `<button>` — no native keyboard activation, focus ring inconsistency. | 2.1.1 Keyboard / 4.1.2 | High |
| O2 | No `Cmd/Ctrl+K` global command palette; no documented keyboard shortcut sheet. | 2.1.1 (informational) | Medium |
| O3 | Accordion section in Element Style Editor is not confirmed to follow WAI-ARIA Disclosure pattern (arrow-keys / Home / End). | 2.1.1 | Medium |
| O4 | The Live Edit toolbar overlaps the canvas content on mobile — keyboard users cannot reach overlapped elements. | 2.4.7 Focus Visible / 2.4.11 Focus Not Obscured | High |
| O5 | Per-row `aria-label="Actions"` is non-contextual on every list page. Keyboard navigators cannot tell rows apart. | 2.4.6 Headings & Labels | Medium |
| O6 | Master-checkbox column on Posts/Products lists has no accessible name. | 4.1.2 / 2.4.6 | Medium |
| O7 | "Where to put it" tree in Create-Post modal lists hundreds of items without a search-and-filter alternative — keyboard navigation through 200+ tree items is impractical. | 2.4.5 Multiple Ways | Medium |
| O8 | No `Escape` key tested to close every modal in a focus-trapped manner. | 2.1.2 / 2.4.3 | Medium |

### 3. Understandable

| # | Finding | WCAG | Severity |
|---|---|---|---|
| U1 | Modal heading `Add new` is generic (no context). Sighted users get the surrounding chooser; screen-reader users need clearer phrasing. | 2.4.6 / 3.3.2 | Medium |
| U2 | Title field on Create-Post and Create-Product is **required at submit** but **not marked `aria-required="true"`** in the DOM. (`Price*` on the product modal *is* correctly marked.) | 3.3.2 / 4.1.2 | High |
| U3 | The Element Style Editor's `Italic` is a 2-option select rather than a toggle button → cognitively heavier than necessary. | 3.2.2 (Predictable) | Low |
| U4 | A typography numeric input visibly renders the literal string `NaN` when the underlying CSS value is non-finite. | 3.3.1 Error Identification | High |
| U5 | "Refference image" typo in the AI image-generation tab. | 3.1.5 Reading Level | Low |
| U6 | "Write your post here" label appears inside the Create-*Product* modal (wrong context) — confusing for users who navigate by labels. | 2.4.6 / 3.3.2 | High |
| U7 | The page-tree picker mixes real pages and seeded latin gibberish — screen reader users cannot tell signal from noise. | 3.1.4 Abbreviations / 3.3 (general) | Medium |

### 4. Robust

| # | Finding | WCAG | Severity |
|---|---|---|---|
| R1 | "Select image" modal lacks `role="dialog"` AND `aria-labelledby`. Confirmed in DOM (`role: ""`, `ariaLabel: ""`). | 4.1.2 | Critical |
| R2 | "Link" picker modal lacks `role="dialog"` AND `aria-labelledby`. Confirmed. | 4.1.2 | Critical |
| R3 | Admin "Add new" chooser lacks `role="dialog"` and `aria-labelledby`. (Live-edit chooser DOES have both — inconsistent.) | 4.1.2 | High |
| R4 | Tabs (`Tabs` module) likely missing the ARIA Tabs pattern (`role="tablist"`, `aria-selected`, arrow-key navigation). | 4.1.2 | Medium |
| R5 | Filament list-row buttons emit identical `aria-label="Actions"` for every row on every list page. | 4.1.2 | Medium |

---

## Priority Backlog

### P0 — Critical (block release if unfixed)

### P1 — High

### P2 — Medium

### P3 — Polish

---

## Confidence Caveat

This report is **DOM-only**. Items that need real screen-reader passes:

- NVDA on Windows + Firefox.
- VoiceOver on macOS + Safari.
- VoiceOver on iOS + Safari (mobile).
- TalkBack on Android + Chrome.

A small number of "infer" items above will turn out to be either much worse or much better once a real AT user navigates the flows. The DOM-only conclusions remain accurate as a *minimum* set of issues.
## Inventory
- Click → grid editor opens (rows × cols picker, then editable cells).
- Likely supports header row, alignment per cell.

## Working ✅
- Tables are still the right answer for tabular data; having a real one in the picker beats fake-tables made of columns.

## Internal conflicts ⚠
- **Tables for tabular data only.** No warning prevents users from using a table for layout (a 2010-era anti-pattern).
- **No responsive strategy** — wide tables horizontally scroll on mobile by default. Should at least wrap in `.overflow-x-auto`.
- **No row-header vs column-header distinction** for screen readers.
- **No CSV/Excel paste import.**

## Single highest-leverage fix
Add a CSV / TSV / Excel paste-import in the create flow.

## Quick wins
1. Always wrap in `<div class="table-responsive">`.
2. Mark the first row as `<th scope="col">` by default.
3. Add a "Striped / Bordered / Compact" preset row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory (short)
- Inserts a product grid bound to the shop. Right-rail quick actions: `+ Add element`, `Products` (data source), `Category` (filter).
- Settings drawer is an iframe with `Items list / Settings / Design` tabs.
- "NEW PRODUCT" → opens Filament `Create Product` modal nested two iframes deep.

## Single highest-leverage fix (recap)
Kill the iframe-in-iframe-in-modal architecture; lift the Create-Product modal to the outer document.

## Top three quick wins (recap)
1. Default the products grid to paginated 12 items, not full DB dump (currently renders 12,142 px tall).
2. Rename body editor label `Write your post here` → `Product description` on the Create-Product modal.
3. Add Image / Price / Status columns to the Items list (currently TITLE only).

## Scorecard (recap)
**Journey average: 19 / 35.** See full report for per-surface breakdown.
## Inventory
- A specialised Accordion: question + answer pairs, intended for SEO-rich FAQ pages.

## Working ✅
- Distinct from Accordion *if* it emits FAQPage JSON-LD; otherwise redundant.

## Internal conflicts ⚠
- **Mixed-case casing**: `Faq` (rather than `FAQ`) breaks the picker's ad-hoc convention. Acronyms should be uppercase.
- **Overlap with Accordion**: see `accordion.md`.
- No structured-data emit confirmed.
- No "search this FAQ" toggle for long lists.

## Single highest-leverage fix
Rename to `FAQ` (uppercase) and confirm `FAQPage` JSON-LD emission.

## Quick wins
1. Search-this-FAQ field toggle.
2. Anchor links per question for shareable URLs.
3. "Was this helpful?" mini-feedback per item.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Grug's first walk through the codebase (via UI only)

Grug not have source code but grug see lots of UI clues. Grug shake club at three things.

---

## Big complexity demon #1 — iframe inside iframe inside modal

**Where:** Click any product on Live Edit. Click `Products` quick-action. Drawer opens. Click `New Product`. Modal opens.

The "drawer" is an `<iframe src="/admin/products-module-settings?...">`. The Filament `Create Product` modal lives **inside** that iframe. The whole thing sits **inside** the live-edit canvas iframe. Three browsing contexts deep.

Grug ask: **why?**

- Focus traps must work across three documents.
- Browser back button does not navigate the drawer.
- Screen reader must context-switch three times.
- CSP `frame-ancestors` configuration is now a nightmare.
- Memory: three documents loaded, three Livewire/Alpine instances active.

Grug suspect: someone needed to "isolate" the settings drawer's CSS, picked iframe as the easiest hammer, and then nested the modal inside without thinking.

**Grug fix:** Render the drawer in the parent document. Use Alpine + Livewire scoping (which is already used everywhere else in this admin). Keep one document, kill the iframes.

**Grug verdict:** `COMPLEX: kill the iframe-in-iframe-in-modal`.

---

## Big complexity demon #2 — fifty-two modules in a flat picker

**Where:** Click `+` next to a selected element on Live Edit canvas.

A flat scroll of **52 modules**: Title, Text, Picture, Multiple Columns, Icon, Empty, Inline Table, Add to cart, Button, Video, Pictures, Google Maps, BeforeAfter, Spacer, Audio, Menu, Accordion, Content, Layout Content, Pages, Posts, Products, Tabs, Testimonials, Embed, Logo, Marquee, TextType, Skills, Category, Breadcrumb, Newsletter, Contact Form, Faq, Team Card, Slider, Multilanguage, Background, Facebook Like, Facebook Page, Search, TweetEmbed, Sharer, Blog, Image Rollover, PDF, Rating, Shop, Social Links, Tags, Comments, HighlightCode.

Grug count overlaps:

- `Picture` and `Pictures`: same thing, different plural.
- `Posts` and `Blog`: same thing.
- `Products` and `Shop`: same thing in different shape.
- `Empty` and `Spacer` and `Layout Content`: three names for "I want a hole".
- `Content` and `Text` and `Layout Content`: three names for "long text".
- `Faq` and `Accordion`: same widget different copy.
- `Facebook Like` and `Facebook Page`: same vendor, two boxes.
- `TweetEmbed`, `Sharer`, `Facebook Like/Page`: four social embeds, no group.
- `BeforeAfter` and `Image Rollover`: both swap-on-hover patterns.

**Grug rule:** "abstract after third occurrence." This is opposite — multiple concrete things claiming to be one. Grug suspect plugins/modules grew organically and nobody pruned.

**Grug fix:** Merge the obvious near-duplicates. Group the rest. Twelve to fifteen modules max. Save real options for "Advanced".

**Grug verdict:** `COMPLEX: shrink picker; merge near-duplicates`.

---

## Big complexity demon #3 — five image sources, three different naming styles

**Where:** Click any image-uploader in any modal.

Modal "Select image" has 5 tabs: `My computer · Enter prompt · URL · Uploaded · Media library`. Grug already wrote about the tab order in the design report. Here grug worried about *naming taxonomy*:

- `My computer` — possessive noun phrase.
- `Enter prompt` — verb phrase.
- `URL` — uppercase initialism.
- `Uploaded` — adjective.
- `Media library` — noun phrase.

Five tabs. Five naming conventions. Grug shake head.

**Grug fix:** Five nouns: `Library · Computer · Recent · URL · AI`. Done.

**Grug verdict:** `COMPLEX: pick one taxonomy and apply it`.

---

## Smaller demons grug saw

- **`Empty` is a module.** Grug doesn't know any user who needs an `Empty`. Delete it.
- **`Layout Content` is a module.** Same critique. Internal plumbing leaked into the picker.
- **Two different "Categories"** in the sidebar (post categories vs product categories). Grug would name them differently.
- **Two `New product` buttons** on the Products list page. Grug wonders if a copy-paste accident never got cleaned up.
- **Settings hub has 40+ links and no search.** Grug would put a search at the top.
- **`Big2`, `Bootstrap`** as template names. These are not user names, those are dev names.
- **`HighlightCode`, `TextType`, `BeforeAfter`, `TweetEmbed`** — CamelCase brand names from upstream libraries leaked into the user-facing picker.
- **The `+ Add` toolbar button always opens the same 4-card modal.** Grug always says: "context-aware actions cost less code than universal modals that duplicate work the user already implied."

---

## What grug wants to keep ✅

- Filament admin shell — clean, consistent table primitives.
- Orders page is the right shape: stat cards + status tabs + inline actions. Whoever designed Orders should design every other list.
- Element Style Editor's t-shirt sizing for Spacing and Rounded corners — chef's kiss. Best decision in the product. Keep.
- Live preview iframe in Template Customization. Real-time feedback is good.
- AI image generation co-located with regular image upload. Smart IA decision.
- The Blog/Shop/Pages tab grouping in the sidebar — sensible.

---

## Three commits grug would write today

```
fix: hide Empty and Layout Content modules from user-facing picker

These are internal plumbing concepts. Users do not need to "insert
empty space" — they have Spacer for that. Tagging both as
internal-only.

fix: rename TweetEmbed, TextType, HighlightCode, BeforeAfter

Code identifiers leaked into user-facing labels. Renaming to:
- TweetEmbed → X (Twitter)
- TextType → Typewriter
- HighlightCode → Code Block
- BeforeAfter → Before / After

refactor: lift Create Product modal out of nested iframe

Drawer was rendered as <iframe src="/admin/products-module-settings">.
Modal nested inside. Three browsing contexts for one form. Lifting
both to the parent document; using Alpine scope to isolate styles.
```

---

## Grug's verdict

> *"Microweber a lot of good. Microweber make grug nervous in three places: nested iframes, picker overflow, naming inconsistency. None of these need a rewrite. All three need a saw and an afternoon. Make it work. Make it simple. Then stop."*

**Complexity score:** 5 / 10 (lower is simpler). Most of the codebase is fine. The three demons above add the fragility.
## Inventory
- Buttons to share the current page on Facebook / X / LinkedIn / WhatsApp / email / copy-link.

## Working ✅
- Genuinely useful editorial primitive.
- Pre-fills the share URL/title from the current page.

## Internal conflicts ⚠
- **Three-deep social cluster**: `Sharer`, `Facebook Like`, `Facebook Page` overlap conceptually.
- **Default platforms** are not configurable from the canvas — must edit module settings.
- **No copy-to-clipboard fallback** confirmed.
- **No native Web Share API** path on mobile (single-button flow).

## Single highest-leverage fix
On mobile, render a single "Share" button that triggers the Web Share API; render the per-platform buttons only on desktop.

## Quick wins
1. Inline platform toggle chips on the canvas.
2. Copy-link button always present.
3. SVG icons inlined; no font-icon dependency.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 17 / 30**
## P0 — Critical mobile bugs

| # | Issue | Surface | Suggested fix |
|---|---|---|---|
| 1 | Element Style Editor renders at `x=410` on a 390px viewport — entire right rail unreachable | Live Edit | Convert to bottom-sheet drawer at viewport < 768px |
| 2 | Live Edit toolbar overlaps canvas content (Save button overlays "111" header link) | Live Edit | Add top-padding to iframe canvas equal to toolbar height |
| 3 | `Go Live Edit` admin chip visible on anonymous public site | Public site | Gate behind `auth()` |
| 4 | Big2 template home renders as a 20-screen wall of empty grey placeholders on mobile | Big2 frontend | Audit page template binding (already flagged in Big2 report) |
| 5 | Toolbar wraps to two rows when `⋮ + VIEW + SAVE + device-toggle` don't fit | Live Edit | Collapse non-essential items into a `⋮` overflow at <500px |

## P1 — Mobile UX gaps

| # | Issue | Suggested fix |
|---|---|---|
| 6 | No bottom-tab navigation in admin (relies on hamburger sidebar) | Add fixed bottom-tab nav: Dashboard / Lists / Add / Settings / Account |
| 7 | `+ Add` toolbar button is icon-only on mobile, label gone | Restore label at viewport ≥360px |
| 8 | No sticky `Save` action on long forms | Add sticky bottom-action bar (Save + Cancel) |
| 9 | No quick-status filter tabs on Posts/Products lists | Adopt Orders-style horizontally-scrollable status tabs |
| 10 | Body editor toolbar wraps to 3 rows by default | Ship a minimal-toolbar mode on mobile |
| 11 | Live Edit doesn't auto-scroll selected element into view when the iOS keyboard opens | Implement `element.scrollIntoView({block:'center'})` on focus |
| 12 | "Mobile" device-toggle in Live Edit duplicates real-device width | Default to Mobile when the actual device is mobile; hide the toggle |
| 13 | List page rows are tall (~250px) — only ~3 visible per screen | Add a compact-density toggle |
| 14 | No long-press to multi-select on list rows | Add long-press multi-select |
| 15 | Page tree picker dumps all pages including seeded gibberish | Already flagged on desktop; harder to recover from on mobile |

## P2 — Polish & accessibility

| # | Issue | Suggested fix |
|---|---|---|
| 16 | Cart badge shows `0` when empty | Hide badge when count is 0 (matches desktop fix) |
| 17 | Public site has no skip-to-content link | Add the standard skip link |
| 18 | Search icon-only button has no scope hint | Tap → expand → auto-focus + show scoped placeholder |
| 19 | No swipe-to-refresh on lists | Implement |
| 20 | Custom datetime picker on Publish Date | Use native `<input type="datetime-local">` on touch devices |
| 21 | Status pill uppercase + green is loud at 390px width | Sentence-case + lower contrast |
| 22 | Tab strip horizontally scrolls without a visible affordance | Add subtle right-edge fade indicating more |
| 23 | Touch targets on row edit/link/eye icons may be < 44px | Audit and pad |
| 24 | Mobile menu drawer (when opened) not exercised | Audit reachability and contrast |
| 25 | Marketplace card grid (when narrow) — only one card per row eats real estate | Two-column grid at ≥360px |

## P3 — Wishlist

- Native PWA manifest + offline shell.
- iOS Safari `apple-mobile-web-app-title`.
- Pinch-to-zoom on Live Edit canvas instead of triggering page zoom.
- `viewport-fit=cover` + safe-area insets for notched devices.
- Haptic feedback on save.
## Inventory (Bootstrap template, anonymous visitor)

- **Header**: black bar, hamburger toggle right; the **`Go Live Edit ↗`** blue pill is visible top-left (admin chrome leak).
- Inline social bar (Facebook + LinkedIn icons) and shop icons (search, account, cart with badge `0`).
- Below the header: a thin row of post links (`111`, `Blog`, `Shop`, `Contact us`) which shouldn't be there on the home — looks like a leak from a Posts module.
- Hero area is empty (no banner image, no headline) — just whitespace.
- Then a list of post cards (Story Title, Story Title Two, etc.) with images.
- Footer: the standard branding/contacts block.

## Working ✅

- Header hamburger collapses the menu on mobile.
- Story-card images scale to mobile width.
- Touch-target sizes for the icon row (search, cart) appear adequate (≥44px).
- No horizontal scroll on the page (`overflowX: hidden`, `docWidth = viewportWidth = 390`).

## Internal conflicts ⚠

1. **🐛 `Go Live Edit` admin chip visible** on the anonymous site. Same bug as flagged on desktop but more painful on mobile because it eats prime real estate.
2. **🐛 Big2 template (when active) renders the home as a wall of empty grey placeholders** stretching for 20+ screens. See full-page screenshot. The home-as-product-list bug (already flagged in the Big2 report) is far more visible on mobile.
3. **The `111`/`Blog`/`Shop`/`Contact us` row** above the cards looks like a debugging or stub element — it shouldn't be on the home page hero area.
4. **No mobile navigation drawer** captured — the hamburger toggles `<MENU>` but the captured state shows it closed; behaviour on tap not exercised here.
5. **Cart badge shows `0`** even when there's nothing to count.
6. **No skip-to-content** link visible — accessibility miss.
7. **Hero region is empty** — first viewport above the fold offers no message, no CTA, no value prop.

## Single highest-leverage fix
Suppress the `Go Live Edit` admin chip and any other admin-only chrome whenever the visitor is not authenticated.

## Quick wins
1. Hide cart badge when count is 0 (matches the desktop funnel-badge fix).
2. Add a skip-to-content link.
3. Fix the empty hero by surfacing the page's actual headline + CTA.
4. Remove the rogue `111`/Blog/Shop/Contact us row from the home.
5. Confirm `prefers-reduced-motion` is respected by any page animations.

## Mobile usability score
- Bootstrap template: **3 / 5** — functional but with admin leakage.
- Big2 template: **1 / 5** — broken layout, blank rectangles dominate.
## Inventory
- Inserts an icon. Likely backed by Material/MDI or a custom set.
- Settings: icon name (search), size, colour.

## Working ✅
- Useful for visual hierarchy without image weight.

## Internal conflicts ⚠
- **No icon-pack disclosure.** Users don't know if they're searching MDI, FontAwesome, or a custom set.
- **No accessibility label prompt.** Decorative icons should mark `aria-hidden`; meaningful icons need a label.
- **No SVG inlining vs icon-font choice.** Icon fonts blow up on screen-readers; SVG with `<title>` is the modern path.

## Single highest-leverage fix
Always emit SVG with `<title>` (or `aria-hidden="true"` if marked decorative).

## Quick wins
1. Search by category (Arrow / Brand / UI / Communication…).
2. Recently used row.
3. Bulk replace ("change all chevrons in this layout").

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## I'm `admin@admin.com / admin`. Now what?

I land on the dashboard. It says **"Welcome back, Wallace"** which is friendly but I'm not Wallace and I haven't been here before. So someone else made this account. That's fine, but the welcome is now lying to me.

There's a chart of visitors. The number says 0 online. There are stats: Emails 0, Last comments 127, Sales $1,695.58, Recent Orders 4. **Recent Orders 4? — but I haven't sold anything yet?** It's the seed data. I don't know that. I think I have orders.

I want to make my home page. I look for "Edit my home page" or "Get started" and there's nothing. The big green button at the top says **Live Edit**. I click it because it's the most colourful thing.

It opens a kind of preview of a website that says "111" "Blog" "Shop" "Contact us". Why does it say "111"? I didn't write that. There's a `Go Live Edit ↗` button. I'm already in live edit. Why is there another `Go Live Edit` button on top of my page?

`P0 — JARGON TRAP`: "Live Edit" used twice for two different things.

I click on `My title`. A blue box appears around it. I want to make it say "Joe's Café". I just type. Nothing happens — wait, I can type when I click again. The first click just selects.

`P3 — FRICTION`: First click selects, second click types. Two-click model not signposted.

There's a panel on the right with `Typography · Background · Spacing · Container · Border · Rounded corners · Animations · Shadow · Classes · AI Style Editor`. Ten sections. I want to make my heading bigger. I find Typography. I scroll past Font, Align, Color, **Font Size**. Good. There's a slider. I drag it.

It works. Cool.

There's a section called **Classes**. I don't know what that is. I click it. It says nothing. I close it. Why is it there?

`P2 — CONSEQUENCE BLINDNESS`: "Classes" means HTML/CSS classes. As a cafe owner I have no idea. The label is jargon.

I want to add my menu. I click `+` on the toolbar. A modal opens: **New Page · New Post · New Category · New Product**. I want to add my menu — what's the difference between a Page and a Post? I read the descriptions. "A standalone page like About, Services or Contact." OK so for my menu I want a Page. Click.

I'm sent to a long form with tabs: `Content · Custom Fields · SEO · Advanced`. Title is the only required field. I type "Menu". I click Save.

I'm now on `/admin/pages/<id>/edit`. There's no "View on site" or "Add to navigation" button I can find. Wait — there's a "Live edit" button in the top-right. I click. Nothing happens.

`P1 — INVISIBLE AFFORDANCE`: After saving a new page, no clear path to "see it" or "publish it to the menu".

I go back to Dashboard via the sidebar. Find Pages. There's my "Menu" page. I click. Same form. There's a "Where to put it" section with a tree of pages and **dozens of latin gibberish names** like "Ipsa doloribus perferendis." and "Reprehenderit tenetur molestiae."

`P0 — TRUST BREAK`: This looks like real data on someone else's site. Either I'm seeing things I shouldn't or this software shipped me junk. I lose confidence.

I want to add a product because the dashboard claims I have orders. I click `Shop → Products`. The list says **every product is $0.00**. But the dashboard says Sales $1,695.58. **Either the list is broken or the dashboard is.** I don't know. I lose more confidence.

`P0 — DATA INCONSISTENCY`: $0.00 on Products list vs $1,695.58 in Sales card.

I click `+ New product`. The modal opens. Title placeholder asks **"What's the product name?"** — friendly! I type "Latte". The required field is **Price**. I type 4.50. There's a section called **More options** that's collapsed. There's a body editor labelled **"Write your post here"**.

`P1 — JARGON TRAP`: I'm creating a *product*, not a *post*. Why does the editor think I'm posting?

I save. I look for the product. I expand `Where to put it` which lists more latin gibberish. I'm not sure where my product went.

I want to change the colour of my whole site. I navigate. I find `Settings`. There are **forty links**. I scan: General · Template · SEO settings · Login & Register · Privacy Policy · Custom Tags · Cookie Notice · Files · Comments · Faqs · Ratings · Menu · Template Customization · Media Library — and that's just Website Settings. There are also Shop Settings, Customization Settings, Email Settings, System Settings, Language Settings.

I just want to change one colour. **No search.**

`P1 — FRICTION`: 40 settings links, no search. I have to read them.

I click "Template Customization". I find Primary Color. I change it from blue to red. The bottom of the screen says "Click 'Save' to persist changes". **There is no Save button.**

`P0 — BROKEN PROMISE`: The page tells me to click Save. Save doesn't exist.

I close the tab.

---

## My P0–P4 Findings (this is a real customer's voice, not a clinical report)

### P0 — Will lose users today

- The page-tree picker shows seeded gibberish to first-time admins. Trust evaporates immediately.
- Products list shows $0.00 while dashboard shows $1,695.58 in sales. I don't trust either.
- Template Customization tells me to click Save. There's no Save button.
- Big2 template (if I switch to it) breaks my home page into a wall of empty grey rectangles.

### P1 — Will frustrate first-time users

- "Live Edit" button on the public site, "Live edit" button in the admin form, "Live Edit" green pill in the toolbar — three different "live edit" affordances doing different things.
- Settings hub has 40+ links and no search.
- Body editor in *Create Product* says "Write your post here". I'm not making a post.
- After creating a Page or Post I can't tell where it went or how to "see it on the site".
- "Welcome back, Wallace" — I'm not Wallace. The greeting is wrong on first login.

### P2 — Surface these on the help page

- "Classes" panel in the Element Style Editor — no idea what classes are.
- "Empty" module — what does Empty mean as a thing I can insert?
- Module names like "TextType", "BeforeAfter", "HighlightCode" — those are computer terms.

### P3 — Polish

- Click-to-select then click-again-to-edit is a learning curve.
- Status pills shouting `PUBLISHED` in green make me think every row is "very published".
- Action icons in list rows have no labels until hover.

### P4 — Wishlist

- A "Get started" card on the dashboard with three big buttons: `Edit your home page · Add a product · Set up email`.
- A "Tour" mode that walks me through making my first page, post, and product.

---

## Quote I'd give the support team

> *"It looks finished but it doesn't feel finished. Half of it shows me other people's data, the other half tells me to click buttons that aren't there. I want to like this thing because the design when I clicked the heading was great. But I closed the tab."*
## Inventory
- Vertical empty space. Settings: height (px / em / rem).

## Working ✅
- Cleaner than typing `<br><br>` or empty paragraphs.
- Inline drag handle (presumed) for sizing.

## Internal conflicts ⚠
- **Redundant with `Empty`** — see Empty module report.
- No mobile-vs-desktop separate height — common need.
- No "snap to design system" (8px grid).

## Single highest-leverage fix
Snap height adjustments to a 8px grid by default; surface a "free" toggle for power users.

## Quick wins
1. Hover preview shows the height in px and rem simultaneously.
2. Per-breakpoint heights (mobile / tablet / desktop).
3. Delete the redundant `Empty` module so Spacer is the single source of "vertical air".

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 4 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## Inventory
- Inserts a styled CTA. Inline label edit. Settings: link target (uses Link Picker modal — see `DESIGN_REPORT_LINK_PICKER_MODAL.md`), style preset, size.

## Working ✅
- Default style is template-aware (orange CTA on the seeded site, capsule pill on Big2).
- Inline label edit feels natural.

## Internal conflicts ⚠
- **No primary/secondary/ghost preset row** in the canvas — must descend into Element Style Editor.
- **Link picker is the same minimal modal flagged elsewhere** — accepts garbage URLs, no internal-page search.
- **No icon-prefix / suffix** baked in (most modern CTAs have an arrow).
- **No loading state** for buttons that submit.

## Single highest-leverage fix
Add inline preset chips (`Primary · Secondary · Ghost · Outline · Link`) above the button on selection.

## Quick wins
1. Append-icon picker (arrow, plus, external-link).
2. Built-in `target="_blank"` + `rel="noopener noreferrer"` when external.
3. Hover-state preview toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## Inventory
- Inline editable heading element. No modal, no settings sidebar; clicking surfaces the rich-text inline toolbar (Bold/Italic/Underline/Strike/Sub/Sup/Link/H2/H3/align…).

## Working ✅
- Direct manipulation — type to edit. The framework's "voice made visible" (typography first) lives in this module.
- The Element Style Editor's Typography section governs the heading's appearance fully.

## Internal conflicts ⚠
- **Default heading level is unclear.** Inserting `Title` produces an `<h2>` regardless of context. Should be context-aware (`<h1>` if no h1 exists, `<h2>` otherwise) or expose a heading-level switcher.
- No live SEO/keyword hint.
- No outline view across the page — three Title modules in a row may all be `<h2>` and the user can't tell.

## Single highest-leverage fix
Add a small inline heading-level chip (`H1 · H2 · H3 · H4 · H5 · H6`) above the field on first focus.

## Quick wins
1. Auto-detect first/only Title on a page → render as `<h1>`.
2. Show the resolved tag (`<h2>`) under the inline toolbar.
3. Add `aria-required` to required Title fields in templates that need one.

## Drunk-Designer scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 4 |
| Coherence | 3 |
| Scalability | 4 |
| Distinctiveness | 2 |
**Total: 19 / 30**
## Inventory
- Embeds Facebook's Like button.

## Working ✅
- Single-purpose social widget.

## Internal conflicts ⚠
- **GDPR / privacy**: loads Facebook tracking. No consent gate.
- **Single-platform bet**: Facebook is one of many; why not Twitter/X, LinkedIn?
- **Dated**: 2010s pattern; Like buttons rarely used in 2025+.
- **No fallback** when Facebook is blocked by the user (China, ad-blockers).

## Single highest-leverage fix
Defer-load behind a one-click "Show Facebook widget" placeholder until the user opts in.

## Quick wins
1. Combine with `Sharer` / `TweetEmbed` into a single `Social embed` module.
2. Show the button in a privacy-respecting "lite" mode that hydrates on click.
3. Hide on cookie-rejection.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 2 |
| Scalability | 1 |
| Distinctiveness | 1 |
**Total: 10 / 30 — strong consolidation candidate.**
## Baselines (what was measured directly)

| Surface | Hard fact (live DOM) | Implication |
|---|---|---|
| Live Edit canvas — `module-shop-products` after insertion | `getBoundingClientRect().height = 12,142 px` | Renders the entire product database in one DOM tree. |
| Page-tree picker (in Create-Post / Create-Product / Image picker) | Lists hundreds of items including seeded gibberish; not virtualised | One forced layout for every option. |
| Live Edit toolbar at 390px | Wraps to two rows | One reflow per session start. |
| Console warnings | 0 errors, 18–28 warnings per session | Indicates unaddressed runtime quirks. |
| Element Style Editor positioning at 390px | Renders at `x=410` (off-screen) | Layout cost incurred for an invisible panel. |
| Marketplace package grid | Default 24 cards per page with full cover images | Each card is a real image fetch. |
| Modules grid | ~90 tiles, all rendered up-front | No virtualisation. |

---

## Top hypotheses (need real-world test to confirm)

### H1. Products module renders the whole catalogue in DOM (CRITICAL)

**Evidence:** `module-shop-products` `rect.height = 12142px` after insert.
**Hypothesis:** No pagination at the *DOM* layer. Every row in the product table is rendered.
**Test:** Time to first interaction (TTI) of `/admin/live-edit?url=...` on a page that contains the Products module. Expect linear cost in product count.
**Mitigation:**
- Default to `take(12)` server-side; surface "Load more" client-side.
- Lazy-load product images with `loading="lazy"`.
- Consider virtualised list when count > 50.
**Estimated win:** Cuts editor open-time on shops with 100+ products by an order of magnitude.

### H2. Page-tree picker renders all pages flat (HIGH)

**Evidence:** Captured DOM listed hundreds of seeded entries (`Ipsa doloribus perferendis.` etc.) interleaved with real pages.
**Hypothesis:** No virtualisation; every option rendered into the DOM.
**Test:** Open Create-Post modal on a site with 1,000 pages — measure modal-open latency.
**Mitigation:**
- Replace flat tree with a search-and-pick combobox.
- Or paginate at 50 with "show more".
**Estimated win:** Sub-100ms modal-open even on large sites.

### H3. Iframe-in-iframe-in-modal multiplies bootstrap cost (MEDIUM)

**Evidence:** `<iframe src="/admin/products-module-settings?...">` confirmed; Filament modal nested inside.
**Hypothesis:** Three runtimes (canvas iframe + settings iframe + modal Filament) bootstrap before the user can edit.
**Test:** Compare TTI of clicking `Products` quick-action vs equivalent flat modal.
**Mitigation:** Lift to parent document (also recommended by Grug + UX personas).
**Estimated win:** Half a second to a second of perceived latency on cold loads.

### H4. Modules admin grid loads 90 tiles up-front (MEDIUM)

**Evidence:** Captured ~90 tiles in `/admin/module-resource/modules`.
**Hypothesis:** Each tile triggers an icon fetch (some are SVG inlined, some `<img>` referenced).
**Test:** Network panel on first load — count requests to `/modules/.../icon`.
**Mitigation:**
- Inline SVGs.
- Use a sprite-sheet for the icon set.
- Lazy-load below-the-fold tiles.

### H5. Marketplace grid loads 24 full cover images per page (LOW)

**Evidence:** Captured screenshot shows 24 cover-image cards per page.
**Hypothesis:** Each card fetches a high-res cover. No `srcset` confirmed.
**Test:** Network tab on `/admin/marketplace`.
**Mitigation:** Responsive `srcset`, AVIF/WebP, `loading="lazy"`.

### H6. Live Edit canvas iframe re-bootstraps the entire site CSS (MEDIUM)

**Evidence:** Live Edit re-renders the public site inside its iframe with admin chrome layered on top.
**Hypothesis:** The iframe payload is the full public-site bundle (CSS, JS) plus admin overlay assets.
**Test:** Open Live Edit on a fresh page; record total transferred bytes.
**Mitigation:** Strip non-essential public-site bundles when running in `editmode=y`.

### H7. Image-picker Media Library renders thumbnails as plain grey rectangles (DATA)

**Evidence:** Captured screenshot shows grey rectangles instead of thumbnails.
**Hypothesis:** Thumbnails missing on disk / Cloudflare cache miss / placeholder logic broken.
**Test:** Inspect the `<img>` elements; check `src` URLs.
**Mitigation:** Generate thumbnails on upload + cache; show low-quality placeholder behind every grid cell.

---

## Caching observations (what should be in place)

| Asset class | Recommendation |
|---|---|
| Images uploaded by users | Convert to AVIF/WebP on upload; store original + variants. |
| Static admin assets | Long-lived cache + content-hashed filenames. |
| Dashboard KPIs (`Sales`, `Recent Orders`) | Cache for 30s in Redis to avoid live aggregation on every dashboard render. |
| Site stats charts | Pre-aggregate hourly into a `site_stats_hourly` table; query that on read. |
| Marketplace catalogue | Cache the remote fetch for 1h; provide manual `Reload Packages` (already exists ✅). |

---

## Latency budget (targets)

| Surface | p50 | p99 |
|---|---|---|
| Admin dashboard | < 200ms | < 600ms |
| Posts/Products list (50 rows) | < 150ms | < 500ms |
| Create Post modal open | < 100ms | < 300ms |
| Live Edit cold load (single page) | < 1.5s | < 3s |
| Image picker open | < 100ms | < 300ms |

---

## Top 5 backlog items (by measurable impact, P0 first)

1. **[P0] Paginate the Products module on canvas.** Render 12 by default, lazy-load the rest. The 12,142 px DOM tree is the strongest measurable bottleneck observed.
2. **[P0] Replace the page-tree picker with a search-and-pick combobox.** Pages-with-thousands-of-entries scenarios are realistic on any mature site.
3. **[P1] Lift the Create-Product modal out of the nested iframe.** Saves ~500ms TTI per click.
4. **[P1] Generate thumbnails on upload.** Fix the grey-rectangle media library.
5. **[P2] Add server-side cache (30s TTL) to the dashboard KPI aggregations.**

## What this audit could not measure (needs production-like environment)

- Database query plans + N+1 counts.
- Backend p99 latency under real load.
- CDN behaviour and cache-hit ratios.
- Memory and PHP-FPM child saturation.
- Background-queue depth (orders, emails).

These need a load-generator hitting a staging environment with realistic data and a profiler attached. Recommend: `wrk -t12 -c400 -d60s` against `/admin/live-edit?url=/blog`, `/api/orders`, `/admin/products`, while running Blackfire / Tideways behind it.
## Inventory
- Auto-generated trail of the current page's ancestry.

## Working ✅
- Resolved from page tree automatically — no manual maintenance.
- SEO-relevant when wired to BreadcrumbList JSON-LD.

## Internal conflicts ⚠
- **JSON-LD emit not confirmed.** SEO miss if absent.
- **Separator character** is not customisable from the canvas (›, /, →).
- **Truncation strategy** absent: deep trees overflow on mobile.
- Hidden on home page is a default — confirm.

## Single highest-leverage fix
Emit `BreadcrumbList` JSON-LD whenever this module renders.

## Quick wins
1. Separator picker chip.
2. Mobile truncation: `Home › … › Current`.
3. `aria-label="Breadcrumb"` on the wrapper `<nav>`.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 4 |
| Emotion | 2 |
| Usability | 4 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 18 / 30**
## Inventory
- Row of icon-links to the brand's social profiles (Facebook, X, Instagram, LinkedIn, YouTube…).

## Working ✅
- Common need; saves rolling icons by hand.
- Already present by default in the Bootstrap and Big2 templates' headers.

## Internal conflicts ⚠
- **Two of the seeded URLs may be empty** — module should hide platforms whose URL is missing.
- **Icon family inconsistency** with other modules' icons.
- **No `rel="me"` for fediverse** discoverability.

## Single highest-leverage fix
Hide platforms with empty URLs by default; surface "Add Instagram link" CTA when a slot is empty.

## Quick wins
1. SVG icons inlined.
2. `rel="me"` on profile links.
3. Hover-state colour matches each platform's brand colour as a default.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 17 / 30**
## Inventory (Posts list at 390px)

- Heading: `Posts` centered.
- Primary CTA `+ New post` (centered, dark filled).
- Toolbar row: sort handle ↕, **`Categories`** (large dark button — visually dominates), Search input.
- View toggle + filter funnel (badge `0`).
- Master checkbox.
- List rows: thumbnail above title, byline, action icons (Link / Eye / Pencil), status pill (UPPERCASE GREEN), ⋯ overflow.

## Working ✅

- List rows stack cleanly into a single column.
- Action icons remain finger-tappable (≥40px).
- Pagination remains functional at the bottom.

## Internal conflicts ⚠

1. **`Categories` button still wins the visual fight** — same critique as desktop, more painful on mobile because it eats half the screen width.
2. **Filter funnel badge `0`** persists.
3. **No quick-status filter tabs** — same critique as Orders comparison: Posts/Products/Pages/Users would benefit from a horizontally-scrollable status tab row on mobile.
4. **Each row uses ~250px height** — only ~3 rows visible per screen. Compact mode would help.
5. **Status pills (`PUBLISHED` uppercase green)** dominate the row.
6. **No mobile-friendly bulk-select gesture** — long-press to select rows would feel native.
7. **Per-row `Actions` overflow menu** at three different positions across screens because some rows have status drop-down toggle + ⋯ + edit icon — visual noise.

## Single highest-leverage fix
Adopt the Orders status-tab pattern with a horizontally-scrollable tab row above the table.

## Quick wins
1. Compact density toggle.
2. Long-press to multi-select.
3. Demote `Categories` button to a ghost chip (matches desktop recommendation).
4. Soften status pills.

## Mobile usability score: **3 / 5**
## Inventory
- Inserts a blog landing — list of posts with archive controls (year/month, category).

## Working ✅
- Distinct from `Posts` *if* it bundles archive controls; otherwise duplicate.

## Internal conflicts ⚠
- **Overlap with `Posts` module.** Two paths to the same goal.
- Big2 template is missing the `Blog` layout-picker entry — see `DESIGN_REPORT_TEMPLATE_BIG2.md`.
- No RSS auto-generation hint.
- No author / category landing-page support.

## Single highest-leverage fix
Define `Blog = Posts + sidebar archive controls`. If the sidebar isn't there, the modules are equivalent — merge.

## Quick wins
1. Auto-generate `/feed.xml` whenever this module is on a page.
2. Reading-time, author, category badges per item.
3. Optional Featured Posts row.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Inventory
- Series of label + percentage bars. Likely animated on scroll-into-view.

## Working ✅
- Resume / portfolio template staple.

## Internal conflicts ⚠
- **Skill bars are subjective and dated.** "JavaScript: 85%" carries no real meaning. Modern portfolios use chips/tags.
- **No accessible alternative**: screen-reader users get a percentage but no qualitative reading.
- `prefers-reduced-motion` for the fill animation likely not respected.
- No data-source binding (could pull from a profile JSON).

## Single highest-leverage fix
Offer an alternative `Skill chips` rendering as a default toggle alongside the bar UI.

## Quick wins
1. Honor reduced-motion (snap to final value).
2. ARIA: `role="progressbar" aria-valuenow="85" aria-valuemax="100" aria-label="JavaScript"`.
3. Group skills under category headers.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Inventory
- Auto-rotating carousel of slides with images + optional headlines + CTAs.

## Working ✅
- Hero slider is a template-staple expectation, even when overused.

## Internal conflicts ⚠
- **Auto-rotating carousel** is on the Drunk-Designer anti-pattern list. The first impression of a Slider should pause until interaction, not rotate users away from the slide they were reading.
- **`prefers-reduced-motion`** likely not respected.
- No deep-link to a specific slide.
- No accessible name on the carousel; arrow buttons unlabelled.

## Single highest-leverage fix
Default the auto-rotate to OFF; require an explicit toggle to enable, and always show pause/play controls.

## Quick wins
1. Honor `prefers-reduced-motion: reduce`.
2. Slide indicators + numbered counter (`2 / 5`).
3. Accessible labels on prev/next buttons.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Drop in raw HTML / iframe / script. Power-user escape hatch.

## Working ✅
- Necessary for one-off third-party widgets (Calendly, Stripe, custom forms).

## Internal conflicts ⚠
- **Security**: arbitrary HTML/JS opens XSS surface for non-admin users. Confirm RBAC: only admins can edit Embed.
- No sandbox preview — pasted code may break the page in prod, not in the editor.
- No allow-list of known providers (would convert pasted iframe URLs to safer privacy-respecting variants).
- No "set width / height / aspect-ratio" wrapper.

## Single highest-leverage fix
Wrap pasted iframes in a responsive `aspect-ratio: 16/9` container by default and surface a chip to override.

## Quick wins
1. RBAC check: only admins can insert Embed.
2. Preview pane below the textarea showing the rendered embed.
3. Strip `<script>` for non-admin roles.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 15 / 30**
## Inventory
- Inserts a navigation menu bound to a Microweber Menu (Header Menu / Footer Menu / etc.).
- Settings: which menu, orientation, mobile breakpoint.

## Working ✅
- Bound to a real Menu entity — single source of truth.
- Live-updates when the bound menu changes.

## Internal conflicts ⚠
- **Mobile menu (hamburger) styling** is template-controlled, opaque to the user.
- No mega-menu support visible.
- Active-state styling not surfaced as a control — users have to know which CSS class to override.
- "Menu" appears twice in the system — as a module here and as a separate admin entity (the Menus database). The relationship is implicit.

## Single highest-leverage fix
Show the bound Menu name (`Header Menu`) and a `Edit menu →` link inside the canvas selection.

## Quick wins
1. Mega-menu boolean toggle.
2. Active-link colour control surfaced on the canvas.
3. "Sticky on scroll" toggle.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Executive Summary

Microweber's user-facing surface is functional and the shop / live edit features work. From a security posture, the visible gaps cluster in two areas:

1. **Tenant-leak / fixture-leak**: every picker (page tree, products list, media library) exposes seeded test data and dummy gibberish. On a hosted multi-tenant install, this is at minimum reputational; in the worst case it suggests cross-tenant data isolation boundaries are flexible.
2. **Embed module + Generic HTML widgets**: the Live Edit `Embed` module accepts arbitrary HTML/iframe/script. Without RBAC enforcement (unverified), this is a stored-XSS vector for any role allowed to edit pages.

No attempted exploit was performed. All findings below are observation-driven.

---

## OWASP Top 10 — observations

### A01: Broken Access Control

**OBSERVED — `Go Live Edit` chip visible to anonymous visitors.**
- The public site renders a `Go Live Edit ↗` button when an admin session exists in the browser. **Confirm**: behaviour as a fully unauthenticated visitor needs a separate test.
- If the chip leaks the live-edit URL (including the page id) to non-admins, that is **information disclosure** (A01).

**INFER — Per-page edit permissions**: Microweber supports user roles but the audit did not test whether an `editor` role can see admin-only routes (Settings, Users, Marketplace). **Test:** sign in as a downgraded role and confirm the sidebar entries hide.

**INFER — Direct Object References**:
- `/admin/pages/1271/edit` exposes a numeric page id. If an editor role can edit page 1271 without permission, that is IDOR.
- `/admin/products/<id>/edit` likely follows the same pattern.

**Backlog:**

### A02: Cryptographic Failures

**INFER** — Out of scope for UI audit. **Source-code follow-up:**
- Are passwords hashed with `password_hash` / bcrypt or argon2id?
- Is the `remember_me` cookie HMAC-signed?
- Is TLS enforced via HSTS preload?

### A03: Injection

**INFER — Embed module**: the Live Edit `Embed` module advertises raw HTML/iframe/script. If non-admin roles can use it, the surface is a stored-XSS vector.

**OBSERVED — Search inputs across Posts, Products, Pages, Site stats**: tested with normal text only. Need to confirm:
- Is the search query sanitised before being put into the URL?
- Is the search API parameterised? **Test**: try `'; DROP TABLE posts; --` style probes (in a sandbox).

**Backlog:**

### A04: Insecure Design

**OBSERVED — Iframe-in-iframe-in-modal Products flow**: the Create-Product form is nested two iframes deep inside a Filament modal. Beyond UX impact (covered elsewhere), this design:
- Multiplies CSP / `X-Frame-Options` configuration surface area.
- Makes it harder to centrally enforce `frame-ancestors`.
- Increases the chance of clickjacking-style overlays going undetected.

**OBSERVED — `Open in new tab` checkbox in the Link Picker** without observable `rel="noopener noreferrer"` enforcement: tabnabbing surface (A04 / A07).

**Backlog:**

### A05: Security Misconfiguration

**OBSERVED — Seeded fixtures in production-looking surfaces**:
- The Pages tree (visible inside Create-Post and Create-Product modals) lists hundreds of latin gibberish entries (`Ipsa doloribus perferendis.`, `Reprehenderit tenetur molestiae.`, `Patched Title`, etc.) interleaved with real pages.
- The Users list shows `test-admin-69c7e92...@example.com` accounts.
- The Media library shows seeded thumbnails (`test-upload`, `Move Me`, `Bulk Test`).
- Products list shows `Dusk Product 1777313473`.

**Implication:** if this is a production install, sensitive test data is reachable. If it is a demo/dev install, the *fixtures-shipped-with-installer* leak directly into the user's first-day experience.

**OBSERVED — Big2 template footer literal `© Big2 Header. /`**: information disclosure of internal template variable name (low severity).

**Backlog:**

### A06: Vulnerable & Outdated Components

**INFER** — Out of scope for UI audit. **Source-code follow-up:**
- `composer audit` for PHP CVEs.
- `npm audit` for any frontend bundles.
- Check Filament, Livewire, Alpine.js versions.

### A07: Identification & Authentication Failures

**INFER — Login & Register settings exist** (`/admin/settings/Login & Register`). Did not test:
- Brute-force rate limiting.
- Account lockout policy.
- 2FA / MFA availability.
- Password complexity policy.

**Backlog:**

### A08: Software and Data Integrity Failures

**INFER — Updater** is exposed in Settings → System Settings → Updater. Need to confirm:
- Are updates fetched over HTTPS?
- Are package signatures / checksums verified before install?
- Does the marketplace verify package integrity?

**Backlog:**

### A09: Security Logging & Monitoring Failures

**OBSERVED — No visible audit log** in the admin. Settings does not expose an "activity log" or "audit trail" page.
- Privileged actions (user deletes, role changes, settings edits) need an immutable log.

**Backlog:**

### A10: Server-Side Request Forgery (SSRF)

**INFER — Image URL tab** (`Select image → URL`): user pastes an image URL. If the server fetches that URL to mirror the image, it's an SSRF surface. Confirm:
- Are private-IP ranges (10.0.0.0/8, 169.254.169.254, etc.) blocked?
- Is the fetch timed out and capped?

**Backlog:**

---

## STRIDE — Live Edit Modal-in-Modal Flow

| Threat | Likelihood | Notes |
|---|---|---|
| **S**poofing | LOW | Filament/Laravel auth handles session; CSRF tokens assumed in place. |
| **T**ampering | MEDIUM | Iframe-nested forms make CSP enforcement harder. |
| **R**epudiation | HIGH | No audit log → admin actions cannot be traced. |
| **I**nformation disclosure | MEDIUM | Seeded data leaks; numeric IDs in URLs. |
| **D**enial of service | MEDIUM | Products module renders all rows in one DOM tree (~12k px) — a malicious admin or large catalogue can hang the editor. |
| **E**levation of privilege | LOW (untested) | Role hardening not audited. |

---

## Top 5 Backlog Items (P0)

1. **Audit log page** for all privileged actions (A09).
2. **Restrict the `Embed` module** to admins only (A03).
3. **`rel="noopener noreferrer"`** auto-applied to new-tab links (A04).
4. **Gate seeded fixtures** behind a testing flag (A05).
5. **Confirm IDOR** on `/admin/pages/<id>` for non-admin roles (A01).
## Inventory
- Series of horizontal tabs with content panels each.

## Working ✅
- Necessary primitive for product detail pages, comparison sections.

## Internal conflicts ⚠
- **No "first tab is default" hint** — three-tab block opens with all collapsed in some templates.
- Likely lacks ARIA Tabs pattern (`role="tablist"`, `aria-selected`, arrow-key navigation).
- No vertical-tabs option.
- Default tab labels read as "Tab 1 / Tab 2" — uncreative starting state.

## Single highest-leverage fix
Implement the ARIA Tabs pattern correctly (roving tabindex + arrow keys).

## Quick wins
1. Vertical-tabs preset toggle.
2. URL-fragment binding (`#tab-2`).
3. Suggest meaningful default labels based on context.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 16 / 30**
## Inventory
- Embeds a single tweet by URL.

## Working ✅
- One-tweet embeds are common in editorial; module-ising it saves manual `<blockquote>` wrangling.

## Internal conflicts ⚠
- **CamelCase brand name** `TweetEmbed`. Rename to `Tweet`.
- **Twitter is now X** — the rename has not propagated to this module.
- **Privacy / consent** issues identical to other social embeds.
- **Volatile**: X has changed embed APIs and pricing multiple times; brittle dependency.

## Single highest-leverage fix
Rename to `X (Twitter)`, store the tweet's resolved text + author + date as a privacy-respecting fallback, fall back to that if the embed fails.

## Quick wins
1. Privacy-respecting deferred load.
2. Strip tracking params on insert.
3. Always store an attribution block as fallback.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 2 |
| Distinctiveness | 2 |
**Total: 11 / 30**
## Inventory at 390px

### Top toolbar (left → right)
- Back arrow
- Insert Layout `+`
- Page-picker dropdown (collapsed to `▾` only)
- Tools menu `⋮`
- `VIEW` button
- `SAVE` button
- Hamburger (right side)

### Canvas area
- The site iframe takes remaining width.

### Element Style Editor right rail
- **Renders at `x = 410px` on a 390px viewport** — confirmed via DOM `getBoundingClientRect()`.

## Working ✅

- The toolbar is **present** on mobile (not hidden as some editors do).
- The iframe canvas does render the page.
- `VIEW` and `SAVE` are reachable.

## Internal conflicts ⚠

1. **🐛 CRITICAL: Element Style Editor panel is off-screen.** Confirmed: `rect: [410, 185, 310, 32]` while `viewportWidth = 390`. The right rail is positioned at `x: 410px` which is 20px beyond the viewport's right edge. The user cannot scroll horizontally because `overflowX: hidden` on body. **The most-marketed feature in the product is unreachable on mobile.**

2. **🐛 Toolbar overlaps canvas content.** Confirmed in screenshot: the green `SAVE` button overlaps the public site's "111" link in the header. The toolbar is fixed-positioned over the iframe; on mobile the iframe doesn't get a top-padding to clear it, so the canvas content slides under the toolbar.

3. **Toolbar wraps to two rows** because the device-toggle (Desktop / Mobile preview), `⋮`, `VIEW`, and `SAVE` don't all fit on one row. Layout instability when modal/menu opens.

4. **No "Mobile preview"** toggle visible in the captured state (the desktop has Desktop / Mobile device-toggle). On a phone the desktop-preview default is wrong by definition.

5. **Add Layout / module picker (`+`)** still works — opens the picker. But the picker itself takes the full screen on mobile, which is OK.

6. **Selecting an element shows no quick-toolbar nearby** — on desktop the `+` add-element + Products/Category icons appear at the right rail; on mobile they should appear inline below the selected element.

7. **No bottom sheet / drawer** for the Element Style Editor. The right-rail metaphor is fundamentally desktop-shaped; on mobile the panel should slide up as a bottom sheet on selection.

8. **The "Mobile" device-toggle button** (when present) confuses purpose: it switches the *preview* to a mobile width — but the user is already on a mobile device. The toggle should be inverted or hidden on real mobile.

9. **No long-press to open module-actions menu** confirmed.

10. **Inline edits (typing into a heading)** trigger the iOS keyboard which obscures the bottom half of the canvas — no scroll-into-view to keep the cursor visible.

## Single highest-leverage fix
**Convert the right rail to a bottom-sheet drawer on viewports < 768px.** When an element is selected, slide up a sheet with the same Element Style Editor sections.

## Quick wins
1. Add `padding-top` to the iframe canvas to clear the fixed toolbar.
2. Wrap or collapse the toolbar items into a `⋮` overflow on viewports <500px.
3. Default Live Edit's preview to "Mobile" when the user agent is mobile.
4. Auto-scroll selected element into view when iOS keyboard opens.

## Mobile usability score: **2 / 5** — toolbar overlap + ESE off-screen.
## Inventory
- Lists the site's categories (post categories, product categories, or both).

## Working ✅
- Useful for shop sidebars and blog landing pages.

## Internal conflicts ⚠
- **Scope ambiguity**: posts vs products vs all. The picker doesn't say.
- Default render is a flat list — categories often have hierarchy (parent/child) that should be visualised.
- No empty-state UX.

## Single highest-leverage fix
Add a scope chip on insert: `Posts · Products · All`.

## Quick wins
1. Render hierarchy by default; flat-list as a toggle.
2. Item counts per category (`Pricing (3)`).
3. Active-category highlighting when listed inside a category landing page.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 2 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 14 / 30**
## Inventory
- Multi-image gallery. Likely supports grid, masonry, carousel layouts.

## Working ✅
- The plural module exists alongside `Picture` — gallery is a separate concept, correct.
- Bulk upload is implied by the underlying image-picker (see image-upload report).

## Internal conflicts ⚠
- **`Picture` vs `Pictures` is a near-duplicate** that confuses the picker; users may pick wrongly.
- No layout-style chip row (`Grid · Masonry · Carousel · Lightbox`) visible by default.
- No bulk alt-text prompt — gallery a11y tax compounds.
- No re-order drag-and-drop confirmed.

## Single highest-leverage fix
Merge `Picture` and `Pictures` into one module that auto-switches to gallery mode when the user adds a 2nd image.

## Quick wins
1. Bulk alt-text editor for the gallery.
2. Lightbox toggle on the canvas.
3. Default lazy-loading for all images in the gallery.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 3 |
| Usability | 3 |
| Coherence | 2 |
| Scalability | 3 |
| Distinctiveness | 3 |
**Total: 16 / 30**
## Inventory
- Inserts a list/grid of pages. Filter by parent, tag, count.

## Working ✅
- Canonical pattern for "Our services" / "Locations" landing pages.

## Internal conflicts ⚠
- **Same unscoped page tree we saw in the post and product modals** — likely shows seeded gibberish on a fresh install.
- No layout preset (cards / list / minimal links).
- No exclusion filter ("hide this page from the list").
- No empty-state when there are no pages matching the filter.

## Single highest-leverage fix
Add a filter chip row above the inserted Pages module: `By tag · By parent · By date · Manual`.

## Quick wins
1. Hide unpublished pages by default.
2. Show the filter result count in the editor.
3. Templated empty-state message ("No pages match yet.").

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 3 |
| Emotion | 2 |
| Usability | 2 |
| Coherence | 3 |
| Scalability | 3 |
| Distinctiveness | 2 |
**Total: 15 / 30**
## Inventory
- Embeds a Facebook Page's preview card with timeline / events / cover image.

## Working ✅
- One-line embed for "follow us on Facebook" sections.

## Internal conflicts ⚠
- Same privacy concerns as `Facebook Like`.
- **Two separate modules for the same vendor** — combine.
- **Likely deprecated by Meta**: the Page plugin's design and tracking model has changed several times; long-term reliability is uncertain.

## Single highest-leverage fix
Merge `Facebook Like` + `Facebook Page` into a single `Facebook` module with a `Like / Page / Post` mode toggle.

## Quick wins
1. Privacy-respecting deferred load.
2. Show fallback link when blocked.
3. Consider a generic `Social embed` module that handles Facebook, X, Instagram, LinkedIn.

## Scorecard
| Dim | Score |
|---|---:|
| Clarity | 2 |
| Emotion | 1 |
| Usability | 2 |
| Coherence | 1 |
| Scalability | 2 |
| Distinctiveness | 1 |
**Total: 9 / 30 — merge target.**
## UITEST — UI testing framework batch (ref: https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui)

### UI Component Testing

### Browser Compatibility

### Accessibility Validation

### Documentation

### UITEST findings — follow-up tasks

## MSET — Per-module Filament settings smoke coverage

> **Goal:** every module that ships `Modules/<X>/Filament/*ModuleSettings.php`
> has a `tests/Browser/LiveAdminModule<X>SmokeTest.php` Dusk test that
> exercises three signals through the live admin pipeline:
>
>   1. **Page OK** — `assertPageSmokeOk('/admin/<slug>-module-settings')`:
>      HTTP < 500, no Whoops / Internal Server Error / Symfony stack-trace
>      markers in the DOM, no SEVERE JS console entries.
>   2. **Save round-trip** — direct `save_module_option(...)` call against
>      a marker-prefixed `option_key`, then assert the row landed in the
>      `options` table with the expected `(option_key, option_value,
>      module)` tuple. The same code path the Livewire `updated()` hook
>      invokes server-side on every reactive field edit, so the smoke
>      proves that the page's persistence pipeline works end-to-end.
>   3. **Console clean** — `installInPageErrorGuard()` after settle,
>      1.5s pause, then `assertNoConsoleErrors()` to catch deferred-
>      script throws the SEVERE log read in (1) couldn't catch.
>
> The contract test
> `tests/Feature/LiveAdminModuleSmokeTestThreeAssertionsContractTest.php`
> already enforces all three signals on every `LiveAdminModule*SmokeTest`
> file. Use one of the existing smokes as the canonical template:
>
>   - **Settings-page modules with a single option** (most common):
>     copy `tests/Browser/LiveAdminModuleBtnSmokeTest.php`.
>   - **Settings-page modules with media-picker / data-source toggles**:
>     copy `tests/Browser/LiveAdminModulePicturesSmokeTest.php`.
>   - **Service/API/Storage modules without an admin Save button**:
>     copy `tests/Browser/LiveAdminModuleSeoSmokeTest.php`.
>
> Pre-conditions for each batch: dev server at 127.0.0.1:8000;
> admin admin@admin.com/admin (handled by `AdminLoginTrait`).
> Tear down marker-prefixed rows in `tearDown()`.

### MSET.1 — Modules with settings page but NO smoke yet (10)

> Highest-leverage batch — these modules have a Filament settings
> page operators can reach today, but no automated smoke verifying
> the admin pipeline works. Ship a smoke for each.

### MSET.2 — Modules with settings page AND smoke (39, already shipped)

> Inventory captured for completeness — every entry below has its
> `LiveAdminModule<X>SmokeTest.php` already shipped. No work to do
> in this batch unless a future settings-page change adds new
> options that warrant tightening the smoke's assertions.

### MSET.3 — Verifier-side guards (already in place)

## DOCS.0 — Foundations

## DOCS.1 — Tier 1 modules (full data + API docs)

For each module below, ship `Modules/<X>/docs/README.md` populated
from the template. Acceptance criteria per module:

  1. Lists every database table the module owns (table name,
     columns + types, FK relationships).
  2. Lists every Eloquent model class + its relationships.
  3. Lists every public API endpoint (path, method, auth, scope,
     controller method).
  4. Lists every service class + its public method contracts.
  5. Lists every event the module dispatches or listens for.
  6. Cross-links to MCP catalog entries (if any).
  7. Linted by the audit script in DOCS.0 above.

## DOCS.2 — Tier 2 modules (API-only docs)

For each module below, ship `Modules/<X>/docs/README.md` covering
only the API surface + service contracts (no data-model section
since these modules don't own non-trivial tables of their own).

## DOCS.3 — Tier 3 + Tier 4 (aggregate doc)

## DOCS.4 — Legacy doc salvage (from `microweber-docs`)

> Source surveyed: `/home/headless/Documents/GitHub/microweber-docs/`
> (SUMMARY.md table of contents). Each old section is classified
> below; **salvaged** items shipped 2026-04-25, **superseded** items
> already exist in the current docs, **obsolete** items describe
> pre-Filament UI / pre-Laravel helpers that have no current
> equivalent and were not salvaged.

## BSW — Bootswatch palette refinement batches

> The 25 Bootswatch palettes (`bootswatch-*.json`) shipped 2026-04-25
> map only the *core* color tokens. Each batch below picks one
> palette, browser-verifies it on a real Bootstrap-template site,
> and either accepts the mapping as-is or extends it with
> theme-specific tweaks (form-control colors, footer palette,
> swatch thumbnails, dark-mode badge contrast). Each batch is
> independent — pick any palette, ship in isolation.

> Acceptance criteria for each: (1) load the palette via the admin
> Style Editor on `?editmode=y`; (2) screenshot home + a content
> page in both modes; (3) confirm primary buttons / links / headings
> match Bootswatch's own demo at `https://bootswatch.com/<slug>/`;
> (4) record any extra MW vars that need adjusting in the JSON pack.

## MOBILE — Per-skin mobile rendering follow-up

> Coverage shipped 2026-04-27 under `task-2026-04-27-a9cfee`:
> bulk-added `img-fluid` to 96 skin files across Pictures, Content,
> Post, Product, Blog, Testimonials, Skills, Tag, Teamcard,
> LayoutContent, BeforeAfter, Slider, Audio, Video. Mobile-overflow
> regressions caused by intrinsic-pixel `<img>` tags are now fixed
> at the source.
>
> The batches below cover the remaining skin categories — none
> surfaced a visible mobile regression during the audit pass, but
> they're worth a per-skin browser pass when time permits to catch
> subtler issues (text overflow on narrow columns, button-row
> wrapping, table-as-grid layouts, embedded iframe overflow, etc.).

> Acceptance criteria for any future per-skin drill-down:
> (1) seed a fixture page with the skin in 3 column widths
> (col-12, col-md-6, col-md-3); (2) load at 390×844 mobile;
> (3) confirm `bodyScrollWidth ≤ viewport`; (4) confirm no element
> rect extends past the viewport's right edge.

## RTM.1 — Route migration foundations

## RTM.2 — Migrate the existing `$modules` loop to per-module providers

The 16 slugs in the loop are the easy part: each maps cleanly onto a
single controller in a single module. Extract one block per module
and land it in the module's own `routes/api.php`, registered via
`loadRoutesFrom()` in the module's service provider.

## RTM.3 — Migrate the action-route blocks

These don't fit the standard REST loop and have their own
hand-written routes:

## RTM.4 — Wind down the global file

## RTM.5 — Verification

## MTU.1 — Per-module MCP tool unit tests

For each of the 12 module keys the catalog declares, ship at least
one focused `Modules/<X>/Tests/Unit/Mcp/<X>ToolUnitTest.php` that
exercises the underlying tool's `__invoke()` directly (no HTTP, no
agent). Goal: catch a regression in a tool's text output / error
shape / argument validation in the module that owns it, not a
hundred lines down the McpToolCatalogContractTest stack.

## CLI.1 — Foundations

      ```bash
      php artisan microweber:ai "add a blog post about cats"
          [--agent=general]
          [--user=admin@admin.com]
          [--session=NN]
          [--json]
      ```

      Accepts the prompt as a positional argument so `php artisan
      microweber:ai "..."` is the canonical invocation. Options:

      - `--agent=NAME` — named agent type (defaults to `general`).
        Allow-list pulled from the AgentFactory's registered types
        so unknown names reject with a list of valid options.
      - `--user=EMAIL` — operator to run as (defaults to the first
        admin user; falls back to a `--user-id=N` form for CI use).
        The command resolves the user via `User::where('email', $email)`,
        seats them as the authenticated user via `Auth::login()`, and
        threads the resulting user-id through every write-tool's
        `user_id()` lookup so audit-log entries land under the
        correct actor.
      - `--session=NN` — optional `mcp_chats.id` to continue an
        existing chat. Without it, the command opens a fresh
        ephemeral session (or persists a new `AgentChat` row when
        `--persist-session` is added in CLI.3).
      - `--json` — emit the agent's reply (and any tool-call
        side-effects) as a JSON envelope on stdout instead of the
        default human-readable text. Useful for shell-pipelines.

## CLI.2 — Write-action coverage

These sub-tasks each surface one of the 6 catalog write tools as a
first-class CLI sub-command **once** the foundation lands. Each
sub-command is a thin adapter that pre-fills the agent's prompt
template so operators get a deterministic invocation contract
instead of having to remember free-text phrasing.

## CLI.3 — UX polish

## CLI.4 — Security & operations

## CLI.5 — Testing

## CLI.6 — Documentation

## A. MCP Spec Compliance Gaps (high priority — interop risk)

Each of these is a deviation from the [MCP spec](https://spec.modelcontextprotocol.io/) that
will cause real MCP clients (Claude Desktop, Cursor, Cline, Continue, etc.) to fail
in subtle / loud ways. The current server is a "JSON-RPC server with tools/* methods",
not a fully spec-compliant MCP server.

### A.1 Required protocol methods

### A.2 Capability negotiation

### A.3 Streamable HTTP / SSE transport

## B. Critical Bug — `allowed_tools = null` blocks every tool

Reproduced live on 2026-04-25:

  1. Created an MCP client with `allowed_tools = null`, `allowed_modules = null`,
     `allowed_scopes = ['mcp:access', 'mcp:admin']`.
  2. Issued a token, called `tools/list` — returned **0 tools**.
  3. Updated the client to `allowed_tools = ['*']`, `allowed_modules = ['*']`
     — `tools/list` returned all 39 tools.

Root cause: `McpClient::allowsValue()` (Modules/Ai/Models/McpClient.php:106-113)
treats both `null` AND `[]` AND `['*']`-aware as allow-list-empty; only `['*']`
or an explicit whitelist passes. Most operators reading the schema would assume
`null = unrestricted`.

      ['*']=wildcard, specific=least-privilege. The 60-test
      McpControllerTest suite stays green under the new semantics.)*

## C. Tool catalog — coverage + UX

### C.1 Missing high-value tools

### C.2 Schema robustness

### C.3 Tool output normalisation

## D. Security & operations

### D.1 Auth & rate limiting

### D.2 Audit log

### D.3 Observability

### D.4 Hardening

## E. Documentation

## F. CLI / DX

## G. Testing

      [MCP test suite](https://github.com/modelcontextprotocol/inspector)
      validations as PHPUnit assertions: every required JSON-RPC
      envelope shape, every required method, every error code.
      *(Already satisfied by the existing
      `Modules/Ai/tests/Feature/McpSpecComplianceTest.php` (14
      tests / 100 assertions): every required JSON-RPC envelope
      shape (initialize, ping, tools/list, tools/call,
      notifications/* batched + standalone), every spec-mandated
      error code (-32000 disabled, -32600 invalid request,
      -32601 method-not-found graceful-decline for the 7
      unsupported method families). The Inspector test suite's
      additional resources/* and prompts/* checks don't apply
      because the server omits those capabilities by design (see
      Plan C.1 resources / prompts decline). When the first write
      tool lands and resources/* go live, port the Inspector
      validations for those method families as a separate task.)*

## H. Future / nice-to-have

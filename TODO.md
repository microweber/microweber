# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Done

- [x] 2026-04-11  Make a todo to fix all mobile pages issues

## Mobile Pages Issues

> Audited at 390×844 viewport (iPhone 14 equivalent) on 2026-04-11.
> Screenshots saved under `screenshots/audit/mobile/`.

### Tables — column overflow / truncation

- [x] 2026-04-11  Products list: "PUBLISHED" badge text clipped on right edge — badge should wrap or abbreviate on mobile
- [x] 2026-04-11  Customers list: table columns overflow viewport ("LAST NA...", "Custo..." truncated) — hide low-priority columns (ID, Phone) on mobile or switch to card layout
- [x] 2026-04-11  Users list: "PHON..." column header truncated — same fix as customers, hide Phone column on small screens
- [x] 2026-04-11  Orders edit: Payments table headers overflow ("PAYMENT PROVIDER", "STATUS" clipped) — responsive table or hide columns on mobile

### Content list cards

- [x] 2026-04-11  Pages list: long page titles overflow card width — add `text-overflow: ellipsis` with `max-width` on title text
- [x] 2026-04-11  Categories tree: long category names overflow container — truncate with ellipsis

### Page headers / edit pages

- [x] 2026-04-11  Page edit: page title in header bar overflows left edge ("ategoryJsonTreeAdminPageStatic0_69c...") — truncate with ellipsis and max-width on the header title element
- [x] 2026-04-11  Page edit: header action buttons (trash, Live edit, SAVE) are cramped on mobile — stack vertically or use icon-only buttons on small screens
- [x] 2026-04-11  Products list: duplicate "New product" button appears (one in page header, one in floating toolbar) — hide one on mobile

### Dashboard

- [x] 2026-04-11  Dashboard chart: X-axis date labels slightly truncated on far left at 390px — increase left grid margin or reduce font size on mobile
- [x] 2026-04-11  Dashboard stat cards: "Last comments" and "Recent Orders" card labels may truncate on very narrow screens — use shorter labels on mobile or allow text wrapping

### Tabs

- [x] 2026-04-11  Order edit: third tab label ("P...") truncated — ensure tab labels are abbreviated or scrollable-visible on mobile
- [x] 2026-04-11  Product edit forms with many tabs may overflow — verify horizontal scroll indicator is visible

- [x] 2026-04-11  fix the thumbnail on the admin marketplace templates

- [x] 2026-04-11  ok all works, now we will work on the bootstrap template, make a detailed plan in the todo how to make it

---

## Bootstrap Template — Match Demo Design (https://demo.microweber.org/v2/)

> **Goal:** Update the local Bootstrap template (`Templates/Bootstrap/`) to match the polished demo site design.
> **Build command:** `cd Templates/Bootstrap && npm run build`
> **Key files:** `resources/views/`, `resources/assets/sass/app.scss`, `resources/assets/css/main.scss`

### Current vs Demo — Key Differences

The local template uses raw Bootstrap 5 example markup (generic jumbotron, "Columns with icons", "Centered hero" placeholder text). The demo has a professional, production-ready design with:
- Dark top header bar (social links, phone, "CONTACT US" button, search, user icon, cart)
- Clean right-aligned main navigation
- Full-width hero with background image and centered CTA
- Feature sections with icons and "Learn More" buttons
- Video section with embed
- Story/blog cards grid
- Testimonial carousel
- Professional 3-column footer (company info, phone/email/social, addresses)
- Consistent color scheme (dark header, orange/peach accent color, white body)

---

### Phase 1: Header & Navigation

- [x] 2026-04-11  Update `menus/skin-1.blade.php` — Add top header bar with social links (Facebook, Twitter, LinkedIn), phone number, "CONTACT US" button, search icon, user icon, cart icon
- [x] 2026-04-11  Update `menus/skin-1.blade.php` — Replace centered pill nav with right-aligned main navigation (Home, Blog, Shop, Contact us) matching demo layout
- [x] 2026-04-11  Add SCSS for `.templates-top-header-menu` — dark background, white text, flex layout with social left / actions right
- [x] 2026-04-11  Add SCSS for main nav — clean typography, right-aligned links, active state styling with underline/bold

### Phase 2: Hero / Jumbotron

- [x] 2026-04-11  Update `jumbotron/skin-1.blade.php` — Replace Bootstrap SVG logo + generic jumbotron with full-width hero section: background image, centered heading ("Describe your company"), subtitle, CTA button
- [x] 2026-04-11  Remove the two-column "Change the background" / "Add borders" sub-cards — replace with clean single hero layout
- [x] 2026-04-11  Add SCSS for hero — full-viewport-height option, overlay gradient for text readability, centered content

### Phase 3: Feature Sections

- [x] 2026-04-11  Update `features/skin-1.blade.php` — Replace raw SVG icons with proper icon components (using mw-micon or mdi icons), add "Learn More" buttons matching demo style
- [x] 2026-04-11  Add section title ("The Feature Title") with centered layout and clean typography
- [x] 2026-04-11  SCSS for feature cards — icon sizing, consistent spacing, button styling to match demo orange/accent color

### Phase 4: Content / CTA Sections

- [x] 2026-04-11  Update `content/skin-1.blade.php` — Create "Your Story Should Evolve Over Time" section with icon, heading, subtitle matching demo content/skin-2 layout
- [x] 2026-04-11  Add centered icon above section headings (matching demo anchor icon pattern)
- [x] 2026-04-11  SCSS for icon-size-64px utility class

### Phase 5: Blog Section

- [x] 2026-04-11  Update `blog/skin-1.blade.php` — Blog section already uses posts module with skin-1 slider; minor cleanup (duplicate attr, add module id)
- [x] 2026-04-11  Style blog cards — posts module skin-1 handles card styling (image, title, date, Read More); slick slider provides dots/pagination
- [x] 2026-04-11  Add pagination dot indicator below blog grid — already included via slick slider config in posts module

### Phase 6: Testimonial / Text Block

- [x] 2026-04-11  Update `text-block/skin-1.blade.php` — Create testimonial/quote section with author avatar, name, title, company, and quote text
- [x] 2026-04-11  Add "Pictures In The Sky" callout section with centered text (matching demo)
- [x] 2026-04-11  SCSS for testimonial — avatar circle, carousel dots, centered layout

### Phase 7: Footer

- [x] 2026-04-11  Update `footers/skin-1.blade.php` — Replace 4-column category menu layout with 3-column professional footer: Column 1 (Company name, description, page links), Column 2 (Phone, Email, Social icons), Column 3 (Address blocks — California, New York)
- [x] 2026-04-11  Keep copyright bar at bottom with "All Rights Reserved" and Microweber credit
- [x] 2026-04-11  SCSS for footer — light warm background (#f9f6f1 or similar), clean typography, orange accent on links/icons

### Phase 8: Shop Page

- [x] 2026-04-11  Update `shop.blade.php` — Add hero banner section with dark background, "Online Store Design Concept" heading and subtitle
- [x] 2026-04-11  Ensure product grid uses clean card layout with image, title, price
- [x] 2026-04-11  Add sidebar with category list (matching demo Category 1, Category 2 pattern)

### Phase 9: Blog & Post Pages

- [x] 2026-04-11  Update `blog.blade.php` — Add blog listing layout matching demo style
- [x] 2026-04-11  Update `post.blade.php` — Clean single-post typography and layout

### Phase 10: Global Styling & SCSS

- [x] 2026-04-11  Define CSS custom properties in `main.scss` or `design-vars.scss`: primary accent color (orange/peach ~#e8945a), heading color, body text color, footer background
- [x] 2026-04-11  Typography — set heading font family, body font, consistent font sizes and weights
- [x] 2026-04-11  Button styling — rounded buttons with accent color, hover states, outline variants
- [x] 2026-04-11  Section spacing — consistent vertical padding between sections (py-5 / 80-100px)
- [x] 2026-04-11  Link styling — accent color on hover, smooth transitions

### Phase 11: Mobile Responsiveness

- [x] 2026-04-11  Test all updated sections at 390×844 viewport
- [x] 2026-04-11  Top header bar — stack or simplify on mobile (hide phone text, icon-only buttons)
- [x] 2026-04-11  Navigation — hamburger menu on mobile
- [x] 2026-04-11  Hero section — responsive text sizing and padding
- [x] 2026-04-11  Feature grid — single column on mobile
- [x] 2026-04-11  Footer — stack columns vertically on mobile
- [x] 2026-04-11  Shop sidebar — collapse below products on mobile

### Phase 12: Build & Verify

- [x] 2026-04-11  Run `npm run build` in `Templates/Bootstrap/` to compile SCSS/JS
- [x] 2026-04-11  Verify all pages visually: Home, Blog, Shop, Contact, single post, single product
- [x] 2026-04-11  Take before/after screenshots for comparison
- [x] 2026-04-11  Commit all changes

- [x] 2026-04-11  work on the bootstrap template mobile

- [x] 2026-04-11  next work on all module settings, populate the todo with all modules and a plan for each module settings

---

## Module Settings — Bootstrap Template Integration

> **Goal:** Review and improve all module settings panels, template skins, and JSON schema configurations used by the Bootstrap template to ensure they render correctly, have proper defaults, and match the demo design.

### Module Categories

**Layout Modules** — modules used to structure page sections:
- Layouts, Background, Spacer

**Navigation Modules** — header/menu/breadcrumb:
- Menu, Logo, Breadcrumb

**Content Display Modules** — modules that show content:
- Post (blog posts), Pictures (galleries), Slider, Video, Embed

**Interactive Modules** — user interaction:
- Btn (buttons), ContactForm, Search, Sharer, Comments, Rating

**E-commerce Modules** — shop functionality:
- Cart, Product, Category, Checkout, Currency, Payment, Shipping, Coupons, Order

**Social & Communication Modules:**
- SocialLinks, Newsletter, Testimonials, Teamcard

**Utility Modules:**
- Tabs, Accordion, Elements, Faq, CookieNotice, GoogleMaps, Pagination, Captcha

---

### Phase 1: Core Layout & Navigation Module Settings

- [x] 2026-04-11  **Layouts module** — Verify background settings panel (image/video/color picker), spacer controls, and section padding options work correctly in live editor
- [x] 2026-04-11  **Menu module** — Test all menu template skins (navbar, simple, skin-1, linktree, images) render properly with Bootstrap styling; verify mobile hamburger settings
- [x] 2026-04-11  **Logo module** — Test default and 2rows templates; verify logo sizing and alignment settings
- [x] 2026-04-11  **Breadcrumb module** — Test skin-1 through skin-6; ensure consistent styling with Bootstrap theme
- [x] 2026-04-11  **Spacer module** — Verify height control works; check spacer renders correctly in sections

### Phase 2: Content Display Module Settings

- [x] 2026-04-11  **Post module** — Test key skins (default, skin-1, skin-10, blog-pro, post-slider); verify JSON schema settings (slides count, adaptive height) for carousel; check card styling consistency
- [x] 2026-04-11  **Pictures module** — Test gallery templates (masonry, simple, default, skin-10+); verify lightbox, grid columns, and caption settings
- [x] 2026-04-11  **Slider module** — Test default and swiper-skin-1; verify autoplay, navigation, transition settings
- [x] 2026-04-11  **Video module** — Test default, dialog, dialog-button templates; verify responsive embed sizing
- [x] 2026-04-12  **Embed module** — Verify iframe/HTML embed settings panel works

### Phase 3: Interactive Module Settings

- [x] 2026-04-12  **Btn module** — Verify bootstrap.json schema (button style, size, text, link, icon settings); test button preview in settings panel
- [x] 2026-04-12  **ContactForm module** — Test form templates (default, skin-1 through skin-6, CTA forms); verify field configuration, email settings, success message
- [x] 2026-04-12  **Search module** — Test autocomplete template; verify search type settings (all, shop, blog)
- [x] 2026-04-12  **Sharer module** — Test social sharing buttons; verify platform selection settings
- [x] 2026-04-12  **Comments module** — Verify comment display and moderation settings
- [x] 2026-04-12  **Rating module** — Test star rating display and settings

### Phase 4: E-commerce Module Settings

- [x] 2026-04-12  **Cart module** — Test templates (bootstrap, default, small_modal, shop_inner); verify cart quantity display, checkout link
- [x] 2026-04-12  **Product module** — Test product display skins; verify price, image, add-to-cart settings
- [x] 2026-04-12  **Category module** — Test category list templates (default, skin-1, horizontal-list, images); verify nesting and active state
- [x] 2026-04-12  **Checkout module** — Verify checkout flow settings and form fields
- [x] 2026-04-12  **Currency module** — Test currency selector display
- [x] 2026-04-12  **Payment module** — Verify payment provider settings panels

### Phase 5: Social & Communication Module Settings

- [x] 2026-04-12  **SocialLinks module** — Test all skins (default, skin-1, skin-2, skin-4, skin-7, footer); verify JSON schema for platform URL settings; check icon rendering
- [x] 2026-04-12  **Newsletter module** — Test subscription form templates; verify email integration settings
- [x] 2026-04-12  **Testimonials module** — Test key skins (default, skin-10, skin-12+); verify avatar, name, company, quote fields; check carousel settings
- [x] 2026-04-12  **Teamcard module** — Test team member display skins; verify photo, name, role, social link settings

### Phase 6: Utility Module Settings

- [x] 2026-04-12  **Tabs module** — Test tab templates (default, skin-1 through skin-5, horizontal centered); verify tab add/remove, content editing
- [x] 2026-04-12  **Accordion module** — Test accordion display; verify expand/collapse settings
- [x] 2026-04-12  **Elements module** — Test element types (icon, picture, text, title, inline-table, multiple-columns)
- [x] 2026-04-12  **Faq module** — Test FAQ display template; verify question/answer editing
- [x] 2026-04-12  **CookieNotice module** — Verify cookie consent banner settings (text, button, position)
- [x] 2026-04-12  **GoogleMaps module** — Test map embed; verify API key, zoom, marker settings
- [x] 2026-04-12  **Pagination module** — Test pagination styles (bootstrap3, bootstrap4, default, mw); verify per-page settings
- [x] 2026-04-12  **Captcha module** — Verify captcha integration settings

- [x] 2026-04-12  All module settings verified across 6 phases (40+ modules) — fixes applied to Tabs, Faq, Captcha, Product, Category modules

- [x] 2026-04-12  Run full PHPUnit test suite to verify no regressions from module fixes

- [x] 2026-04-12  Connect global search on all Filament resources — added recordTitleAttribute and getGloballySearchableAttributes to 10 resources

- [x] 2026-04-12  remove the translations from the global search

- [x] 2026-04-12  now in http://127.0.0.1:8000/admin/template-customization there is slow loading arrow when you change template

- [x] 2026-04-12  now work on http://127.0.0.1:8000/admin/file-manager-page-admin  see the seach and sort by boimnputs

- [x] 2026-04-12  some module like the accodion and other live edit module donesh ave admin pages http://127.0.0.1:800

- [x] 2026-04-12  now work on the bootrap template it has some double menus etc, isntall the default content

## Todo
- [x] 2026-04-12  the prduct inner pages are not loading pls fix
- [x] 2026-04-12  evaluetae the whole bootrap tempalte and fix make realsitic default content and

- [x] 2026-04-12  now prodcut are added witohu price, plsfix

- [x] 2026-04-12  test and fix all template pages and the content form

- [x] 2026-04-12  test and fix the checkout

- [x] 2026-04-12  also ifx the template seggins and see if  color schemems work on live edit

- [x] 2026-04-12  ok contine to work on the bootrap 5 template fxi all mobile issues make interl todo lsit and work
- [x] 2026-04-12  fux the checjout and th e full tempalteo n mobile

- [x] 2026-04-12  make a plan to valu e all module sins on descto and mobile and fix them , ad in the the todo.md the

---

- [x] 2026-04-13  now the top menu has some double botred bototm or idnerline, pls fix
## Module Skins Evaluation — Desktop & Mobile

> **Goal:** Test every module skin on the Bootstrap template at both desktop (1440×900) and mobile (390×844) viewports. Fix layout issues, broken styling, overflow, and Bootstrap 4→5 compatibility problems.
> **Method:** Load each module skin via live editor or direct page, screenshot, check for overflow, broken layout, and missing styles.
> **53 modules, ~280 skins total** — grouped by priority (user-facing impact).

### Priority 1: Layout & Page Structure Skins (used on every page)

- [x] 2026-04-12  **Layouts** — default, skin-1, content/skin-1; check section padding, background, overflow on mobile
- [x] 2026-04-12  **Menu** — default, navbar, simple, skin-1, small, linktree, images; verify dropdown submenus, mobile hamburger, BS5 data attributes
- [x] 2026-04-12  **Logo** — default, 2rows; sizing and alignment on mobile
- [x] 2026-04-12  **Breadcrumb** — default, skin-1 through skin-6; truncation on mobile with long page paths
- [x] 2026-04-12  **Footer** — skin-1, footer_cart; column stacking on mobile, link colors

### Priority 2: Content Display Skins (blog, shop, galleries)

- [x] 2026-04-12  **Post** — default, skin-1, skin-10, blog-pro, post-slider, skin-2 through skin-9; card grid on mobile, image aspect ratios, slider dots
- [x] 2026-04-12  **Product** — default, skin-1, skin-4 through skin-12; product card layout, price alignment, add-to-cart button on mobile
- [x] 2026-04-12  **Pictures** — default, masonry, simple, skin-1 through skin-20, slick, slider; lightbox, grid columns collapsing on mobile
- [x] 2026-04-12  **Content** — default, skin-1, sidebar, masonry, dictionary, search; content width on mobile
- [x] 2026-04-12  **Slider** — default, swiper-skin-1; touch swipe, navigation arrows on mobile
- [x] 2026-04-12  **Video** — default, dialog, dialog-button; responsive embed sizing

### Priority 3: Interactive Module Skins

- [x] 2026-04-12  **Btn** — bootstrap, default; button sizing on mobile
- [x] 2026-04-12  **ContactForm** — default, skin-1 through skin-6, CTA forms, subscribe-1 through subscribe-7; form field width on mobile, submit button
- [x] 2026-04-12  **Search** — default, autocomplete; search input width on mobile
- [x] 2026-04-12  **Comments** — default; comment thread indentation on mobile
- [x] 2026-04-12  **Rating** — default; star sizing
- [x] 2026-04-12  **Sharer** — default; social icon spacing on mobile

### Priority 4: E-commerce Skins

- [x] 2026-04-12  **Cart** — bootstrap, default, shop_inner, mw_default; cart table on mobile, quantity controls
- [x] 2026-04-12  **Category** — default, skin-1, horizontal-list-1, images; category list wrapping on mobile
- [x] 2026-04-12  **Shop** — default, skin-1; product grid and sidebar on mobile

### Priority 5: Social & Communication Skins

- [x] 2026-04-12  **SocialLinks** — default, skin-1, skin-2, skin-7, skin-9, footer; icon sizing and spacing on mobile
- [x] 2026-04-12  **Testimonials** — default, skin-1 through skin-23; carousel swiping, card overflow on mobile
- [x] 2026-04-12  **Teamcard** — default, skin-1 through skin-19, slider; card grid on mobile, image sizing
- [x] 2026-04-12  **Newsletter** — default, small; form width on mobile

### Priority 6: Utility Module Skins

- [x] 2026-04-12  **Accordion** — default, skin-1, skin-3, skin-4, misc-12; expand/collapse on mobile, text overflow
- [x] 2026-04-12  **Tabs** — default, skin-1 through skin-5, horizontal-centered-tabs; tab label truncation on mobile, scrollable tabs
- [x] 2026-04-12  **Faq** — default; question/answer width on mobile
- [x] 2026-04-12  **CookieNotice** — default; banner position and button on mobile
- [x] 2026-04-12  **GoogleMaps** — default; map responsive sizing
- [x] 2026-04-12  **Pagination** — bootstrap3, bootstrap4, default, mw; page number overflow on mobile
- [x] 2026-04-12  **Elements** — icon, picture, text, title, inline-table, multiple-columns; column layout on mobile
- [x] 2026-04-12  **Spacer** — default; height consistency
- [x] 2026-04-12  **Embed** — default; iframe responsive sizing
- [x] 2026-04-12  **Tag** — bootstrap, cloud, default; tag wrapping on mobile

### Priority 7: Remaining/Niche Modules

- [x] 2026-04-12  **Audio** — default
- [x] 2026-04-12  **BeforeAfter** — default
- [x] 2026-04-12  **Marquee** — default
- [x] 2026-04-12  **HighlightCode** — default
- [x] 2026-04-12  **Skills** — default
- [x] 2026-04-12  **Pdf** — default, iframe
- [x] 2026-04-12  **ImageRollover** — default
- [x] 2026-04-12  **Multilanguage** — default
- [x] 2026-04-12  **FacebookLike** — default
- [x] 2026-04-12  **FacebookPage** — default
- [x] 2026-04-12  **TweetEmbed** — default
- [x] 2026-04-12  **GoogleAnalytics** — default
- [x] 2026-04-12  **CustomFields** — bootstrap5 set (primary), bootstrap3/4 sets (legacy check)

- [x] 2026-04-12  Evaluate all modules skins and fix the broken

- [x] 2026-04-13  i still see double undelines, pls fix
[attachment: .autodev/messages/attachments/20260413_101222_80324823/paste-1776067504790.png]

- [x] 2026-04-13  fix the admin login, the logo must have somep adding and the sign in text must be centered

- [x] 2026-04-13  ok but now in the template, make the active navi tem undelined
[attachment: .autodev/messages/attachments/20260413_120032_0ef83e21/paste-1776074430256.png]

- [x] 2026-04-13  ok now style the chkout page , seems the cutom css is not loadng
[attachment: .autodev/messages/attachments/20260413_120342_7f308dff/paste-1776074619591.png]

- [x] 2026-04-13  the next buttpn in checkout has somal oagin state, seems the css is not usingg the microwbert heme

- [x] 2026-04-13  also if in the subsctions/billing module the css and the  theme

- [x] 2026-04-13  ok now in the checkout and all form fields seemsthere is some double baack abnd blue border on the focuse filed, pls reove the blue we want ony the black 

also checkn on dark mode, also the shoubly selelct driodown in the shipping on the checkout is not ok the X is falling down , pls fix

- [x] 2026-04-13  fic hre raio nbttuons when not active is not visible and whn e activei s red
[attachment: .autodev/messages/attachments/20260413_130249_f992c0f0/paste-1776078166371.png]

- [x] 2026-04-13  still radio button and checkboxes and not very fiiclbe mak them more visible , whey are too hard to

- [x] 2026-04-13  test the newsltter moduile make sure all is working
  - Dashboard, Campaigns, Lists, Designs, Subscribers, Senders pages all working
  - Create Campaign wizard working (Email To → From Email → Content → Schedule → Send)
  - Fixed: Flatpickr component crash on Edit Campaign — switched from `BobiMicroweber\FilamentFlatpickr` to `Coolsam\Flatpickr` (Filament 5 compatible)

- [x] 2026-04-13  on http://127.0.0.1:8000/admin/orders/180/edit the priduct drodown has diferent height htan the text
[attachment: .autodev/messages/attachments/20260413_134517_1d458312/paste-1776080708333.png]

- [x] 2026-04-13  now the drodown hegiht is ok but thel ine hegith is not correct text is a bit up, evaluete and fix a

- [x] 2026-04-13  now the xindex of thedropwnd is not ok see customer elenct rodown  also make the cusmer selecte drod
[attachment: .autodev/messages/attachments/20260413_135805_1f7d5c71/paste-1776081470802.png]

- [x] 2026-04-13  in the cateogies the checkbox is not alighned an therei s some school, pls mkae the conainet taller
[attachment: .autodev/messages/attachments/20260413_141508_4aa0e0ee/paste-1776082505383.png]

- [x] 2026-04-13  now work in then ewsletter module make appplan how to improve it and popoulate the todo.md

---

## Newsletter Module — Improvement Plan

> **Goal:** Fix bugs, improve UX, and polish the Newsletter module admin panel for Filament 5.
> **Module path:** `Modules/Newsletter/`
> **Admin panel:** `/admin/newsletter/` (panel ID: `admin-newsletter`)
> **Key pages:** Dashboard, Campaigns, Lists, Subscribers, Designs (Templates), Senders, Template Editor, Create/Edit Campaign

### Phase 1: Critical Bugs — Template Editor & Navigation

- [x] 2026-04-13  **Template Editor broken** — Navigating to `/admin/newsletter/template-editor/2118` shows frontend page instead of email template editor. The `TemplateEditor` page uses `filament-panels::components.layout.live-edit` layout and is registered via `FilamentRegistry::registerPage()` on the main admin panel, but the newsletter routes are under the `admin-newsletter` panel. The JS-based email editor (`email-editor.js`) never loads. Fix routing so template editor renders within the newsletter panel context.
- [x] 2026-04-13  **"Designs" sidebar link goes to frontend** — Verified: link correctly points to `/admin/newsletter/templates` and loads the admin Designs page. No fix needed.
- [x] 2026-04-13  **"E-mail Marketing" sidebar link** — Verified: intentionally points to `/admin/newsletter` (dashboard). No change needed.
- [x] 2026-04-13  **Template editor JS asset missing** — Verified: `public/modules/newsletter/js/email-editor.js` exists and loads correctly.

### Phase 2: Campaign Management UX

- [x] 2026-04-13  **Create Campaign form too basic** — Current create form has only Name, List, Email Content HTML (textarea), and Email Content Type. Should include: Subject line field, Sender Account selection, and option to choose a Design template instead of pasting raw HTML.
- [x] 2026-04-13  **Edit Campaign form missing fields** — The resource Edit form now inherits the improved fields (Subject, Sender Account). The primary Edit workflow uses the full wizard page (EditCampaign) which already has all fields.
- [x] 2026-04-13  **Campaign status badges** — Only "Finished" has a colored badge (green). Add colored badges for other statuses: Draft (gray), Sending (blue/animated), Scheduled (yellow), Failed (red), Canceled (gray strikethrough).
- [x] 2026-04-13 **Campaign "Edit" link missing for "Sending" status** — Added "View" action for all non-draft campaigns; also fixed Cancel button to handle 'sending' DB status.
- [x] 2026-04-13  **Campaign table — add Subject column** — Added searchable, toggleable Subject column after Name.
- [x] 2026-04-13  **Duplicate "Create Campaign" buttons** — Removed duplicate CreateAction from table header; page header "Create Campaign" button is the single entry point.

### Phase 3: Subscriber & List Management

- [x] 2026-04-13  **Subscribers table shows "test@example.com" for all** — Added status badge column (active/unsubscribed/bounced) and Created At column to subscribers table.
- [x] 2026-04-13  **Subscribers — add status column** — Added color-coded badge column with sortable and toggleable options.
- [x] 2026-04-13  **Subscribers — add "Created At" column** — Added date column, hidden by default, toggleable.
- [x] 2026-04-13  **Lists table — 572 lists with lorem names** — Added description column (toggleable), Created date column, subscriber count sorting, and "With subscribers" filter.
- [x] 2026-04-13  **Lists — duplicate "New Lists" buttons** — Removed duplicate CreateAction from table header; page header "New List" button remains.

### Phase 4: Designs (Templates) Page

- [x] 2026-04-13  **Templates table too sparse** — Added campaigns usage count, Last Modified column, searchable/sortable title, Created hidden by default.
- [x] 2026-04-13  **"New design" button** — Verified: works correctly, creates template from selected starter and redirects to editor.
- [x] 2026-04-13  **Template preview** — Added preview action (eye icon) that shows rendered email HTML in a modal.
- [x] 2026-04-13  **Duplicate "New design" buttons** — No duplicate found; only the page header button exists.

### Phase 5: Senders Configuration

- [x] 2026-04-13  **Sender type column shows icon only** — Changed from IconColumn to TextColumn with icon + text label (PHP Mail, SMTP, Mailchimp, etc.).
- [x] 2026-04-13  **Sender — add "Active" status column** — Added boolean icon column for is_active field with sorting.
- [x] 2026-04-13  **Sender — test connection button** — Added "Test" action that sends a test email to admin's address using the sender's configuration, with success/failure notifications.
- [x] 2026-04-13  **"New Senders" button label** — Fixed to "New Sender" (singular).

### Phase 6: Dashboard Improvements

- [x] 2026-04-13  **Dashboard stats inconsistency** — Verified: stats pull from real tracking tables (NewsletterCampaignPixel: 300, NewsletterCampaignClickedLink: 200, SendLog: 199). Values are from seeded test data, not hardcoded. No code fix needed.
- [x] 2026-04-13  **Dashboard — add recent campaigns widget** — Added RecentCampaignsWidget showing last 5 campaigns with status badges, opened/clicked counts, and creation date.
- [x] 2026-04-13  **Dashboard — add quick actions** — Added header action buttons: Create Campaign, Import Subscribers, New Template.

### Phase 7: Navigation & Information Architecture

- [x] 2026-04-13  **Sidebar navigation grouping** — Reorganized: Dashboard (ungrouped), Campaigns (Campaigns, Lists, Automation Workflows), Subscribers (Subscribers), Templates (Designs), Settings (Senders), Back to admin (ungrouped, bottom). Removed redundant "E-mail Marketing" link.
- [x] 2026-04-13  **"Back to admin" link** — Replaced NavigationItem with render hook at SIDEBAR_NAV_END; styled with separator line above and hover effect.
- [x] 2026-04-13  **Breadcrumbs** — Edit Campaign, template editor, create flow, and dashboard now show consistent newsletter breadcrumbs; covered by Filament regression tests.

### Phase 8: Mobile Responsiveness

- [x] 2026-04-13  **Campaigns table on mobile** — Added a mobile summary column and hid Subject/List metrics, Subscribers, Opened, Clicked, and Status log at small breakpoints; verified at 390px.
- [x] 2026-04-13  **Subscribers table on mobile** — Replaced the overflow-prone mobile table with a Subscriber summary column and hid Email/Lists on small screens; verified at 390px.
- [x] 2026-04-13  **Dashboard stats cards on mobile** — Changed newsletter stats widgets to stack one card per row on small screens; verified on the mobile dashboard.
- [x] 2026-04-13  **Template editor on mobile** — Added a mobile-only desktop recommendation notice and verified it renders with breadcrumbs at 390px.

### Phase 9: Automation & Workflows

- [x] 2026-04-13  **Workflow builder page** — Registered the workflow resource in the newsletter panel, embedded a working server-rendered builder into the edit page, and added regression coverage for add/connect/update/delete node flows.
- [x] 2026-04-13  **Triggered campaigns** — Test creating a triggered campaign (e.g., cart_abandoned) and verify the automation queue processes correctly.
- [x] 2026-04-13  **Automation navigation** — Registered `WorkflowResource` in the newsletter Filament panel so workflow routes and sidebar navigation appear under the Campaigns group.

### Phase 10: Data Quality & Testing

- [x] 2026-04-13  **Clean up test/factory data** — The 572 lists with lorem ipsum names and 12 campaigns with random Latin text make it hard to evaluate the UI. Consider adding a "seed demo data" command that creates realistic sample data (3-5 lists, 5-10 campaigns with real-looking names).
- [x] 2026-04-13  **Campaign send test** — Added regression coverage for the create → configure → send-now → process → send-log flow and verified it with targeted newsletter tests.
- [x] 2026-04-13  **Unsubscribe page** — Test the `/unsubscribe` endpoint renders properly and actually unsubscribes the user.

### Phase 11: MCP Server & API Key Auth via `pass`

> **Goal:** Add a secure MCP server for Microweber AI/admin operations, authenticated with scoped API keys and backed by the Unix `pass` password store for provider secrets and server credentials.

- [x] 2026-04-13  **Plan MCP follow-up work** — Newsletter work is complete; next phase is planned below so MCP/auth implementation can start in a focused pass.
- [x] 2026-04-13  **Choose module boundary and protocol surface** — Landed the first MCP surface inside `Modules/Ai` with a POST JSON-RPC endpoint at `/api/mcp`, using `initialize` and `tools/list` as the initial protocol methods and reusing the existing AI module/service boundary.
- [x] 2026-04-13  **Define the authentication model** — MCP now uses `auth:sanctum` bearer tokens with an `mcp:access` ability and admin-user enforcement; docs note that Passport/OpenAPI hooks remain for OAuth/OpenAPI compatibility, not MCP transport auth.
- [x] **Add a `pass` secret storage adapter** — Added a process-backed `PassSecretStore` with environment-aware references like `pass://microweber/{env}/ai/{provider}`, wired AI settings to save only secret references, and taught `AiServiceProvider` to resolve or migrate legacy plaintext provider secrets at runtime.
- [x] 2026-04-13  **Model MCP clients and scopes** — Added `mcp_clients`, `mcp_client_tokens`, and `mcp_client_token_events` tables plus AI models/factories and an `McpClientTokenManager` that issues one-time plain tokens, hashes them at rest, supports rotation/revocation, tracks allowed scopes/tools/modules, updates last-used metadata, and writes audit events.
- [x] 2026-04-13  **Implement middleware and request verification** — Replaced Sanctum route auth with an `mcp.client` middleware that parses MCP bearer tokens, verifies hashed token records, enforces required scopes plus tool/module/admin-only gating, applies per-client rate limits, updates last-used metadata, and writes structured MCP audit events.
- [x] 2026-04-13  **Map Microweber tools into MCP capabilities** — Added an MCP tool catalog in `Modules/Ai`, exposed `tools/list` and `tools/call`, mapped the first read-only tools (`content.lookup`, `content.get`, `product.lookup`, `order.lookup`, `settings.read`), normalized HTML tool output into MCP text content, and covered the flow with MCP feature tests.
- [x] 2026-04-13  **Build admin management UI** — Added a Filament `McpClientResource` in `Modules/Ai` with create/edit/view flows, scoped client policy fields, issue/revoke key management via relation managers and page actions, MCP health-test actions, and read-only `pass` reference status summaries that never reveal secret values.
- [x] 2026-04-13  **Add deployment/bootstrap support** — Documented the MCP/pass rollout in `Modules/Ai/README.md`, including server prerequisites (`gnupg`, `pass`), key import/init flow, local/dev bootstrap commands, `AI_SECRET_STORE_*` examples, non-interactive operating guidance, reference-path conventions, and current fallback behavior when `pass` is disabled or unavailable.
- [x] 2026-04-13  **Document the MCP contract** — Added an `MCP contract` section to `Modules/Ai/README.md` covering JSON-RPC request/response envelopes, supported tool schemas, auth header and token format, rotation/revocation flow, middleware vs JSON-RPC error semantics, and relevant `pass://...` examples; linked `Modules/OpenApi/README.md` to that section as the future baseline for OpenAPI exposure.
- [x] 2026-04-13  **Test end-to-end before rollout** — Added a rollout-oriented MCP feature test that boots pass-backed AI config, issues a scoped MCP token, and exercises `initialize` → `tools/list` → `tools/call` in sequence; reran the focused MCP suite (`McpControllerTest`, `McpClientTokenManagerTest`, `PassSecretStoreTest`, `AiServiceProviderSecretStoreTest`, `McpClientResourceTest`) and verified 29 tests pass.

- [x] 2026-04-13  **Integrate MCP in each module separately, make a plan and populate todo** — Expanded the Phase 11 backlog below into a concrete module-by-module MCP rollout plan with priorities, first read-only tool surfaces, and domain/scope expectations.

#### MCP module rollout backlog

**Priority now — extend MCP with high-signal read-only admin/reporting tools first**

- [x] 2026-04-13  **Newsletter MCP domain** (`Modules/Newsletter`) — Added read-only `newsletter.campaign_lookup`, `newsletter.subscriber_lookup`, `newsletter.template_lookup`, and `newsletter.automation_status` tools in `Modules/Ai`, wired them into the MCP catalog under the `newsletter` module key, masked subscriber emails / sanitized automation errors, and covered the flow with focused MCP feature tests.
- [x] 2026-04-13  **Site stats / analytics MCP domain** (`Modules/SiteStats`) — Added read-only `analytics.traffic_summary`, `analytics.top_pages`, `analytics.traffic_referrers`, and `analytics.audience_breakdown` tools in `Modules/Ai`, wired them into the MCP catalog under the `analytics` module key, kept referrer output aggregated by domain/path, and covered the flow with focused MCP feature tests.
- [x] 2026-04-13  **Forms / contact submissions MCP domain** (`Modules/ContactForm` and `Modules/Form`) — Added read-only `forms.form_lookup`, `forms.submission_search`, `forms.submission_detail`, and `forms.activity_summary` tools in `Modules/Ai`, normalized both legacy and new submission storage formats, masked submission PII / file paths, and covered the flow with focused MCP feature tests.

**Priority next — add commerce/billing reporting once the read-only pattern is stable**

- [x] 2026-04-13  **Billing subscriptions MCP domain** (`Modules/Billing`) — Add read-only `billing.subscription.*` and `billing.metrics.*` tools for subscription lookup, plan summaries, MRR/churn snapshots, and customer billing status, with admin-only scope requirements.
- [x] 2026-04-13  **Invoice MCP domain** (`Modules/Invoice`) — Add read-only `billing.invoice.*` tools for invoice search/detail, unpaid/overdue summaries, and customer invoice history.
- [x] 2026-04-13  **Payment MCP domain** (`Modules/Payment`) — Added read-only `billing.payment_lookup`, `billing.payment_detail`, `billing.payment_provider_health`, and `billing.payment_webhook_health` tools in `Modules/Ai`, kept `payment_data` / provider settings / webhook payloads out of MCP output, sanitized secret-like and card-number-like error strings, and covered the flow with focused MCP feature tests.

**Priority later — broaden operational context around shop/design/media once the above surfaces are proven**

- [x] 2026-04-13  **Shipping and tax MCP tools** (`Modules/Shipping`, `Modules/Tax`) — Added read-only `shipping.method_lookup`, `shipping.zone_summary`, `tax.rule_lookup`, and `tax.preview` tools in `Modules/Ai`, summarized provider/zone configuration without exposing raw settings blobs, included both modern and legacy tax rule reporting, and covered the flow with focused MCP feature tests.
- [x] 2026-04-13  **Media / file manager MCP domain** (`Modules/Media`, `Modules/FileManager`, `Modules/MediaLibrary`) — Added read-only `media.lookup`, `media.asset_detail`, and `media.storage_health` tools in `Modules/Ai`, summarized asset paths/folders/relations without exposing raw filesystem or CDN URLs, and covered the flow with focused MCP feature tests.
- [x] 2026-04-13  **Layouts / design MCP domain** (`Modules/Layouts`, template packages) — Added read-only `layouts.layout_lookup`, `layouts.active_template`, and `layouts.asset_summary` tools in `Modules/Ai`, summarized installed template layouts and style references with relative paths only, and covered the flow with focused MCP feature tests.

**Cross-cutting rollout rules for every new module/domain**

- [x] 2026-04-13  **Per-domain scope and module gating** — Completed across the MCP rollout: each domain now has an explicit module key in `McpToolCatalog`, tool/module client allow-lists, domain permissions in the tool classes, and optional admin-only enforcement via `modules.ai.mcp.auth.admin_only_tools` / `admin_only_modules`.
- [x] 2026-04-13  **Read-only first policy** — Completed across the first MCP rollout: newsletter, analytics, forms, billing, invoices, payments, shipping, tax, media, and layouts all ship as read-only lookup/summary surfaces with result caps plus masking/redaction for secrets, PII, raw payloads, filesystem paths, and destructive actions.
- [x] 2026-04-13  **Per-module contract/test/docs updates** — Completed across the module rollout: `Modules/Ai/README.md` now documents each module contract and safety boundary, `Modules/Ai/tests/Feature/McpControllerTest.php` covers `tools/list` and focused `tools/call` flows per module, and the Phase 11 backlog entries were updated as each slice landed.

- [x] 2026-04-14 **Live edit sidebar rail bar** — Stopped the generic `mw-admin-action-links` underline from rendering inside `mw-liveedit-button-animation-component` rail/action buttons, and styled that variant as a compact button so the stray horizontal bar no longer appears in the live edit sidebar.
[attachment: .autodev/messages/attachments/20260414_094348_8db59529/paste-1776152607827.png]

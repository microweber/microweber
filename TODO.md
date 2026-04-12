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

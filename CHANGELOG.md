# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).


## [Unreleased]

### Fixed
- **Live-edit Add Content modal — UX polish round** (task-2026-05-04-f575c7) — UX-engineer audit identified four hits: (1) modal heading was rendering behind the 60px live-edit toolbar (the heading center sat at y=52, toolbar bottom = 60), hiding the title and intercepting clicks on the drag-by-header handle. Pinned the modal to `top: calc(var(--toolbar-height, 60px) + 0.75rem)` so the heading is always clear of the toolbar; (2) the 100+-item parent-page tree was dumped under every form despite the parent being auto-resolved 95% of the time — collapsed `parentPageSection` by default in `formArrayCompact()` for both ContentResource and CategoryResource; (3) browser-native "Please fill out this field" tooltip used to anchor itself to the wrong field on submit — swapped Title's `->required()` for `->rules(['required'])->markAsRequired()` so Filament's own inline validation renders below the Title field instead; (4) the success toast offered "View Post" but the canvas had already navigated there — replaced with "Edit details" → full admin edit form in a new tab, useful next step for power users who want to refine SEO / Tags / Menus / Custom Fields.

### Added
- **Live-edit Add Content modal: "Open in admin" escape hatch + mobile-responsive layout** (task-2026-05-04-76275d) — every live-edit content modal (toolbar +ADD create, per-module Items-list Create, per-module Items-list Edit) now ships an "Open in admin" button (with external-link icon, `target=_blank`) in the modal footer alongside Save/Cancel. Power users who need SEO / Custom Fields / Tags / Menus / Variants click it to open the full admin form in a new tab without losing their place in live-edit. The URL carries the current canvas page forward as `?parent_page_id=` so the admin form pre-selects the same parent. Mobile-responsive: below 768px the `.fi-width-5xl` 1024px width is overridden and the modal pins to viewport edges with 12px gutters — usable on phones.

### Changed
- **Live-edit Category modal uses a compact form schema, Permalink dropped, dark-mode polish** (task-2026-05-04-e0fe54) — added `CategoryResource::formArrayCompact()` mirroring the Content compact variant (no tabs, just Title + Description + Parent picker) and wired `AdminLiveEditPage::generateAction()` to use it for `contentType == 'category'`. The Permalink (URL slug) subsection was dropped from `ContentResource::formArrayCompact()`'s general-information block — customers almost never set the slug manually during inline create, the auto-generated value is fine. Dark-mode chrome polish for `.mw-content-form-modal`: tighter header padding with explicit bottom border, section cards lift slightly off the body in dark mode for card-on-dark separation, tighter section padding + form-field gap, brighter heading colors. Full admin form schemas (`ContentResource::formArray`, `CategoryResource::formArray`) untouched — power users still get the full feature set at /admin/content/{id}/edit and /admin/categories/{id}/edit.
- **Live-edit Add Content modal uses a compact form schema** (task-2026-05-04-1d68c7) — added `ContentResource::formArrayCompact()` and wired it to the live-edit toolbar +ADD (`AdminLiveEditPage::generateAction`) and the per-module Items-list Create/Edit (`ContentTableList::editFormArray`). Cuts the form from 6 tabs + 14+ sections (~25+ fields) down to 4 sections + 6 visible labels — Title, Content body, Excerpt, Permalink (collapsed), Published, Parent page. Power users can still open the full form via the regular admin Filament resource at /admin/content/{id}/edit; the live-edit modal is now optimised for "create now, refine later" inline workflow.

### Fixed
- **Live-edit Add Content modal scroll + footer bleed** (task-2026-05-04-b7eee8) — the Add Post / Page / Product / Category form is ~1640px tall on a typical 900px viewport, so the modal used to grow past the fold, hide the SAVE/Cancel footer, and let later sections (Media, parent-page tree) bleed visually behind the would-be footer. Switched `.mw-content-form-modal` to a flex column with `.fi-modal-content` as the lone scrollable child, and pinned the modal to `position: fixed; top: 1.5rem; max-height: calc(100vh - 3rem)`. Header + scrollable body + footer all fit in the viewport with breathing room above and below; users scroll inside the modal, footer always visible.

### Changed
- **Per-module Posts / Pages / Products Create/Edit modals widened** (task-2026-05-04-61e974, task-2026-05-04-6f1549) — `ContentTableList::headerActions().CreateAction` and `actions().EditAction` `modalWidth(MaxWidth::ThreeExtraLarge)` (768px) → `MaxWidth::FiveExtraLarge` (1024px) to match the +ADD toolbar's modal width. Closes the user's "still uses the old modal" complaint by giving the per-module entry points the same wide layout as the toolbar entry point. Same scroll + flex layout as task-b7eee8 mirrored into `live-edit-module-settings.blade.php` so the iframe-rendered per-module modals also fit their viewport.

### Added
- **Live-edit Add Content modal is now draggable** (task-2026-05-04-c124bc) — the user can grab the modal's header and drag the modal anywhere on the screen so the live-edit canvas behind it is visible — same UX Microweber v2's `mw.dialog` shipped, applied directly to Filament's existing `.fi-modal-window.mw-content-form-modal` via a small native pointer handler in `iframe-page.blade.php`. Avoids porting the full Filament modal pipeline, so the form's Livewire wire:click / wire:model bindings stay intact. Cursor flips to `move` on the header and `grabbing` while dragging; modal pins to `position: fixed` at mousedown so subsequent updates work in viewport space. Y-axis containment is asymmetric (header must stay visible at the bottom, but tall forms can be pushed above the viewport so the user can reach the bottom of the form).

### Fixed
- **Live-edit Add Post — JS errors after save** (task-2026-05-04-3337c0) — `element-style-editor-main.blade.php` ran `mw.require()` / `mw.top()` inline during HTML parse, but the bundle that defines them (`admin.js`) is `type="module"` (deferred), so the inline code raced ahead and threw `mw.require is not a function` + `mw.top is not a function` + a cascading `Cannot read properties of undefined (reading 'on')` from the Vue mounted hook. Wrapped the inline scripts in a wait-for-mw IIFE (poll up to 10s, same pattern as `render-css-editor.blade.php`'s `waitForCodeMirror`) and deferred the Vue bundle injection until inside `init()` so the bundle's own `mounted()` hook only runs after `mw.top().app` is ready. Add Post → SAVE now reports zero console errors end-to-end.

### Changed
- **Live-edit Add Page / Post / Product / Category modal width** (task-2026-05-04-3337c0) — bumped from `MaxWidth::ThreeExtraLarge` (768px) to `MaxWidth::FiveExtraLarge` (1024px). At 768px the tabs + two-column form + rich-text editor still felt cramped on standard 1080p+ desktop viewports; 1024px gives the editor proper breathing room while still leaving the live-edit canvas visible behind the backdrop tint. `LiveEditAddContentModalIsCenteredTest` and `LiveEditTopModalAndSelectiveReloadTest` updated to assert `fi-width-5xl` instead of `fi-width-3xl` so the regression guard tracks the new tier.


## [4.0-dev17] - 2026-04-03

### Added
- **Media Library** — Full admin UI with 3-panel layout (folder sidebar, thumbnail grid/list, detail panel), drag-and-drop upload, bulk actions (delete, move, CDN sync), search/filters (type, date range, folder), Unsplash stock photo integration, and 26 Livewire component tests
- **Menu Management** — Redesigned drag-and-drop menu editor with nested items, item type icons (page/category/custom URL), rename/delete actions, and cascade-delete for child items
- **Product Variants** — Variant management UI with attribute selection, combination generation, and per-variant pricing/inventory/shipping fields
- **Order Enhancements** — Status-change timeline, shipping tracking fields, bulk status update, and refund processing UI (partial/full) with 3 new migrations
- **Users Resource** — Enabled create/edit routes with role assignment form
- **Content Management** — Bulk publish/unpublish actions, post excerpt field, publish/schedule date picker, author column and filter
- **Product Inventory** — Low-stock threshold field, stock status badges (In Stock/Low Stock/Out of Stock) in list view
- **Login Page** — Styled to match MW v2 visual design with branding, layout, and dark mode support
- **Dashboard** — MW v2-style welcome widget, quick stats cards, ECharts area chart with period switching (Daily/Weekly/Monthly)
- **Accessibility** — Skip-navigation link on all admin pages
- **Documentation** — SCOPE.md, PLAN.md, and Media Library feature spec

### Changed
- Migrated dashboard chart from Chart.js to ECharts with gradient fill and smooth line
- Sidebar widened to 16rem with improved group headers and text wrapping
- Shipping dimension fields renamed to match backend drivers (shipping_weight, shipping_width, shipping_height, shipping_depth)
- Settings.php refactored: extracted `extractItemData()`, fixed typo, added array guard
- SiteStatsRepository: fixed ambiguous column in JOIN query, corrected JOIN condition

### Fixed
- Alpine.js component loading error (`mwTreeFormComponent is not defined`) on create/edit forms
- Duplicate `HEAD_START` render hook causing "Identifier 'Ziggy' has already been declared" JS error
- `DateMalformedStringException` when saving content with invalid date strings (PHP 8.4)
- Settings hub page missing title prefix
- Mobile topbar avatar clipping and orders table overflow
- ECharts widget period switching (was non-functional due to `wire:ignore` blocking DOM updates)
- Dashboard stats: deduplicated order queries, added 120s cache, fixed currency symbol

### Security
- Pinned ECharts CDN to v5.5.1 with SRI integrity hash
- Resolved lodash.set prototype pollution CVE

### Performance
- Dashboard quick stats: combined 4 queries into 1 with 120s cache
- SiteStatsEchartsWidget: memoized chart data, cached online count with 60s TTL

## [2.0.16] - 2024-06-26
- Maintenance release
- Fixes on image upload, now rotates the image based on the EXIF data
- XSS fixes in save user and on search 
- Fixes on video module
- Fixes on text type module
- Fixes on marquee module
- Fixes on slider module
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.15...2.0.16 "")



## [2.0.15] - 2024-05-15
- Maintenance release
- Fixes on the Undo and Redo in the live edit
- Fixes editing text in tables in the live edit
- Fixes on the button module url picker
- Fixes on migrations from old versions
- Fixes editing order in admin panel
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.14...2.0.15 "")


 
## [2.0.14] - 2024-05-07
- Maintenance release
- Fixes on the Multi language module
- Fixes on the backup module
- Fixes the contact form module
- Added image alt and title to the image module
- More work done on the free draggable elements
- Icons are now resizable
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.13...2.0.14 "")



## [2.0.13] - 2024-04-02
- Maintenance release
- Fixes on the backup module
- Fixes on the contact form module
- Fixes on the menu module in multilanguage mode
- Fixes on button link picker in multilanguage mode
- Fixes on google analytics module
- Fixes on social links module
- Various fixes on the live edit
- Added marque module
- Added text typing effect module
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.12...2.0.13 "")



## [2.0.12] - 2024-02-19
- Maintenance release
- Fix display of text editor on small screen
- Fix problem with the update function not saving the new version number in the database
- Fix custom fields not updating the list of fields after save
- Fix problem with livewire modules needing initialization after module reload 
- Fix modal window not closing if you are focused on color picker field 
- Fix CRTL+S not working in the text editor if you are with caps lock on or on non-latin keyboard
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.11...2.0.12 "")


## [2.0.11] - 2024-02-16
- Maintenance release
- Fix scroll to element if click on link that has inner elements
- Fix multilanguage dropdown menu was going out of the screen
- Fix Use picture from posts setting was not refreshing the image galeery
- Added trust proxy settings in the admin panel
- Added element style editor button in the live edit toolbar
- Link picker modal is now draggable
- Other issues
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.10...2.0.11 "")


## [2.0.10] - 2024-02-06
- Maintenance release
- Fix Cant edit text in some heading tags
- Fix cant save image as background when using svg
- Fix Must be able to delete email messages
- Fix Live edit menu is missing on small screen
- Fix Layout background image is not deleted when 
- Fix Element style editor was not opening 
- Fix text typing in the editor 
- Fix the background image module when you have picture and color as background
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.9...2.0.10 "")


## [2.0.9] - 2024-01-23
- Maintenance release
- Fixed missing database field in the newsletter module
- Fixed issue on Enter key in live edit
- Fixed on testimonials module not showing the image
- Fixed accordion module not showing the icon
- Fixes on multiple columns module not showing the drag handles
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.8...2.0.9 "")


## [2.0.8] - 2024-01-17
- Maintenance release
- Fixes in live edit
- Fixes on wysiwyg editor in admin
- Multilanguage fixes
- Fixes on module presets
- Fixes on custom fields 
- Fixes on the contact form module
- Fixed on modal windows not aways closing
- Fixed on shop module showing deleted products
- Fixed logo module size slider
- Improved the newsletter module
- Other miscellaneous fixes
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.7...2.0.8 "")


## [2.0.7] - 2023-12-15
- Maintenance release
- Text Editor can now be pinned to the top
- Fixed some problems with live editing
- Fixed undo and redo issues when changing CSS properties
- Fixed Testimonials module
- Fixed editing borders in the style editor
- Fixed Accordion module
- Fixed an issue when saving a Multi language page, but changing the language in another tab
- Further improvements to the newsletter module
- XSS fixes
- Other miscellaneous fixes
- [See all changes...](https://github.com/microweber/microweber/compare/2.0.6...2.0.7 "")


## [2.0.6] - 2023-12-08
- Maintenance release
- Added new price modifier custom field setting for products
- Added newsletter module
- New template editing framework only with css variables 
- Fixed problem with resize images
- Fixed problem when editing text in field without html elements
- Fixed element style editor to work any element that have ID
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.5...2.0.6 "")



## [2.0.5] - 2023-11-17
- Maintenance release
- Fixed issues with cloneable controls
- Fixed issues with elements style editor when you change element 
- Fixed issues with font manager
- Fixed issues with module item editor 
- Fixed categories images module
- Fixed handles for clonable element not being shown
- Fixed some issues with categories module
- Fixed some issues with modal windows for module settings
- Fixed buttons in add content modal window not being sticky
- Fixed confirm dialog to work with escape key and enter key 
- Fixed security issue with svg upload
- Added options to loop and must video for video module
- Added option for site stats module to send server side evens to google analytics
- Added newsletter module
- Added Laravel events for some cart and checkout actions
- Added box shadow and text shadow settings in element style editor
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.4...2.0.5 "")
`

## [2.0.4] - 2023-11-09
- Fixed problem with menu links  
- Fixed problem when you edit module settings and close the modal while saving
- Fixed accordeon module can't be opened in live edit
- Fixed audio module
- Fixed popup module
 - [see all changes....](https://github.com/microweber/microweber/compare/2.0.3...2.0.4 "")


## [2.0.3] - 2023-11-02
- Small maintenance release
- Fixed problem with adding items to menu in admin panel
- Modules are now inserted on bottom of other modules instead of top
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.2...2.0.3 "")


## [2.0.2] - 2023-10-31
- Small maintenance release
- Fixed problem with image resizing
- Fixed problem with editing testimonials module
- Fixed problem with user image upload
- Fixed problem with reordering testimonials
- Fixed problem with removing format from text 
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.1...2.0.2 "")



## [2.0.1] - 2023-10-27
- Small maintenance release
- Fixed drop problems in columns
- Fixed drop problems when you mouse over other modules
- [see all changes....](https://github.com/microweber/microweber/compare/2.0.0...2.0.1 "")



## [2.0.0] - 2023-10-24
- Big milestone release
- Overhaul of the admin panel and live edit
- New live edit experience
- New admin panel design
- New live edit element style editor
- Refactored modules on Livewire components
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.4...2.0.0 "")



## [1.3.4] - 2023-03-24
- Added artisan command for update `php artisan microweber:update`
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.3...1.3.4 "")


## [1.3.3] - 2023-03-13
- Added bindings for Filament admin panel
- Added driver interface for payment modules
- Added support fo 2FA for admin panel
- Fixed loading order of service providers for modules
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.2...1.3.3 "")


## [1.3.2] - 2023-01-27
- Big maintenance release
- Improvements in live edit mode
- Refactored the admin content list
- Refactored the admin orders list
- Refactored file browser
- Fixed various bugs
- Added Import and Export module to import content
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.1...1.3.2 "")


## [1.3.1] - 2022-08-11
- Maintenance release 
- Fix to add missing config files on update
- Fixed XSS in the category name
- Menu url field is now translatable
- Added helper to compile blade from string
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.3.0...1.3.1 "")


## [1.3.0] - 2022-07-20
- Upgraded to Laravel 9 and PHP 8.1
- Added Livewire support for the admin panel
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.20...1.3.0 "")


## [1.2.21] - 2022-07-18
- Fixed security related to module attributes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.20...1.2.21 "")


## [1.2.20] - 2022-07-09
- Fixed security issue when uploading svg files with script tags
- Fixed security issue when using special characters as url parameters
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.19...1.2.20 "")


## [1.2.19] - 2022-07-04
- Fixed uploading, now requires a valid XSRF token.
- Fixed artisan command names and arguments are now with dash instead of underscore
- Fixed XSRF token is now obtained from the server by ajax request.
- Fixed forgot password now works by username not only by email.
- Fixed external URL redirect on logout, while providing username and password in the URL.
- Fixed toggle of notifications accordion was broken due Bootstrap 5 migration
- Fixed bug related to image resizing in live edit.
- Fixed twitter feed was not loading when you reload the module by JS. 
- Added ability for the code editor to pop-out in new window.
- Added icons in the dom tree in live edit for easier identification of elements.
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.18...1.2.19 "")


## [1.2.18] - 2022-06-22
- Fixed unpublished products that were shown on frontend
- Fixed empty CSS properties are removed when you reset the element from live edit
- Fixed mail sending, when using laravel config
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.17...1.2.18 "")


## [1.2.17] - 2022-06-14
 - Fixed Bootstrap 5 dropdowns and tabs 
 - Fixed api_url function to use router if exists
 - Other fixes
 - [see all changes....](https://github.com/microweber/microweber/compare/1.2.16...1.2.17 "")


## [1.2.16] - 2022-06-09
 - Fixed more RTL issues 
 - Fixed translation issues on the slider module
 - Fixed PNG anf GIF image transparency issue
 - Fixed Twitter feed module
 - Fixed contact form not accepting terms and conditions
 - Translations update 
 - Security fixes
 - Other fixes
 - [see all changes....](https://github.com/microweber/microweber/compare/1.2.15...1.2.16 "")


## [1.2.15] - 2022-04-29
- Added new free template based on Bootstrap 5
- Added support for inline svg in live edit
- Added support animations in live edit
- Fixed contact form not sending email, when disable mail saving option is enabled
- Security fixes 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.14...1.2.15 "")


## [1.2.14] - 2022-04-14
- Updated admin panel to Bootstrap 5
- Improvements on the editor when editing UL and LI elements
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.13...1.2.14 "")


## [1.2.13] - 2022-04-04
- Fixes right to left text alignment in the editor.
- Fixes on Multi language
- Security fixes
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.12...1.2.13 "")


## [1.2.12] - 2022-03-22
- Fixes on backup module
- Security fixes
- Added more validations
- Added more tests
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.11...1.2.12 "")


## [1.2.11] - 2022-02-19
- Updated Laravel to 8.83
- Fixes on live edit
- Fixes on multi language
- Fixes on backup module
- Fixes on shop module
- Fixes the security 
- Added more tests
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.10...1.2.11 "")


## [1.2.10] - 2021-12-08
- Fixes on live edit
- Fixes on multi language
- Fixes on coupon codes module
- Fixes on sitemap and rss feeds
- Fixes on categories module
- Fixes on comments module
- Fixes on paypal module
- Fixes on forgot password
- Added Laravel Dusk tests
- Some code refactoring 
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.9...1.2.10 "")


## [1.2.9] - 2021-09-16
- Updated Laravel to 8.61
- Fixes on the loading speed
- Fixes on the security
- Fixes on live edit
- Added service provider for templates
- Added new repository classes
- Added multilanguage module
- Added new icons for modules
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.8...1.2.9 "")


## [1.2.8] - 2021-07-08
- Updated Laravel to 8.4
- Fixes the custom fields
- Fixes the thumbnails
- Added new blog module
- Added new shop module
- Added ability for shop and blog to have filters
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.7...1.2.8 "")


## [1.2.7] - 2021-05-14
- Fixes on checkout module
- Fixes on marketplace module
- Fixes on categories module
- Fixes database connection not being closed sometimes
- Added content revisions module for live edit
- Added ability for menu module to return data
- Added svg support
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.6...1.2.7 "")


## [1.2.6] - 2021-04-29
- Fixes on the checkout module
- Fixes on the tags module
- Fixes on offers module
- Fixes on the package manager
- Fixes on link editor
- Added content revisions module for live edit
- Added webp support
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.5...1.2.6 "")


## [1.2.5] - 2021-04-15
- Fixes on the shipping module
- Fixes on the checkout module
- Fixes bug where needed to delete bootstrap/cache folder
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.4...1.2.5 "")


## [1.2.4] - 2021-04-12
- UI Fixes on mobile
- Live edit fixes  
- Fix CSS editor tools
- Fix Https not work properly with Full page cache
- Fix stats display bug for period more than year
- Added new translatable option fields 
- Added new default checkout page  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.3...1.2.4 "")


## [1.2.3] - 2021-03-30
- Update to Laravel 8.3
- Fix Tags problem
- Fix Offer price 
- Fix Contact form bugs
- Fix Multilanguage bugs 
- Fix https bug behind proxy 
- Fix Live edit bugs linking to layout
- Fix Live edit bugs related to cloudflare caching
- Added new elements for live edit
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.2...1.2.3 "")


## [1.2.2] - 2021-03-12
- Added cached model queries
- Fix multilanguage module 
- Fix stats module styling
- Fix Currency display in order list
- Fix Search in Client
- Fix backup module to include mutli language 
- Fix Reloading pictures module loop  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.1...1.2.2 "")


## [1.2.1] - 2021-03-05
- Added cached model queries
- Fix language translations and locales
- Fix design on the checkout popup
- Fix custom fields bugs
- Fix add order from admin
- Fix offers and discounted price
- Fix backup module
- Fix mail templates are now translatable
- Fix email sending on user registration
- Fix shop bugs related to quantity management
- Added client and address management for users
- Added support for more shipping providers
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.2.0...1.2.1 "")


## [1.2.0] - 2020-12-17
- Migrated to Laravel 8
- Refactored the internal code to Laravel style 
- New admin template
- Many other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.20...1.2.0 "")


## [1.1.20] - 2020-06-22
- Fix Module Menu Not working properly 
- Fix toolbar align on RTL 
- Fix some php 7.4 errors
- Fix center image does not work
- Fix Move layout up-down does not work
- Fix On paste two times it alerts false
- Fix Inline link editor with custom url must select the right section
- Fix Inserting modules with "small plus" does not record undo
- Fix on pages that require login, now redirects to the login form
- Fix Categories cannot be removed from post
- Fix problem with export/import Language file  
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.19...1.1.20 "")


## [1.1.19] - 2020-04-27
- Fix of Small plus popup 
- Layout spacing option is gone
- Paste in safe mode does not work
- Fix the design of the pick element tree
- Fix some php 7.4 errors
- Fix Paging in admin panel is gone
- Fix JS tree bugs
- Fix some bugs on the checkout 
- Fix Inline link editor with custom url must select the right section
- Added Dynamic text module
- Added Event mw.cart.checkout.order_paid 
- Added Recaptcha v2 and v3
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.18...1.1.19 "")



## [1.1.18] - 2020-03-13
- Update installation screen
- Update for Backup module
- Update for Visual editor module
- Update for Pictures module to be able to use media library
- Update for Acordion and Tabs module
- Update for Link editor
- Update for PDF module
- Update for Newsletter module
- Verious speed optimizations
- Many fixes on the live edit
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.17...1.1.18 "")



## [1.1.17] - 2020-01-24
- Added support for php 7.4
- Link editor is broken
- Fix icon picker
- Update iconsmind to woff2
- Alt text and description on image does not work
- Cant open module settings
- Crop image does not work in background image
- Enter key on last paragraph does not work
- Color picker default color is black
- Fixed Less compiler path resolution
- Fixed Less compiler path resolution
- Added ability to login user by token
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.16...1.1.17 "")


## [1.1.16] - 2019-12-19
- Fixes on multi language strings
- Fixes on mail templates
- Added uploader type field on template settings 
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.15...1.1.16 "")

## [1.1.15] - 2019-12-12
- Upload image does not work
- Custom fields module settings popup
- Modal is always moving to center
- Various RTL text issues
- Presets on module inside module
- Icon picker size was not removed from html element 
- Language entries with quotes was breaking JS messages
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.14...1.1.15 "")


## [1.1.14] - 2019-11-27
- Fix of category tree does not appear after 3 level
- Ability to change url of package manager
- $.ajax.done() was not avaible 
- Sub module settings handle open setting on parent module
- Change background picture does not work on old templates
- Put scroll on deep category tree
- Opening modal should close all handles submenus
- Issue with reset password
- Export orders to excel
- Email on new order is not editable
- Add page to menu is broken
- Tooltip must close on element change
- Added language choose method on install screen
- Added admin url setting on install screen 
- Added module install command from CLI 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.13...1.1.14 "")


## [1.1.13] - 2019-10-25
- Fixes on mw.dialog 
- Fixes on Custom fields 
- Fixes on Calendar module in Live Edit mode
- Fixes on Modal mw.dialog.get(...) is not a function when in mw.dialog
- Fixes on Testimonials module select image does not work
- Fixes on UI in RTL languages
- Fixes on Import from CSV 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.12...1.1.13 "")


## [1.1.12] - 2019-10-10
- Various live edit fixes
- cannot save "edit as HTML"
- Handle overlap problem ?
- Custom fields hidden field does not work
- Files browser add thumbnail size
- Extra tags on format of Heading
- Modal window scroll is missing
- Uploader in folder does not work
- Edit link Modal in small window
- Table editor is missing in live edit
- Jumping modal
- Shipping to country module in live edit
- Video module lazyload
- Modal horizontal scroll
- Fix toolbar align to be on left
- You must not to be able to write in icon
- Contact form module fixed
- Added option for user registration to be able to be approved by admin
- Added bank transfer payment method
- Added elements to be able to come from the active template
- Added custom fields module template support
- Added mail templates
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.11...1.1.12 "")


## [1.1.11] - 2019-09-11
- Various live edit fixes
- Fixes on the custom fields module to support templates 
- Fixes on the contact form module that was not saving in lists
- Fixes on the file browser module
- Fixes the text formating while elements has .safe-mode class 
- Fixes of the categories_images module to have more flexible selection 
- Fixes of the twitter feed and instagram feed module to cache the request 
- Fixes of accordeon module 
- Fixes of live edti toolbar  
- Added ability to change background image position 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.10...1.1.11 "")


## [1.1.10] - 2019-08-02
- Various live edit fixes
- Fixes on the new modal window
- Fixes on categories module
- Fixes cart module 
- Added ability to export and import language entries 
- Added ability upload template 
- Added ability to use template framework classes in CSS grid 
- Added new module for unified template settings generated from config.php (beta) 
- Added background job queue (beta) 
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.9...1.1.10 "")


## [1.1.9] - 2019-08-02
- Overall stability fixes
- Implemented new modal window
- Implemented media library
- Implemented Less parser
- Finished the import export function
- Fix a lot of handles bugs
- Fix edit link dialog window
- Fix presets drop-down for new modal
- Fix some custom fields bugs
- Fix Mail setting in admin
- Ability to edit comment after post
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.8...1.1.9 "")


## [1.1.8] - 2019-07-04
- Accordeon and tabs modules does not work 
- Target doesn't work on menu
- Visual editor bugs
- Cant remove custom field value
- Small WYSWYG editor is not triggering change event
- Live edit handle is disappearing and hard to reach
- Added ability to add custom  link to #section
- Contact form possibility to have more input fields
- Contact form external API's integration
- Contact form possibility to add attachment to reply message
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.7...1.1.8 "")


## [1.1.7] - 2019-06-21
- Icons for "Live Edit" sidebar
- Visual editor bug fixes and impovememnts like padding editor, erc
- Added less parser and template settings from .less files
- dialog.js fixes
- Duplicate posts appear on default content
- Update popup
- $.ajax success callback
- Paging is missing in admin panel
- Insert image doesn't work
- New backup manager beta
- Added ability to set size of custom fields
- Other fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.6...1.1.7 "")


## [1.1.6] - 2019-05-21
- Module presets fix
- fix UI alignment of buttons
- Problem with the layouts
- Sub-module settings does not appear
- Update composer installers directory discovery logic
- Clear format is not working
- Add footer tags customization
- Fix version in Composer
- When you change date format can't save 
- Cant upload picture
- Tabs module in Dream template
- Accordeon modules does not work
- Fix categories display on posts module
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.5...1.1.6 "")


## [1.1.5] - 2019-05-02
- Sometimes the Dragging on Layouts doesn't work
- Fix flicking of handles
- Restore content in trash did't work
- Resizeable control outline misplaced on live edit
- There was class .element on all modules
- Format bug when you reformat after format 
- Format looses selection
- CSS editor can't select nodes
- Style visibility: visible was appearing on all elemennts
- Increase the pooling on is_logged function
- Other small fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.4...1.1.5 "")


## [1.1.4] - 2019-04-03
- Various live edit fixes
- Fix encryption key
- Color overlay does not work
- Comment publish/unpublish does not work
- Fix flicking of handles
- Jumping icon
- Fix grid button
- Handle overlaps toolbar
- Fix UI alignment of buttons
- btn-primary is broken
- Multiple pictures upload
- Fix undo issues
- CSS editor beta version
- Other small fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.3...1.1.4 "")


## [1.1.3] - 2019-02-28
- Added package manager 
- Fixes on the live edit 
- Live edit handles improvements 
- Live edit performance improvements 
- Improvements on newsletter and calendar modules
- Better touch screen support
- Better RTL support
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.2...1.1.3 "")


## [1.1.2.1] - 2018-12-14
- Maintenance release 
- Some fixes on the live edit 
- Custom CSS editor now saves properly
- Added compatibility with Gramarly and Naptha browser plugins


## [1.1.2] - 2018-12-06
- Major update 
- Many overall system improvements
- Drag and drop is stable now
- Introduction of new CSS framework for styling 
- New JavaScript components added
- New modules data management mechanism by JSON schema   
- Added "Coupon Code" module
- Added "Offers (Price Discount)" module
- A lot of bug fixes
- [see all changes....](https://github.com/microweber/microweber/compare/1.1.1...1.1.2 "")



## [1.1.1] - 2018-08-05
- Added: presets for modules 
- Added: gulp support
- Added: events calendar module
- Fix: bugs in the live edit
- Fix: some bugs in the modules parser  
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.11...1.1.1 "")

 

## [1.1.0] - 2018-07-05
- Added: Changed the whole UI of admin panel
- Added: You can now drop full layouts
- Added: CSS editor
- Added: HTML editor
- Added: Site statistics
- Added: SEO features 
- Fix: Huge Live Edit impovements
- Fix: Parser bugs
- Changed: to MIT license 
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.8...1.0.10 "")


## [1.0.8] - 2017-04-26

- Added: testimonials module
- Added: twitter feed module
- Added: teamcard module
- Added: tabs module
- Added: favicon support
- Added: google analytics support
- Added: 'before/after' module
- Added: PDF Module
- Added: calendar module
- Added: facebook page module
- Added: category_images module
- Added: parallax module
- Added: rating module
- Added: updated to laravel 5.4
- Added: Some JS libs
- Added: Some PHP libs
- Added: Paste from word button
- Added: setting to allow admin to login only from some IP's
- Added: captcha setting to login form
- Added: more registration and login settings
- Added: payment methods voguepay.com, przelewy24.pl, mollie, etc 
- Added: start depth setting for breadcrumb
- Added: ability to edit link text in the popup
- Added: search by custom fields basic functionality
- Added: translation for Arabic language
- Added: translation for Bahasa Indonesia
- Added: btn module now has skins
- Added: link parameter in `pages_tree` now can be a closure
- Added: JS validation engine
- Added: override method for options
- Added: verify email function for registered users
- Added: htmlpurifier library
- Added: notification_data field to the notification table
- Added: api_expose_user function
- Added: allow keyword search in joined table
- Added: option to require captcha on login
- Added: option to disable registration with disposable email address
- Added: layouts on in the toolbar now have title  shown
- Added: jquery UI version updated
- Added: new function is_cli()
- Added: module for content revisions
- Added: force_https setting to force the site to be on https
- Added: way to change the admin panel url from the config file #277
- Added: option to set max quantity per product order
- Added: drag & drop improvements
- Added: wrapping laravel classes
- Fix: mail sending error when escapeshellcmd() has been disabled for security reasons
- Fix: reflected xss reported by Kacper Szurek
- Fix: check file mime type if finfo function exists
- Fix: live edit fixes
- Fix: table edit  
- Fix: support for postgres 
- Fix: fix when .env file does not exist
- Fix: menu module bugs
- Fix: UI fixes
- Fix of:  #380, #395, #388, #378, #377, #373, #372, #369, #368, #362, #360, #357, #353, #352, #346, #340, #334, #298, #277, #225
- [see all changes....](https://github.com/microweber/microweber/compare/1.0.7...1.0.8 "")




## [1.0.7] - 2016-03-01

- Vendor: added Omnipay library
- Vendor: added coduo/php-humanizer library
- Vendor: added league/csv library
- Added: taxes support
- Added: caching with .htaccess on some folders
- Added: payment providers (Mollie,Stripe and Authorize.net)
- Added: taxes support
- Added: url support for categories
- Added: crud class
- Added: custom field type "property"
- Added: "max depth" setting to the categories module
- Added: Portuguese language
- Added: LinkedIn login
- Added: export orders to SCV
- Refactor: database, content manager, orders and shop classes
- Fix: general UI improvements
- Fix: security fixes
- Fix: bugfixes in custom fields
- Fix: bugfixes drag and drop
- Fix: thumbnail resize fix
- Fix: fixed dropdown bug when you click on the scroll
- Fix: mw.tooltip issues
- Fix: mw.modal issues
- Fix: sitemap and rss feed
- Fix: performance improvements when there are many categories
- Fix: cache files number reduced
- Fix: fix of notifications  
- Fix: fix of import module
- Various other fixes



## [1.0.6] - 2015-11-19

- Vendor: added Twig parser
- Added: taxes support
- Added: tabs module
- Added: twig parser to shop email template
- Added: content revisions (for new installs only)
- Added: data fields support for categories
- Added: country states class
- Added: .allow-drop css class on live-edit field to allow dropping inside modules
- Added: custom reset password email
- Added: custom register user email
- Refactor: cart moved to own class
- Refactor: orders moved to own class
- Refactor: content data fields moved to own class
- Refactor: content attributes moved to own class
- Fix: search function to exclude deleted content #313
- Fix: uploader makes all files lowercase
- Fix: live edit fixes on columns
- Fix: forms module export to excel
- Fix: Unzip was making all files lowercase
- Fix: URLs are now sequential
- Other fixes

## [1.0.5] - 2015-10-10

- Added: Custom content fields now can be added from the template config file
- Added: Session-less routes and Middleware
- Added: Various UI hooks
- Added: Support for custom data fields
- Added: Cache driver support (Files, APC)
- Added: Template class
- Added: Caching option for apijs and frontend assets
- Fix: create post/product now have parent page
- Fix: parser was outputting the same content in different edit fields
- Fix: module id is now generated with md5 instead with crc32
- Fix: is_home bug under sqlite
- Fix: install bug
- Fix: pqsql fix
- Fix: shipping to country bug with the select menu
- Fix: custom field default values were showing 0
- Fix: editor improvements
- Fix: menu reorder bug
- Fix: google fonts loading
- Fix: facebook login bug
- Fix: mw.front event was called before constants are defined
- Fix: bug in image cropping
- Fix: ConfigSave now replaces the storage_path
- Fix: shipping bug
- Fix:  undo is now working properly
- Fix: preg_replace bug
- Fix: icon picker
- Fix: save edit now uses MutationObserver
- Fix: category reordering
- Fix: live edit changes were not saved when editing an image
- Fix: pixum now caches the images
- Fix: drag and drop in row was appending the item on the wrong place
- Fix: upload image shows progress again
- Fix: resizable columns bug when no width is set
- Fix: redirect in views and modules was not working
- Fix: updated jquery-ui to 1.11.4
- Fix: captcha is now working on pages with multiple captchas
- Fix: upload file bug
- Fix: default controller's render logic now moved in the template class
- Many other fixes



## [1.0.4] - 2015-08-04
- Vendor: added Laravel HTML helper class
- Vendor: updated jquery ui
- Added: template manager class
- Added: XSS filter class
- Added: `artisan microwber:update` command
- Fix: security fix in uploader
- Fix: security fix user creation
- Fix: editing menus bug
- Fix: delete abandoned cart
- Fix: query pagination was skipping pages
- Fix: menu title was outputting html tags
- Fix: menu module bug when editing
- Fix: add to cart with quantity parameter
- Fix: cache now uses locale prefix
- Fix: updated raw SQL queries to use Eloquent
- Fix: redirect bug when using redirect from template
- Fix: UI in forms module in admin
- Fix: captcha validation when there are multiple catchas on one page
- Fix: module id was random when using module inside module
- Fix: other small bugs


## [1.0.3] - 2015-06-15

- Vendor: Updated Laravel to v5.0.33 (last version that supports php 5.4)
- Feature: Custom fonts support (only Google fonts for now)
- Feature: Color picker can set custom color from hash
- Feature: CSS Editor
- Feature: Icon picker
- Feature: Custom font size value can be set
- Fix: improvements in the live edit for Boostrap3 templates
- Fix: possible XSS in users
- Fix: fixes in shop orders management
- Fix: many other minor bugs


## [1.0.2] - 2015-06-01


- Added: get_category_items_count function
- Added: Some missing language entries
- Added: `sum` parameter to the `db_get` function
- Added: `connection_name` parameter to the `db_get` function
- Changed: replaced database class with database_manager
- Fix: shop currency function timeout
- Fix: modal initialization
- Fix: update check
- Fix: install problem under mysql 5.6
- Fix: drop in bootstrap columns
- Fix: improvements for bootsrap themes
- Fix: edit link from live edit
- Fix: cart shipping price display
- Fix: shipping cost per weight
- Fix: price bug
- Fix: shipping cost per weight
- Fix: locale now uses App::getLocale()



## [1.0.1] - 2015-05-12

- Vendor: added Omnipay for payments processing
- Vendor: Markdown provider
- Added: added option to change the currency sign position
- Fix: Custom environment name respects the `getenv` value
- Fix: Cache expiration
- Fix: Category delete
- Fix: Position db field is now converted to integer
- Fix: Delete categories bug
- Fix: Reorder categories bug
- Fix: Factored DB class
- Tests added
- Many other fixes

## [1.0.0] - 2015-02-16

- Moved to Laravel 5

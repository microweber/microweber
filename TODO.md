# MW v2 → Filament 5 Admin Design Migration Plan

> **Source design:** https://demo.microweber.org/
> **Clone-website skill:** https://agents.tools.ooyes.net/skills/clone-website.yml
> **UI test workflow:** https://agents.tools.ooyes.net/workflows/dev-cycle/02-test-the-project-ui.yml

---

## Migration Approach

Each page migration follows this cycle:

1. **Capture reference** — Use the clone-website skill (Phase 1: Reconnaissance) to screenshot the MW v2 page at `https://demo.microweber.org/admin/<path>` and extract design tokens, layout patterns, spacing, colors
2. **Inspect current Filament page** — Open the local Filament admin, compare against the MW v2 reference
3. **Implement** — Update Filament Resource/Page forms, tables, Blade views, and CSS to match the MW v2 design language (section icons, card layouts, typography, colors, dark mode)
4. **Visual QA** — Use the UI test workflow to verify pixel-level match at desktop and mobile viewports, light and dark mode
5. **Commit** — One logical change per page/group

---
 

## Todo



- [x] 2026-04-06  sidebar icons on the sub item aren ot the same color and sidebars with subintem fave messeg padding not aligned, pls fix
- [x] 2026-04-06  ok but icon aligment on the items with subitmes is not the same as the itmes without suitmes
- [x] 2026-04-06  also work on the bashabrd and hte main contnaer spasnc see the odiginal site and make a plan to fix the conainter , see on the original we dont strech to the fill site, make a plan and populat rhe todo

### Main container spacing plan

**Problem:** At 1920px viewport, content stretches to 1617px with only 16px side padding — too wide, not matching MW v2.
**Root cause:** SCSS `.fi-main { padding: 10px 16px !important; }` overrides the responsive padding in `global.css` (`lg:px-[5rem] md:px-[3rem] sm:px-[1rem]`).
**Fix approach:** Replace the fixed SCSS padding with responsive padding that scales with viewport width, and add a max-width on the page content to cap stretching on ultra-wide screens.

- [x] 2026-04-06  fix: remove SCSS `!important` padding override on `.fi-main` and restore responsive side padding (lg:80px, md:48px, sm:16px) so content doesn't stretch edge-to-edge on wide screens
- [x] 2026-04-06  fix: add max-width constraint (1440px) on `.fi-page > div` to cap content width on ultra-wide monitors while keeping it centered
- [x] 2026-04-06  verify: test layout at 1920px, 1440px, 1024px, and 768px viewports — content should have proportional side spacing and never stretch beyond 1440px
- [x] 2026-04-06  on fix the pages and post and proiduct header there is isme white backgound on it
## Done

- [x] 2026-04-06  fix the side body cool fix the side by The Concourse on the ATMs and the super teams
- [x] 2026-04-06  remove the dashboard container shadow
- [x] 2026-04-06  work on the main container seeing the main container on the original website there is padding on the left and the right which is responsive for making the same so we don't stretch the content on the fluid work on the container

- [x] 2026-04-06  the sidebar has some double underlines please fix
- [x] 2026-04-06  sidebar isi sntill not the same design as the old version, evalue and fix
- [x] 2026-04-06  migrate the sidebar design to match MW v2

- [x] 2026-04-06  on the pages posting product lists on the bottom the paging selector is not okay

- [x] 2026-04-06  on the page post product list table made the title of the page bigger

- [x] 2026-04-06  in the sidebar the padding of the items with suit items the first item padding is not okay and color is not okay

- [x] 2026-04-06  also work on the dashboard we want the cards to not have white background on the container and see the old dashboard we want the same

- [x] 2026-04-06  dashboard design is still not okay

- [x] 2026-04-06  on the dashboard when you click on the emails link it doesn't work

- [x] 2026-04-06  dashboard remove the last 30 days link on the top because we already have statistics

- [x] 2026-04-06  the the pagigng controlls on all pages and add paging and limit seelctions on them issing

- [x] 2026-04-06  on the dashboard click on view comments the table is not ok see the Potent page stable

- [x] 2026-04-06  the Welcome note on the dashboard is spliced to the cart it needs some padding

- [x] 2026-04-06  on the sidebar there is from bottom border on the menus please remove it

- [x] 2026-04-06  on up page screen on the menu Selecter app search and make it through the top 10 menus with expandable box

- [x] 2026-04-06  on the dashboard the card bottles are not very visible please fix them

- [x] 2026-04-06  on the dashboard on the show more the statistics of the old version are better please make the new version the same

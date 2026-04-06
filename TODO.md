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

- [ ] fix the side body cool fix the side by The Concourse on the ATMs and the super teams

- [ ] remove the dashboard container shadow

- [ ] work on the main container seeing the main container on the original website there is padding on the left and the right which is responsive for making the same so we don't stretch the content on the fluid work on the container

## Done

- [x] 2026-04-06  the sidebar has some double underlines please fix
- [x] 2026-04-06  sidebar isi sntill not the same design as the old version, evalue and fix
- [x] 2026-04-06  migrate the sidebar design to match MW v2

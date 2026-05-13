# Novice Customer Report

> Persona audit by the "novice-customer" sub-agent (definition: <https://agents.tools.ooyes.net/agents/novice-customer/>).
> Voice: first-person, lived-experience. The persona is "Brenda" — a first-time site owner with no technical background, no jargon tolerance, a 90-second abandon clock.

---

## 2026-05-13 — Live Edit first-90-seconds audit

15 findings across the Live Edit toolbar, the +ADD picker, the upload-image modal, the mw-media-browser, the mw-modal Livewire wrapper, and the Filament login page. Severities: 3× P0, 7× P1, 4× P2, 1× P3.

### Brenda's verdict

> "I came here to put a photo on my homepage. After 90 seconds I had: clicked a button labelled '+' that I didn't understand, opened a modal I couldn't close, picked 'New Image' because the others sounded like jargon, uploaded a photo, accidentally clicked 'Browse Media Library' which navigated me away losing my upload, came back, gave up, and clicked the three-line menu hoping for a 'Help' link. There wasn't one. I closed the tab."

### Top blockers (P0)

| # | Title | File |
|---|---|---|
| 1 | "+ADD" label is `hidden`; only the plus icon shows | `AddContentButton.vue:5` |
| 2 | Save & Publish publishes instantly, no confirmation, no undo | `SaveButton.vue:192` |
| 3 | Picker has no Cancel button — only a small X | `AdminLiveEditPage.php:143-144` |

### High-priority follow-ups (P1)

| # | Title | File |
|---|---|---|
| 4 | "Add a block to THIS page" entry missing from picker | `AdminLiveEditPage.php:121-133` |
| 5 | Page/Post/Category disambiguator copy still confusing | `AdminLiveEditPage.php:81-104` |
| 6 | Hamburger menu has no avatar/label affordance | `Toolbar.vue:114-120` |
| 7 | "VIEW" button has no tooltip, silently changes UI mode | `Toolbar.vue:107` |
| 8 | "Browse Media Library" footer link in upload modal navigates away mid-upload | `AdminLiveEditPage.php:219` |
| 9 | mw-media-browser "Select media file or Upload" affordance unclear | `mw-media-browser.blade.php:62-66` |
| 10 | Login page missing `autocomplete="username"/"current-password"` for password managers | `Login.php:25-37` |

### Medium polish (P2)

| # | Title | File |
|---|---|---|
| 11 | New-Post empty state lands on blank page with no visible click target | `AdminLiveEditPage.php:684, 707` |
| 12 | "Show all options" context-loss when admin page opens in new tab | `AdminLiveEditPage.php:602, 614-617` |
| 13 | mw-modal close X lives inside the child component, not on the wrapper | `mw-modal.blade.php:66-74` |
| 14 | Picker search jargon "Search content types…" + no synonyms (photo→image, article→post) | `add-content-modal.blade.php:77, 114` |

### Low polish (P3)

| # | Title | File |
|---|---|---|
| 15 | mw-modal scroll restore jumps to top when `mwModalScrollY` missing (nested modal race) | `mw-modal.blade.php:115-118` |

---

## Shipped this session

See git log for commits referencing `task-2026-05-13-899d57`. Subtask resolution is tracked in TODO.md under the parent entry.

## Deferred

Items not shipped this session remain in TODO.md as `(subtask) [ ]` lines under the parent. Future sessions can pick them up by severity.

# Troubleshooting

Common LiveEdit issues with diagnostic steps.

---

## SAVE submits the wrong form

**Symptom.** You're editing a post inside a module-settings table action. You click toolbar SAVE. Instead of saving the post, the outer module-settings form submits (saving nothing meaningful).

**Cause.** The save-flow specificity ranker (in `iframe-page.blade.php` lines 349–494) couldn't unambiguously identify the innermost form. Most common reason: the post-edit modal didn't render as a real `<form wire:submit.prevent="...">` — it rendered as a Filament action button that triggers a Livewire method without an enclosing form element.

**Diagnosis.**

In the browser DevTools, with the post-edit modal open:

```js
// Count all wire:submit.prevent forms
document.querySelectorAll('form[wire\\:submit\\.prevent]').length

// And inside same-origin iframes
Array.from(document.querySelectorAll('iframe')).forEach(f => {
    try {
        console.log(f.src, f.contentDocument?.querySelectorAll('form[wire\\:submit\\.prevent]').length);
    } catch (e) { /* cross-origin */ }
});
```

If only the outer form shows up, the inner edit modal lacks a `wire:submit.prevent` wrapper. Fix it by ensuring the inner Filament action emits a real form — usually a matter of adding `->form([...])` to the action definition (Filament wraps `->form()`-equipped actions in a real `<form>`).

If multiple forms show up but the wrong one wins, check the precedence rank — the handler picks `callMountedTableBulkAction > callMountedTableAction > callMountedFormComponentAction > callMountedAction`. Your inner form probably uses a lower-precedence handler. Make it a `TableAction` or a form-component action to bump precedence.

---

## CustomEvent fires but listener never runs

**Symptom.** You dispatched a verb (e.g. `liveEditAddContentSaved`) but the listener never fires. The console shows no errors.

**Cause.** The verb has exactly one dispatcher and exactly one listener. You added the dispatcher but forgot to wire the listener on the other surface (or vice versa). This is the **silent failure mode** of the two-surface architecture.

**Diagnosis.**

In the browser DevTools, on the surface where you EXPECT the listener to run:

```js
// Manual sanity check
window.addEventListener('liveEditAddContentSaved', (e) => {
    console.log('liveEditAddContentSaved listener fired with detail:', e.detail);
});

// Then dispatch from the other surface (or fake it locally):
window.dispatchEvent(new CustomEvent('liveEditAddContentSaved', { detail: { url: '/test' } }));
```

If the manual dispatch fires your test listener, the verb plumbing works — your problem is just that the production listener is missing or registered too late. Search the codebase:

```bash
grep -rn "liveEditAddContentSaved" src/ packages/ Modules/ --include="*.blade.php" --include="*.js" --include="*.vue"
```

You should see at least one `dispatchEvent` and one `addEventListener` for every verb. If only one or zero match, the verb is broken.

If the manual dispatch ALSO doesn't fire your test listener, JavaScript is throwing an error somewhere earlier in the page load and the listener registration never ran. Check DevTools Console for the first error.

---

## Modal close-on-backdrop discards unsaved typing

**Symptom.** User types into the compact create-record modal, accidentally clicks outside, modal closes, typing is lost. Reports of "save button is broken — my work disappears".

**Cause.** A regression — the `generateAction` is supposed to call `closeModalByClickingAway(false)` + `closeModalByEscaping(false)` (see the [API reference](./api.md#generateactionstring-actionname-string-contenttype-filamentactionsaction)). If a custom override forgot one of those calls, the protection is lost.

**Diagnosis.**

Open `AdminLiveEditPage::generateAction()` (or your custom override). Confirm both lines exist:

```php
->closeModalByClickingAway(false)
->closeModalByEscaping(false)
```

If a project-level override extends the page, check that `parent::generateAction()` is called AND that the override doesn't reset these flags to `true`.

The +ADD picker (`addContentAction`) intentionally allows close-on-backdrop because there's nothing to lose if the user cancels. The compact create-record dialog (`generateAction`) intentionally does NOT — protect that distinction.

---

## Canvas iframe loads but no toolbar buttons respond

**Symptom.** Live Edit URL loads. The page renders. Toolbar appears at the top. Clicking SAVE / UNDO / +ADD does nothing — no console errors.

**Cause.** Two common roots:

1. The Vue toolbar bundle didn't load (404 on the JS file).
2. The toolbar loaded but `window.Livewire` is undefined (Livewire didn't boot inside the iframe).

**Diagnosis.**

```js
// In browser DevTools console, on the Live Edit page:

// Is the toolbar bundle loaded?
document.querySelectorAll('script[src*="frontend-assets/build"]').forEach(s => {
    fetch(s.src, { method: 'HEAD' }).then(r => console.log(s.src, r.status));
});
// Expected: all 200

// Is Livewire booted?
typeof window.Livewire
// Expected: 'object'

// Is mw.app reachable?
typeof window.mw?.app
// Expected: 'object'

// Does dispatch even work?
window.dispatchEvent(new CustomEvent('liveEditSaveCallMountedAction'));
// Expected: nothing visible, but no errors thrown
```

If the bundle 404s, the build wasn't run after a deploy. Run:

```bash
cd packages/frontend-assets && npm run build
```

If Livewire is undefined, the Filament page didn't initialize. Refresh the page; if it persists, check Laravel logs for a server-side error during the page mount.

If `mw.app` is undefined, the canvas-side scripts didn't load. Check the canvas iframe (NOT the parent frame) — `mw.app` lives in the iframe scope, not the parent.

---

## Save flow throws "form not found"

**Symptom.** SAVE button shows a brief loading state then silently fails. DevTools console logs `Save flow: no submittable form found`.

**Cause.** No `<form wire:submit.prevent="callMountedAction">` (or any of the higher-precedence variants) exists in the DOM at the moment SAVE fires. Most likely:

1. The user clicked SAVE while a transition was in flight (modal mounting/unmounting).
2. A custom override of `AdminLiveEditPage` removed the default action's form rendering.
3. The current focused modal is a non-Livewire view (e.g. a raw HTML modal injected by a non-Filament integration).

**Mitigation.** Add a guard in your custom code to ignore save clicks during transitions:

```js
let isTransitioning = false;

window.addEventListener('openAddContentAction', () => {
    isTransitioning = true;
    setTimeout(() => isTransitioning = false, 300);
});

window.addEventListener('liveEditSaveCallMountedAction', (e) => {
    if (isTransitioning) {
        console.log('Ignoring save during transition');
        e.stopPropagation?.();
    }
}, true);  // capture phase so this runs before the iframe-page handler
```

For case (3), wrap your non-Livewire modal's submit in a `liveEditSaveCallMountedAction` listener that runs your own save — see [examples #4](./examples.md#4-custom-save-handler-for-a-bespoke-editing-surface).

---

## Element selection is invisible (no outline on click)

**Symptom.** You click an element on the canvas. Nothing visibly changes. No `.element-active` outline appears.

**Cause.** The AI-513 selection-visibility CSS isn't loaded. Or `.element-active` IS being applied but the CSS rule is overridden by a stronger selector later in the cascade.

**Diagnosis.**

```js
// In canvas iframe DevTools console
document.querySelector('.element-active')
// Expected: the selected element (or null if click handler didn't run)
```

If `null`: the click handler in `live-edit-page-scripts.js` didn't fire. Check whether the element has any handlers blocking it (pointer-events: none, parent .mw-prevent-interaction, etc.).

If non-null: the class is being applied but no outline appears. Open DevTools Elements panel, select the element, check Computed Styles for `outline`:

- If `outline: none` → some later CSS rule wins. Look for `* { outline: none }` resets in custom themes.
- If `outline: 2px solid #0d6efd` (the AI-513 value) → the outline IS painting but you can't see it (maybe `overflow: hidden` on a parent is clipping it, or z-index issues, or `outline-offset` is negative).

The AI-513 selection-visibility CSS lives at `packages/frontend-assets-libs/resources/local-libs/css/liveedit.css` (served-source-of-truth, rebuilt via `frontend-assets-libs/build.mjs`). If the file is missing the AI-513 block, re-build:

```bash
cd packages/frontend-assets-libs && npm run build
```

---

## "Add a block" picker card does nothing

**Symptom.** Click +ADD → click "Add a block to this page" card → picker closes → nothing happens.

**Cause.** The card dispatches `liveEditInsertLayoutRequest` (it has `js_dispatch` set, not a Livewire `wire:click`). The verb fires successfully, but the canvas-side listener can't find `mw.app.editor` and silently bails.

**Diagnosis.**

```js
// In canvas iframe DevTools console
typeof window.mw?.app?.editor
// Expected: 'object'

typeof window.mw?.app?.editor?.dispatch
// Expected: 'function'
```

If either is undefined, the canvas-side editor scripts (`live-edit-page-scripts.js`, `liveeditmode.js`) failed to load or initialize. Rebuild frontend-assets:

```bash
cd packages/frontend-assets && npm run build
```

If `mw.app.editor.dispatch` exists but the layout dialog still doesn't open: the dialog is part of legacy canvas-side JS that depends on `frontend-assets-libs`. Confirm:

```bash
curl -I http://your-site/vendor/microweber-packages/frontend-assets-libs/api/editor/liveeditmode.js
# Expected: 200
```

If 404, run:

```bash
cd packages/frontend-assets-libs && npm run build
```

---

## Title carry-forward to admin Create form not populating

**Symptom.** User types a title in the compact create-record dialog, clicks "Show all options" footer button. New tab opens at the full admin Create form. The Title field is empty (instead of carrying the typed value).

**Cause.** The `mw-open-in-admin-btn` JS hook in `iframe-page.blade.php` (lines 660–662) reads the typed title from the modal's input and appends it to the link's `href` as a query parameter. If the hook isn't running, the carry-forward never happens.

The receiving side is `Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent::getInitialData()` (NOVICE #12 work). It reads `request()->query('title')` / `?body=` / `?excerpt=` and pre-fills the form with length caps (256 / 6 KB / 2 KB).

**Diagnosis.**

In the compact dialog with a title typed, before clicking "Show all options":

```js
// Find the link
const link = document.querySelector('a.mw-open-in-admin-btn');
console.log('href:', link.href);
// Expected: includes ?title=<typed>&body=<typed>...
```

If the href has no `?title=`, the JS hook isn't running. Check Console for errors. The hook is wired via the modal's `x-init` or a `setupTitleSyncOnMount()` call — depends on Filament version.

If the href has `?title=` but the full admin form doesn't pre-fill, the receiver's `getInitialData()` isn't reading correctly. Confirm:

```php
\Modules\Content\Filament\Admin\ContentResource\Pages\CreateContent::class
```

contains `getInitialData()`. If a project override replaced it without `parent::getInitialData()`, the carry-forward is lost. Restore the parent call.

---

## Module-settings slide-over loads then immediately closes

**Symptom.** Click a module on the canvas. Slide-over starts to mount. Closes immediately. Console shows no errors.

**Cause.** Two common roots:

1. The `openModuleSettingsAction` event detail is malformed — the `component` key references a class that doesn't exist or isn't registered.
2. The component's `mount()` method throws and Filament silently unmounts on exception.

**Diagnosis.**

```js
// In admin frame DevTools console
window.addEventListener('openModuleSettingsAction', (e) => console.log('opening:', e.detail), true);
```

Then click the module. The log shows the `detail` payload. Confirm `detail.component` is a real, registered class name.

```php
// In Laravel tinker
class_exists($detail['component']);
// Expected: true
```

If false → the canvas-side dispatcher is sending stale or wrong data. Find the dispatcher (search the canvas-side JS for `openModuleSettingsAction`) and check what's being put in `detail`.

If true → the class loads but its `mount()` throws. Check Laravel logs (`storage/logs/laravel.log`) for the exception immediately preceding the slide-over close.

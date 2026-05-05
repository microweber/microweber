# UX Feedback

## 2026-05-05 — task-2026-05-05-02f93f — ux-engineer audit

### Audit summary

Highest remaining friction in the live-edit Add Content flow was failed-save clarity.

When the compact modal's required title was left empty, the main live-edit `SAVE` button could feel like it did nothing. The issue was most visible on the product flow, where the mounted action did not always surface an obvious server-rendered invalid state through the compact modal.

### What shipped

- Intercept empty compact-title saves in `src/MicroweberPackages/LiveEdit/resources/views/iframe-page.blade.php` before `requestSubmit()`.
- Apply immediate inline validation treatment to the compact title field:
  - invalid styling on the input/wrapper
  - explicit "The title field is required." message
  - scroll/focus reveal back to the title field
- Emit `liveEditMountedActionValidationFailed` consistently for mounted-action validation failures.
- Update `packages/frontend-assets/resources/assets/ui/components/Toolbar/SaveButton.vue` so mounted-action validation shows a validation-specific toast instead of a misleading page-saved success toast.
- Strengthen `tests/Browser/LiveEditAddContentValidationFailPathTest.php` to assert visible invalid/error UX, not just the lack of a DB write.

### Review

Result: the Add Page, Add Post, and Add Product fail-path now behaves like a clear form validation flow instead of a silent no-op. The user is sent back to the missing title field with obvious inline feedback, and the toolbar toast no longer conflicts with the modal state.

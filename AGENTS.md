<!-- autodev:profile-ref:begin -->
file:///home/headless/Documents/GitHub/microweber/.autodev/AGENT_PROFILE.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/00-identity.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/01-learning.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/02-memory-mcp.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/03-living-docs.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/04-skill-files.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/05-skill-creation.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/06-core-rules.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/07-core-loop.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/08-thinking.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/09-parallel-panel.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/10-codebase-verification.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/11-git-debug-security.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/12-todo-format.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/13-workflow-principles.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/14-contracts.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/15-soul.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/16-journal.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/17-issue-tracking.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/18-knowledgebase.md
file:///home/headless/Documents/GitHub/microweber/.autodev/profile/19-subagent-context-management.md
<think>
IMPORTANT: Read all the instruction files listed above before proceeding.
They contain your core protocols, rules, and operational guidelines.
</think>
<!-- autodev:profile-ref:end -->

## Skills Update — 2026-05-27

**Filament mobile table stacking** (`.claude/skills/filament-developer/SKILL.md` §11) documents the `stackedOnMobile()` + label-visibility-gap pattern discovered in AI-1132. Filament v5 defaults `stackedOnMobile` to `false`, so the server never renders `.fi-ta-cell-label` divs unless explicitly opted in — and even when enabled, Filament's `sm:hidden` hides labels above 640px while Microweber's card CSS activates at 1024px, creating a 640px–1024px gap where labels are invisible. The skill documents the global `Table::configureUsing()` opt-in plus the CSS `display: block !important` override pattern (with dark-mode variant) to bridge the gap.

## Skills Update — 2026-05-26

Two new skills formalized from this session's P0/P2 fixes. **`vue3-scoped-dark-mode`** (`.claude/skills/vue3-scoped-dark-mode/SKILL.md`) captures a silent, non-obvious Vue 3 SFC gotcha: `<style scoped>` appends `[data-v-xxx]` to every selector in a descendant combinator — including ancestor selectors like `html.dark` that live on `<html>`, which never carries the scoping attribute — so dark-mode overrides silently never match; the fix is wrapping ancestors in `:global()`. **Filament v5 enum namespace migration** (`.claude/skills/filament-developer/SKILL.md` §7) documents that `TextColumnSize`, `IconColumnSize`, and `ActionSize` moved to `Filament\Support\Enums\*` in v5 — using the old path compiles fine but crashes at runtime with "Class not found", which is P0-severity when it hits a primary admin route like `/admin/module-resource/modules`.

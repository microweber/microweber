# Monorepo Split — Work Report & Checklist

**Scope:** split the Microweber monorepo into independently published repositories
so that `composer require microweber/microweber` (and each package individually)
resolves from Packagist/VCS instead of in-tree `path` repositories.

**Snapshot of current state (measured on `filament-5`):**

| Area | Count | How it's wired today |
|---|---:|---|
| `packages/*` | 86 | all have `composer.json`; root declares **77 `path` repositories** |
| `Modules/*` | 100 | 99 have `composer.json` (**`Modules/HostingApi` is missing one**); vendor `modules/*`, `type: microweber-module` |
| `Templates/*` | 32 | 31 have `composer.json`; vendor **`nwidart/*`** (not ours), `type: microweber-template` |
| vendor prefixes (packages) | — | `microweber-packages/*` ×77, `microweber/*` ×5, `microweber-deps/*` ×1, forks `arcanedev/*` ×2, `torann/*` ×1 |
| forks pinned | — | *(resolved — see "Established conventions" below)* `composer/installers` + `arcanedev/*` + `torann/*` now vendored & published under `microweber-packages/*`; `minimum-stability: dev` still repo-wide |
| standalone-ready | 51 / 86 | have `orchestra/testbench` in `require-dev` (harness: `scripts/standalone-test.sh`, `packages/STANDALONE.md`) |
| hardcode a `version` | **82 / 86** | `"version": "1.0.0"` etc. baked into `composer.json` |

---

## Established conventions (settled in the deps-forks pilot)

The four vendored dependency forks were split first as a pilot; the rules they
settled apply to every later package.

### Naming — folder = repo = composer-name suffix

One string identifies a package everywhere, so the split tool needs **no mapping
table**:

```
packages/<X>  →  GitHub repo  microweber-packages/<X>  →  composer name  microweber-packages/<X>
```

The split tool derives the repo name from the **folder basename**, never from the
composer `name`'s own suffix (they are deliberately kept identical now, but the
folder basename is the source of truth). Pilot results:

| folder = repo (`microweber-packages/…`) | composer `name` | replaces | stable tag |
|---|---|---|---|
| `microweber-composer-installers` | `microweber-packages/microweber-composer-installers` | `composer/installers` | `v2.3.2` |
| `microweber-deps-arcanedev-support` | `microweber-packages/microweber-deps-arcanedev-support` | `arcanedev/support` | `v12.0.2` |
| `microweber-deps-arcanedev-php-html` | `microweber-packages/microweber-deps-arcanedev-php-html` | `arcanedev/php-html` | `v9.0.2` |
| `microweber-deps-laravel-repository` | `microweber-packages/microweber-deps-laravel-repository` | `torann/laravel-repository` | `v1.2.2` |

GitHub keeps redirects after a `gh repo rename`, so earlier pushes/tags survive a
rename — but the repo should still be *created* with the final name.

### Renamed fork of an upstream package → `replace` **and** a root require

When a package is renamed away from an upstream name that other packages still
require (e.g. our fork named `microweber-packages/…` but consumers `require
arcanedev/support`):

1. The fork declares `"replace": { "<upstream/name>": "*" }` — this satisfies any
   constraint on the upstream name, including a pinned one like `^1.0`.
2. **Something must actually require the fork** (we add it to the root
   `composer.json` `require`). A `replace` is inert unless its package is in the
   install set — without the root require, Composer ignores the replace and pulls
   the real upstream package from Packagist (it silently installed the ancient
   `arcanedev/support 1.0.0` until the root require was added). For a
   `composer-plugin` fork, also add the new name under `config.allow-plugins`.

### Versioning — tags only, never a baked field

Drop the `"version"` field. As a path package Composer derives the version from
the monorepo git branch (`*`/`@dev` requires match it); the published repo is
driven by git tags. A baked version overrides the tag, so a released tag would
serve stale content (this is exactly how the installers `v2.3.0` tag first
shipped pure-upstream content with the fork changes stranded on an untagged
`master`).

### Lock hygiene

Re-resolve with a **targeted** `composer update <pkg> <old-name>` (no `-W`) — `-W`
cascades unrelated patch bumps across the whole Laravel stack and pollutes the
commit. Verify the lock package-set diff is only the intended swap.

---

## 0. Decisions to make before any split (blockers)

These change the mechanics of everything below; settle them first.

- [ ] **Packagist strategy** — public Packagist org(s) vs a private Composer repo (Satis/Private Packagist). Who owns `microweber-packages`, `microweber-deps`, `modules`, templates vendor.
- [ ] **Vendor renaming** — `Modules/*` publish as `modules/*` and `Templates/*` as `nwidart/*`: **neither vendor is ours**. Decide final vendors (e.g. `microweber-modules/*`, `microweber-templates/*`) — renaming is a breaking change to every `require`, `installer-name`, and autoload reference.
- [ ] **Repo-per-package vs grouped repos** — 86 + 100 + 32 = **218 potential repos**. Decide granularity (one repo each, or grouped e.g. all modules in `microweber-modules` monorepo that itself publishes subtrees).
- [ ] **History policy** — carry per-file git history into each new repo (`git filter-repo` / `git subtree split`) or start fresh at a tag.
- [x] **Fork endgame** — **DONE** for `composer/installers`, `arcanedev/*` and `torann/*`: vendored as `packages/microweber-*`, renamed with `replace`, published to `microweber-packages/*` with stable tags (see the pilot table above). Remaining: register them on Packagist (owner), and apply the same pattern to any other `dev-*`/forked dep.
- [ ] **Branch/release model** — which branch is canonical (`master` vs `filament-5`), SemVer policy, and whether packages version independently or lockstep.

---

## 1. Per-package readiness (metadata hygiene) — applies to all 218

For every package/module/template, before it can live in its own repo:

- [ ] **Remove the hardcoded `"version"`** (82/86 packages, most modules). With VCS/Packagist the version comes from **git tags**; a baked `version` field fights tag resolution and goes stale.
- [ ] **Declare real dependencies.** Today many packages declare few/none (e.g. `microweber-live-edit` and `microweber-admin` declare **no** `microweber-packages/*` deps) because the monorepo autoloads everything. Standalone, their `use` statements will fatal. Extract the true dependency graph (static analysis of `use`/`namespace` + composer class-map) and write explicit `require` entries with version constraints.
- [ ] **Fix vendor/name** to the final decided vendor; keep `type` (`microweber-module` / `microweber-template`) and `extra.installer-name` so `composer/installers` still places them.
- [ ] **PSR-4 autoload** block correct and self-contained (no reliance on the root `composer.json` autoload map).
- [ ] **`Modules/HostingApi`** — add the missing `composer.json`.
- [ ] **Stability** — ensure each package can resolve at `minimum-stability: stable` once deps are tagged (drop the repo-wide `dev`).

---

## 2. Dependency graph extraction (the hard, high-risk part)

The monorepo hides the real DAG. We must make it explicit and acyclic.

- [ ] Build a **dependency extractor**: for each package, scan `use`/`new`/facade/`config()`/`view()` references, map class → owning package, emit the `require` set.
- [ ] **Detect cycles** between first-party packages (e.g. admin ↔ live-edit ↔ filament-theme). Cyclic deps can't be split into independently versioned repos — break them (extract shared contracts into a leaf package) or group them.
- [ ] Produce a **topological order** (leaves first: `package`, `laravel-helper-functions`, `config`, `url`, … → mid-tier → `admin`, `live-edit`, `app`). This is the publish order.
- [ ] Reconcile with **`packages/STANDALONE.md`** (currently 50/53 pass) — it already proves the leaf/mid tiers resolve standalone; extend the matrix to **all 86** (35 lack `orchestra/testbench` today).

---

## 3. Split mechanics / tooling to build

- [ ] **Split script** — per package: `git subtree split` (or `git filter-repo --path`) → push to its new repo, preserving history per the decision in §0.
- [ ] **Mirror/one-way sync option** — until the cutover, a script that pushes monorepo subtrees to the per-package repos read-only (so Packagist can tag) without yet flipping the root to depend on them.
- [ ] **Release automation** — tag + GitHub release per repo; webhook to Packagist. The `gh` CLI in this environment can create GitHub releases (confirmed) but **cannot publish to Packagist** — that needs Packagist credentials/webhooks (owner action).
- [ ] **CI matrix** — per-repo CI running that package's standalone test (reuse `scripts/standalone-test.sh` shape) on push/tag.
- [ ] **Root `composer.json` flip** — replace the 77 `path` repositories with Packagist/VCS requires at the published constraints; keep `path` repos available behind a flag for local dev (Composer `COMPOSER_` env or a `composer.local.json`).

---

## 4. Root application after the split

- [ ] `microweber/microweber` becomes a **thin application skeleton** (app bootstrap, config, `public/`, `storage/` scaffolding) that `require`s the published packages/modules/templates.
- [ ] Resolve how first-party **modules** are delivered: as Composer deps installed into `Modules/` via `composer/installers`, vs a separate module marketplace. Same question for templates → `Templates/`.
- [ ] Preserve the activator contracts (`storage/*_statuses.json`, `MODULES_ACTIVATOR`/`TEMPLATES_ACTIVATOR`) so installed-via-Composer modules still enable correctly (ties into the multisite `database` activator work).
- [ ] Local-dev workflow doc: how a contributor edits a package locally (path repo / symlink) while the app consumes the published version.

---

## 5. Forks & third-party pins to resolve

- [x] **`composer/installers`** — **DONE**. Was `vcs peter-mw/installers @ dev-master`; now the path package `microweber-packages/microweber-composer-installers` (keeps `MicroweberInstaller` for `microweber-module`→`Modules/`, `microweber-template`→`Templates/`), `replace: {composer/installers: *}`, published tag `v2.3.2`. Required directly by root + allow-listed under its new name.
- [x] **`arcanedev/*`** (2) and **`torann/*`** (1) — **DONE** (vendored stable forks with `replace`, published `v12.0.2`/`v9.0.2`/`v1.2.2`; see the pilot table). Remaining for all of the above: Packagist registration (owner).
- [ ] Audit `minimum-stability: dev` — every `dev-*` constraint must become a tagged stable range before a stable `microweber/microweber` release.
- [ ] **Security debt** — GitHub reports 204 Dependabot alerts (6 critical) on the default branch; a split is the moment to bump transitive deps, so fold a dependency-upgrade pass into the plan.

---

## 6. Suggested sequencing

1. **Decisions (§0)** — vendors, Packagist, granularity, history, forks.
2. **Metadata hygiene (§1)** across all packages — scriptable, low-risk, do in bulk.
3. **Dependency extraction + cycle-break (§2)** — gate; nothing publishes cleanly until the DAG is acyclic and declared.
4. **Resolve forks (§5)** — `composer/installers` first (modules/templates can't install without it).
5. **Tooling (§3)** — split + mirror + release + CI, proven on **leaf packages first** (`package`, `laravel-helper-functions`, `config`).
6. **Publish in topological order**, leaves → app. Each tier green in CI before the next.
7. **Flip the root app (§4)** to consume published versions; keep path-repo dev fallback.
8. **Cutover** — `master` tagged; `composer create-project microweber/microweber` verified end-to-end (reuse the Docker production image as the clean-room test).

---

## 7. Known risks (name them in PRs)

- **Under-declared deps** — the #1 hazard; the monorepo autoload masks missing `require`s. A package "passes" in-tree and fatals standalone. Mitigated by §2 + extending STANDALONE.md to all 86.
- **Dependency cycles** — may force grouping or a contracts-extraction refactor; discovered only once the DAG is built.
- **Vendor renames are breaking** — touch every `require`, installer-name, and namespace; do once, mechanically, with a contract test.
- **`dev-master` / baked `version`** — both block a stable release; easy to miss on individual packages.
- **Templates/modules placement** — depends entirely on the `composer/installers` fork; if that isn't stabilized, nothing lands in `Modules/`/`Templates/`.
- **Two-way drift** — during migration, edits must not fork between monorepo and split repos; use one-way mirror until cutover.

---

*Generated as a planning deliverable. Measurements are from the working tree on
`filament-5`; re-run the counts before acting as they will drift.*

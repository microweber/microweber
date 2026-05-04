# Troubleshooting

## 2026-05-04 — `/admin/login` returns 404 in local runtime

### Symptoms
- `curl -I http://127.0.0.1` returns `200 OK`.
- `curl -I http://127.0.0.1/admin/login` returns `404 Not Found`.
- Playwright navigation to `http://127.0.0.1/admin/login` shows Apache’s 404 page instead of the Laravel admin login.

### Likely Cause
- The local Apache-served runtime is not routing requests into the Laravel app for `/admin/*`, even though the host responds on the root URL.

### Impact
- Browser-based verification for admin and live-edit tasks is blocked or misleading in this environment.

### Current Workaround
- Use static/code verification and CLI-level checks when browser validation depends on `/admin/*`.
- Record the environment issue in task notes so UI failures are not misattributed to application regressions.

## 2026-05-04 — Full PHP suite needs split-process runner

### Symptoms
- Broad single-process test runs can fail or become unstable because PHP memory usage grows across many suites.

### Cause
- `run-tests.sh` documents PHP memory fragmentation/leak behavior during long suite execution.

### Fix / Preferred Command
- Use `./run-tests.sh` for broad repo validation.
- Use direct `php vendor/bin/phpunit <path>` or targeted suite runs for small changes.

## 2026-05-04 — Pest helper files are not active root entrypoints

### Symptoms
- Historical helper scripts exist under `docs/testing/` for a possible future Pest migration.
- The root repository uses `phpunit.xml`, `composer test`, and `./run-tests.sh` instead.

### Impact
- Contributors may assume the helper scripts are active root config unless the docs are explicit.

### Current Guidance
- Use `composer test`, targeted `php vendor/bin/phpunit ...`, or `./run-tests.sh`.
- Treat the `docs/testing/*pest*` helpers as scaffolding only unless Pest is intentionally reintroduced as a real root dependency.

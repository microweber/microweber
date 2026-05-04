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

## 2026-05-04 — Testing docs reference missing Pest root files

### Symptoms
- `CONTRIBUTING.md` and `docs/testing/*.md` mention `/Pest.php`, `/pest.xml`, and `composer test-pest`.
- Those root files/scripts are not currently present in the repo.

### Impact
- New contributors can be sent to missing commands or configuration files.

### Next Step
- Align the documented Pest entrypoints with the actual repository setup by either restoring the missing root config/scripts or correcting the docs.

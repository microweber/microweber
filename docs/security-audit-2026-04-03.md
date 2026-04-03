# Security Audit Report

**Date:** 2026-04-03
**Scope:** OWASP Top 10 review, dependency CVE scan, secret detection, security header check
**Branch:** filament-5
**Status:** Complete

---

## 1. Dependency CVE Scan

### Composer (PHP)

**Result:** 0 vulnerabilities found.

3 abandoned packages detected (no security impact):
- `doctrine/annotations` (no replacement)
- `graham-campbell/security-core` (suggested: `voku/anti-xss`)
- `inspector-apm/neuron-ai` (suggested: `neuron-core/neuron-ai`)

### npm (JavaScript)

**Result:** 10 vulnerabilities (5 low, 3 moderate, 2 high).

| Package | Severity | Issue | Fix Available |
|---------|----------|-------|---------------|
| elliptic | Low | Risky crypto implementation (GHSA-848j) | No (transitive via laravel-mix) |
| lodash <= 4.17.23 | High | Code injection via `_.template` (GHSA-r5fr), Prototype Pollution (GHSA-f23m) | Yes (`npm audit fix`) |
| picomatch <= 2.3.1 | High | Method injection (GHSA-3v7f), ReDoS (GHSA-c2c7) | Yes (`npm audit fix`) |
| webpack-dev-server <= 5.2.0 | Moderate | Source code theft in dev server (GHSA-9jgg, GHSA-4v9v) | No (dev dependency only) |

**Note:** All npm vulnerabilities are in build/dev tooling (laravel-mix chain). No runtime exposure in production.

---

## 2. Secret Detection

### Findings

| # | Severity | File | Issue |
|---|----------|------|-------|
| 1 | Medium | `docker-compose.yml` | Hardcoded DB passwords (`secret`, `root_secret`, `minioadmin123`) |
| 2 | Low | `.env.docker` | `DB_PASSWORD=secret`, `AWS_SECRET_ACCESS_KEY=minioadmin123` |
| 3 | Low | `.env` / `.env.dusk` | `APP_KEY` committed (development key) |

### Assessment

All hardcoded credentials are **development-only** values (`secret`, `minioadmin123`). No production secrets detected. The `docker-compose.prod.yml` correctly uses Docker Secrets via file references.

### Recommendations

- Move docker-compose credentials to a `.env.docker.example` template
- Add `.env` and `.env.docker` to `.gitignore` (`.env` may already be ignored depending on config)
- Rotate APP_KEY if this key was ever used in a non-local environment

---

## 3. Security Headers

**Test:** `curl -sI http://127.0.0.1:8199/`

| Header | Present | Value | Status |
|--------|---------|-------|--------|
| X-Powered-By | Yes | PHP/8.4.18 | Remove in production |
| Content-Type | Yes | text/html; charset=utf-8 | OK |
| Cache-Control | Yes | no-cache, private | OK |
| Set-Cookie (XSRF-TOKEN) | Yes | samesite=lax | OK |
| Set-Cookie (laravel_session) | Yes | httponly; samesite=lax | OK |
| Content-Security-Policy | No | - | Missing |
| Strict-Transport-Security | No | - | Missing |
| X-Content-Type-Options | No | - | Missing |
| X-Frame-Options | No | - | Missing |
| Referrer-Policy | No | - | Missing |
| Permissions-Policy | No | - | Missing |

**Assessment:** Session cookies have proper flags (httponly, samesite). But 6 security headers are missing and `X-Powered-By` leaks server version.

---

## 4. OWASP Top 10 Code Review

### Critical Findings

| # | Category | Severity | File | Lines | Issue | CWE |
|---|----------|----------|------|-------|-------|-----|
| 1 | A08 Data Integrity | Critical | `ProcessQueueController.php` | 32 | `@unserialize()` on queue payload | CWE-502 |
| 2 | A08 Data Integrity | Critical | `FieldsManager.php` | 675 | `unserialize()` on custom field values | CWE-502 |
| 3 | A08 Data Integrity | Critical | `UrlManager.php` | 214-215 | `unserialize(base64_decode($param))` from URL | CWE-502 |
| 4 | A08 Data Integrity | Critical | `Format.php`, `OptionManager.php`, `CacheFileHandler.php` | Various | Multiple `unserialize()` calls | CWE-502 |

### High Findings

| # | Category | Severity | File | Lines | Issue | CWE |
|---|----------|----------|------|-------|-------|-----|
| 5 | A02 Crypto | High | `UserForgotPasswordController.php` | 104, 107 | MD5 for password reset tokens | CWE-327 |
| 6 | A03 Injection | High | `FilterByPriceTrait.php` | 37-38, 77-78 | `whereRaw()` with string interpolation | CWE-89 |
| 7 | A03 Injection | High | `FilterByQtyTrait.php` | 33, 35, 37 | `whereRaw()` with string concatenation | CWE-89 |
| 8 | A01 Access | High | `Admin.php` middleware | 49-82 | Allows access when no admins exist | CWE-862 |

### Medium Findings

| # | Category | Severity | File | Lines | Issue | CWE |
|---|----------|----------|------|-------|-------|-----|
| 9 | A04 Design | Medium-High | `MediaManager.php` | 166-198 | File upload validates extension only, not MIME | CWE-434 |
| 10 | A01 Access | Medium | `UserManager.php` | 131-161 | Login rate limiting shows count but doesn't lock out | CWE-307 |
| 11 | A07 Auth | Medium | `UserVerifyController.php` | Various | SHA1 for email verification hash | CWE-327 |
| 12 | A03 Injection | Medium | Comment blade templates | Various | `{!! !!}` output after markdown rendering | CWE-79 |
| 13 | A10 SSRF | Medium | `MultilanguageApiController.php` | 80 | `file_get_contents($url)` on user-provided URL | CWE-918 |

### Low Findings

| # | Category | Severity | Issue |
|---|----------|----------|-------|
| 14 | A05 Config | Low | `APP_DEBUG=true` in `.env` (development expected) |
| 15 | A05 Config | Low | `X-Powered-By` header exposes PHP version |

---

## 5. Summary

| Category | Critical | High | Medium | Low | Total |
|----------|----------|------|--------|-----|-------|
| A01 Broken Access Control | 0 | 1 | 1 | 0 | 2 |
| A02 Cryptographic Failures | 0 | 1 | 1 | 0 | 2 |
| A03 Injection (SQL/XSS) | 0 | 2 | 1 | 0 | 3 |
| A04 Insecure Design | 0 | 0 | 1 | 0 | 1 |
| A05 Security Misconfiguration | 0 | 0 | 0 | 2 | 2 |
| A08 Data Integrity (unserialize) | 4 | 0 | 0 | 0 | 4 |
| A10 SSRF | 0 | 0 | 1 | 0 | 1 |
| Dependencies (npm) | 0 | 2 | 3 | 5 | 10 |
| Secrets | 0 | 0 | 1 | 2 | 3 |
| Headers | 0 | 0 | 6 | 1 | 7 |
| **Total** | **4** | **6** | **15** | **10** | **35** |

---

## 6. Remediation Priority

### Immediate (Critical)

1. Replace all `unserialize()` calls with `json_decode()` or add `['allowed_classes' => []]` parameter
2. Parameterize all `whereRaw()` queries using `?` placeholders

### Short-term (High)

3. Replace MD5 password reset tokens with `hash('sha256', $token)` or Laravel's built-in reset
4. Add MIME type validation to file uploads
5. Fix admin middleware to not allow access when no admins exist (setup wizard should handle this)
6. Run `npm audit fix` to patch lodash and picomatch

### Medium-term

7. Add security headers middleware (CSP, HSTS, X-Content-Type-Options, X-Frame-Options)
8. Implement proper login lockout (not just warning messages)
9. Remove `X-Powered-By` header in production
10. Migrate SHA1 email verification to SHA256
11. Audit all `file_get_contents()` calls for SSRF

### Long-term

12. Replace abandoned `graham-campbell/security-core` with `voku/anti-xss`
13. Add `php artisan view:cache` to CI for Blade component validation
14. Consider migrating from laravel-mix to Vite to eliminate transitive npm vulnerabilities

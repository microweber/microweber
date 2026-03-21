## Reporting a Vulnerability

Please report suspected security vulnerabilities to
**[admin@microweber.com](mailto:admin@microweber.com)**. You will receive a response from
us within 48 hours. If the issue is confirmed, we will release a patch as soon
as possible depending on complexity, but normally within a few days.

---

## Security Policy

Microweber CMS includes comprehensive automated security scanning to detect vulnerabilities in dependencies and source code.

### Automated Security Scanning

Security scans run automatically via GitHub Actions on:
- Every push to `main`, `develop`, or `master` branches
- Every pull request to these branches
- Daily at midnight UTC (scheduled scans)

### Security Tools Used

1. **Composer Audit** - Scans PHP dependencies for known vulnerabilities
2. **NPM Audit** - Scans JavaScript dependencies for known vulnerabilities
3. **PHP Security Checker** - Uses local-php-security-checker for additional PHP dependency scanning
4. **Semgrep** - Static analysis for security vulnerabilities in PHP and JavaScript code
5. **Trivy** - Filesystem security scanner for vulnerabilities and misconfigurations
6. **GitHub Security Advisories** - Checks for known security advisories

### Running Security Scans Locally

#### PHP Dependencies

```bash
# Run Composer security audit
composer run security:audit

# Generate JSON report
composer run security:audit-json

# Check for outdated packages
composer run security:outdated

# Run full security scan
composer run security:full-scan
```

#### NPM Dependencies

```bash
# Run NPM security audit
npm run security:audit

# Generate JSON report
npm run security:audit-json

# Fix automatically fixable issues
npm run security:fix
```

#### Combined Security Check

```bash
# Run all security checks
composer run security:check
```

### Security Configuration Files

- `.trivy.yml` - Trivy scanner configuration
- `.semgrep.yml` - Semgrep static analysis rules
- `.github/workflows/security-scan.yml` - GitHub Actions security workflow

### Security Best Practices

1. **Keep dependencies updated** - Regularly run `composer update` and `npm update`
2. **Review security scans** - Check security scan results in GitHub Actions
3. **Use environment variables** - Never hardcode credentials in source code
4. **Validate user input** - Always sanitize and validate user input
5. **Use parameterized queries** - Prevent SQL injection by using Eloquent or parameterized queries
6. **Enable CSRF protection** - Ensure all forms include CSRF tokens

### Security Scan Results

Security scan artifacts are uploaded to GitHub Actions and available for 30 days:
- `npm-audit-results.json` - NPM audit results
- `php-security-checker-results.json` - PHP security checker results
- `trivy-results.sarif` - Trivy scan results
- `outdated-packages.json` - Outdated package report

### Supported Versions

Security updates are provided for:
- Latest stable release
- Previous major version (for 6 months after new major release)

### Security Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [CWE Top 25](https://cwe.mitre.org/top25/)
- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [Laravel Security](https://laravel.com/docs/11.x/authentication)

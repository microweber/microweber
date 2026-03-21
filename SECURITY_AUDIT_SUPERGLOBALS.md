# Superglobal Remediation Audit Report

## Overview
This document tracks the remediation of PHP superglobal usage (`$_GET`, `$_POST`, `$_REQUEST`) across the Microweber codebase to improve security by leveraging Laravel's Request facade with proper validation and sanitization.

## Statistics
- **Total superglobal usages found:** 174
- **Production code (src/ + Modules/):** 141
- **Test files:** 33 (excluded from remediation as they intentionally manipulate request data)
- **View files (.blade.php):** Excluded from direct remediation, use `request()` helper instead

## Risk Assessment

### Critical Risk Files (Open Redirect, Path Traversal, Unvalidated Input)
1. **PluploadController.php** (21 usages) - File upload controller with path handling
2. **UserManager.php** (11 usages) - Authentication with redirect parameters - ✅ FIXED
3. **ApiController.php** (14 usages) - API endpoint handling
4. **ModuleController.php** (45 usages) - Module rendering with request manipulation

### High Risk Files
5. **ContentManagerHelpers.php** (5 usages) - Content management helpers
6. **FrontendController.php** (5 usages) - Frontend request handling
7. **JsCompileController.php** (4 usages) - JavaScript compilation
8. **UpdaterController.php** (4 usages) - Update handling
9. **MediaManager.php** (4 usages) - Media file handling

### Medium Risk Files
10. **Helper functions** (common.php, other.php) - Global helper functions with redirects

## Remediation Pattern

### Before:
```php
$redirect = $_GET['redirect'] ?? false;
$value = $_POST['field'] ?? '';
$data = array_merge($_GET, $_POST);
```

### After:
```php
$redirect = request()->input('redirect', false);
$value = request()->input('field', '');
$data = request()->all();
```

### Security Improvements:
1. **Open Redirect Protection**: Validate redirect URLs against allowed domains
2. **Path Traversal Prevention**: Use Laravel's path validation
3. **Input Sanitization**: Request facade automatically escapes input
4. **Type Safety**: Use typed inputs with defaults

## Files Remediated

### Completed
1. ✅ **src/MicroweberPackages/User/Services/UserManager.php**
   - Fixed `logout()` method: Replaced `$_GET['redirect']` and `$_GET['redirect_to']`
   - Fixed `codeLogin()` method: Replaced `$_GET['code_login']`, added validation
   - Added open redirect protection for `http_redirect` parameter
   - Fixed `social_login_process()` method: Replaced `$_REQUEST['provider']` usage

## Remaining Work

### High Priority (Security Critical)
- [ ] Modules/FileManager/Http/Controllers/PluploadController.php (21 usages)
- [ ] src/MicroweberPackages/App/Http/Controllers/ApiController.php (14 usages)
- [ ] src/MicroweberPackages/Module/Http/Controllers/ModuleController.php (45 usages)
- [ ] src/MicroweberPackages/App/Http/Controllers/FrontendController.php (5 usages)
- [ ] Modules/Content/Support/ContentManagerHelpers.php (5 usages)

### Medium Priority
- [ ] src/MicroweberPackages/App/Http/Controllers/JsCompileController.php (4 usages)
- [ ] Modules/Updater/Http/Controllers/UpdaterController.php (4 usages)
- [ ] Modules/Media/Repositories/MediaManager.php (4 usages)
- [ ] src/MicroweberPackages/App/functions/common.php (2 usages)
- [ ] src/MicroweberPackages/App/functions/other.php (3 usages)

### Low Priority (Helper functions and edge cases)
- [ ] src/MicroweberPackages/Helper/UrlManager.php (3 usages)
- [ ] src/MicroweberPackages/Database/DatabaseManager.php (3 usages)
- [ ] Remaining 50+ files with 1-2 usages each

## Migration Strategy

### Phase 1: Security Critical Files (This PR)
Focus on files that handle:
- User authentication redirects
- File uploads with user-provided paths
- API endpoints with raw request data

### Phase 2: Core Controllers
- ModuleController
- ApiController
- FrontendController

### Phase 3: Helper Functions and Modules
- Global helper functions
- Module-specific controllers
- Template adapters

### Phase 4: Testing and Validation
- Run full test suite
- Security penetration testing
- Performance regression testing

## Testing

After remediation, run:
```bash
# PHP syntax validation
php -l <file>

# Run affected test suites
./vendor/bin/phpunit tests/Feature/User
./vendor/bin/phpunit tests/Feature/FileManager

# Static analysis
./vendor/bin/phpstan analyse --level=5
```

## References

- Laravel Request Documentation: https://laravel.com/docs/11.x/requests
- OWASP Input Validation Cheat Sheet: https://cheatsheetseries.owasp.org/cheatsheets/Input_Validation_Cheat_Sheet.html
- Laravel Security Best Practices: https://laravel.com/docs/11.x/security

## Commits

1. `d0b591b88c` - refactor: Remediate superglobals in UserManager.php

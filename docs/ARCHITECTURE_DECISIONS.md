# Architecture Decision Records (ADRs)

> **Document Status:** Complete  
> **Last Updated:** 2026-03-23  
> **Scope:** Key architectural decisions for Microweber CMS

---

## Table of Contents

1. [ADR-001: Multi-Panel Architecture (Filament v5)](#adr-001-multi-panel-architecture-filament-v5)
2. [ADR-002: Modular System Architecture](#adr-002-modular-system-architecture)
3. [ADR-003: Repository Pattern for Data Access](#adr-003-repository-pattern-for-data-access)
4. [ADR-004: Service-Oriented Cart Management](#adr-004-service-oriented-cart-management)
5. [ADR-005: Payment Gateway Abstraction](#adr-005-payment-gateway-abstraction)
6. [ADR-006: Tax Calculation Engine](#adr-006-tax-calculation-engine)
7. [ADR-007: Multi-Language Content Support](#adr-007-multi-language-content-support)
8. [ADR-008: Advanced Caching Strategy](#adr-008-advanced-caching-strategy)
9. [ADR-009: Security-First Request Handling](#adr-009-security-first-request-handling)
10. [ADR-010: AI Integration Architecture](#adr-010-ai-integration-architecture)

---

## ADR-001: Multi-Panel Architecture (Filament v5)

**Status:** ✅ Accepted (2026-03-04)  
**Decision Maker:** Senior Developer / Tech Lead  
**Context:** Migration from Filament v3 to v5

### Context

Microweber uses Filament for admin interfaces. The system had multiple user contexts:
- Super admins (full admin panel)
- Billing admins (subscription management)
- Marketing team (newsletter campaigns)
- Customers (billing frontend)
- Authenticated users (profile management)
- Shoppers (checkout flow)

### Decision

**Retain multi-panel architecture** rather than consolidating into a single panel.

### Rationale

| Approach | Pros | Cons |
|----------|------|------|
| **Single Panel** | Simpler initially, one auth stack | Loss of context isolation, mixed resources, complex permissions, accidental exposure risk |
| **Multi-Panel** ✅ | Clear separation, module isolation, different themes, better security | More files, more configuration |

### Implementation

```
Panel Registry (Filament v5)
│
├── Admin Panel (/admin) - Super admins
├── Admin Billing (/admin/billing) - Billing admins
├── Admin Newsletter (/admin/newsletter) - Marketing team
├── Customer Billing (/billing) - Customers
├── User Profile (/profile) - Authenticated users
└── Checkout (/checkout) - Shoppers
```

### Consequences

- **Positive:** Clear security boundaries, panel-specific theming, isolated resource loading
- **Negative:** More files to maintain, need to keep registrations in sync

### Migration Notes

- 7 panel providers migrated to v5
- 35+ resources updated to `form(Schema $schema): Schema` pattern
- 328+ test files migrated to `#[Test]` attributes

---

## ADR-002: Modular System Architecture

**Status:** ✅ Accepted  
**Decision Maker:** Development Team  
**Context:** Application organization

### Context

Microweber is a CMS with diverse features (content, e-commerce, marketing, AI). Need flexible organization that allows:
- Feature isolation
- Independent development
- Selective loading
- Third-party extensions

### Decision

**Implement Laravel-based modular architecture** with self-contained modules.

### Structure

```
Modules/
├── ModuleName/
│   ├── composer.json           # PSR-4 autoloading
│   ├── module.json             # Module metadata
│   ├── Providers/              # Service providers
│   ├── Models/                 # Eloquent models
│   ├── Filament/               # Admin resources
│   ├── database/migrations/    # Schema changes
│   ├── resources/views/        # Frontend templates
│   ├── config/                 # Configuration
│   └── Tests/                  # Module tests
```

### Key Components

1. **BaseModuleServiceProvider**: Handles registration, views, translations
2. **FilamentRegistry**: Dynamic resource registration
3. **Module.json**: Metadata for discovery

### Consequences

- **Positive:** Clear boundaries, independent testing, selective loading
- **Negative:** Cross-module dependencies require careful management

---

## ADR-003: Repository Pattern for Data Access

**Status:** ✅ Accepted  
**Decision Maker:** Development Team  
**Context:** Data layer organization

### Context

Core managers (ContentManager, UserManager, CategoryManager) were growing too large, mixing business logic with data access. Need separation of concerns.

### Decision

**Adopt Repository Pattern** with the following layers:

```
Controller/Service
    ↓
Repository (data access)
    ↓
Eloquent Model
    ↓
Database
```

### Implementation

- **Repositories**: Handle CRUD, queries, filtering
- **Services**: Business logic, calculations
- **Managers**: High-level operations, coordination

### Example

```php
// ContentRepository handles data access
$content = ContentRepository::findById($id);

// ContentService handles business logic
$published = ContentService::publish($content);

// ContentManager coordinates operations
ContentManager::save($data);
```

### Consequences

- **Positive:** Testable, swappable implementations, clear contracts
- **Negative:** Additional abstraction layer

---

## ADR-004: Service-Oriented Cart Management

**Status:** ✅ Accepted (2026-03-21)  
**Decision Maker:** Senior Developer  
**Context:** CartManager refactoring

### Context

CartManager was 1,328 lines with 26+ public methods, handling:
- Cart operations
- Discounts/coupons
- Tax calculations
- Order recovery

This violated Single Responsibility Principle.

### Decision

**Split CartManager into focused services** with CartManager as a backward-compatible facade.

### Architecture

```
CartManager (Facade - 190 lines)
    ├── CartService (Core operations)
    ├── CartTotalsService (Calculations)
    └── CartCouponService (Discounts)
```

### Services

| Service | Responsibility | Methods |
|---------|---------------|---------|
| CartService | get, add, update, remove, empty | 5 |
| CartTotalsService | sum, totals, tax, discount calculation | 8 |
| CartCouponService | apply, validate, consume coupons | 6 |

### Consequences

- **Positive:** 86% reduction in CartManager size, testable units, clear boundaries
- **Negative:** Maintaining backward compatibility in facade

---

## ADR-005: Payment Gateway Abstraction

**Status:** ✅ Accepted (2026-03-21)  
**Decision Maker:** Development Team  
**Context:** Payment processing

### Context

Need to support multiple payment providers (Stripe, PayPal) with:
- Webhook handling
- Secure signature verification
- Refund processing
- Subscription billing

### Decision

**Driver-based payment abstraction** with webhook controllers per provider.

### Architecture

```
PaymentMethodManager
    ├── PaymentMethod (base)
    ├── StripeDriver
    └── PayPalDriver

Webhook Controllers
    ├── StripeWebhookController
    └── PayPalWebhookController
```

### Security

- Webhook routes disable CSRF (stateless)
- Signature verification for all webhooks
- Idempotency key support

### Consequences

- **Positive:** Easy to add new providers, consistent API
- **Negative:** Must maintain webhook security per provider

---

## ADR-006: Tax Calculation Engine

**Status:** ✅ Accepted (2026-03-21)  
**Decision Maker:** Development Team  
**Context:** E-commerce taxation

### Context

Need flexible tax calculation supporting:
- Location-based rules (country/state/city/ZIP)
- Percentage and fixed amounts
- Compound taxes (tax on tax)
- Date-based validity
- Priority-based selection

### Decision

**TaxCalculator service** with location-based rule matching.

### Architecture

```
TaxCalculator
    ├── TaxRule (model)
    ├── Location scoring (specificity-based)
    └── Compound calculation support

Integration
    └── CartTotalsService (automatic calculation)
```

### Features

- ZIP pattern matching with wildcards (e.g., "100*")
- Priority scoring for most specific match
- Backward compatibility with legacy TaxType

### Consequences

- **Positive:** Flexible, location-aware, supports complex scenarios
- **Negative:** Requires comprehensive test coverage for edge cases

---

## ADR-007: Multi-Language Content Support

**Status:** ✅ Accepted (2026-03-22)  
**Decision Maker:** Development Team  
**Context:** Internationalization

### Context

Need full multilingual support:
- Content translations
- Translation management UI
- Frontend locale switching
- URL-based locale detection

### Decision

**Dual approach**: Content translations + Laravel localization

### Architecture

```
Content Layer
    ├── Content translations (database)
    └── Locale-specific content

Translation Management
    ├── TranslationResource (Filament)
    ├── Quick Translate actions
    └── Bulk operations

Frontend
    ├── LocaleSwitcher (Livewire component)
    ├── URL-based detection
    └── multilanguage_url() helper
```

### Consequences

- **Positive:** Flexible, supports both content and UI translations
- **Negative:** Database growth with translations

---

## ADR-008: Advanced Caching Strategy

**Status:** ✅ Accepted (2026-03-22)  
**Decision Maker:** Development Team  
**Context:** Performance optimization

### Context

Need caching at multiple levels:
- Full page cache
- Fragment/partial cache
- Query result cache
- Support for Redis/Memcached

### Decision

**Multi-tier caching** with tag-based invalidation.

### Architecture

```
PageCacheService
    ├── Full page caching
    ├── Mobile/desktop variants
    ├── User role support
    └── Tag-based invalidation

FragmentCacheService
    ├── Partial content caching
    ├── Component-level caching
    └── Remember patterns
```

### Features

- Cache warming (`php artisan cache:warm`)
- Statistics tracking
- Smart invalidation on content changes
- Multiple driver support (Redis, Memcached, Array)

### Consequences

- **Positive:** Significant performance improvement, flexible invalidation
- **Negative:** Cache complexity, requires monitoring

---

## ADR-009: Security-First Request Handling

**Status:** ✅ Accepted (2026-03-21)  
**Decision Maker:** Development Team  
**Context:** Security remediation

### Context

Found 260 superglobal usages (`$_GET`, `$_POST`, `$_REQUEST`) in 34 files. Direct superglobal access:
- Bypasses Laravel's request validation
- Creates injection vulnerabilities
- Makes testing difficult

### Decision

**Systematic remediation** using Laravel's Request facade.

### Approach

```
BEFORE:
$param = $_GET['param']; // ❌ Insecure

AFTER:
$param = request()->input('param'); // ✅ Safe
```

### Remediation Results

| File | Superglobals Before | After |
|------|---------------------|-------|
| ModuleController.php | 45 | 0 |
| PluploadController.php | 21 | 0 |
| ApiController.php | 14 | 0 |
| UserManager.php | 11 | 0 |
| FrontendController.php | 5 | 0 |
| ContentManagerHelpers.php | 5 | 0 |

**Total: 90 superglobal usages remediated**

### Additional Security Measures

- CSRF audit: All 90+ forms protected
- File upload validation: MIME type, size limits
- SQL injection prevention: Parameterized queries
- Path traversal protection: Validated template paths

### Consequences

- **Positive:** Significantly improved security posture
- **Negative:** Large-scale refactoring effort

---

## ADR-010: AI Integration Architecture

**Status:** ✅ Accepted (2026-03-22)  
**Decision Maker:** Development Team  
**Context:** AI features

### Context

Need to integrate AI capabilities:
- Chat-based agents
- Content generation tools
- SEO metadata generation
- RAG (Retrieval-Augmented Generation)

### Decision

**NeuronAI-based workflow architecture** with tool-based extensibility.

### Architecture

```
AI Module
    ├── Agents (workflow orchestrators)
    ├── Tools (reusable capabilities)
    │   ├── GenerateDescriptionTool
    │   ├── GenerateSeoMetadataTool
    │   └── ContentImprovementTool
    ├── Drivers (OpenAI, etc.)
    └── Chat (conversation management)

Integration
    ├── Filament resources
    ├── Frontend components
    └── API endpoints
```

### Key Components

1. **BaseAgent**: Workflow orchestration
2. **AbstractContentTool**: Content manipulation base
3. **AgentChat**: Conversation management
4. **ProgressEvent**: Async progress tracking

### Consequences

- **Positive:** Flexible, extensible AI capabilities
- **Negative:** External API dependencies, rate limits

---

## Decision Summary

| ADR | Decision | Status | Impact |
|-----|----------|--------|--------|
| 001 | Multi-Panel Architecture | ✅ Accepted | High - User experience |
| 002 | Modular System | ✅ Accepted | High - Maintainability |
| 003 | Repository Pattern | ✅ Accepted | Medium - Code quality |
| 004 | Service-Oriented Cart | ✅ Accepted | High - Performance |
| 005 | Payment Abstraction | ✅ Accepted | High - Flexibility |
| 006 | Tax Engine | ✅ Accepted | Medium - Compliance |
| 007 | Multi-Language | ✅ Accepted | High - Reach |
| 008 | Advanced Caching | ✅ Accepted | High - Performance |
| 009 | Security Remediation | ✅ Accepted | Critical - Security |
| 010 | AI Integration | ✅ Accepted | High - Innovation |

---

## Document History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-03-23 | Initial ADR document with 10 key decisions |

---

**Next Steps:**
- Review ADRs quarterly
- Add new ADRs for future architectural changes
- Reference ADRs in code comments where applicable

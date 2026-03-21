# Product Scope: Microweber CMS

## Vision

Microweber is a next-generation, drag-and-drop Content Management System (CMS) and website builder that empowers users to create stunning websites, blogs, and online stores without requiring any technical expertise. Built on Laravel 11 with a modern Filament v5 admin interface, Microweber combines the power of enterprise-grade PHP framework with intuitive visual editing capabilities.

**Core Value Proposition**: Enable anyone to build professional websites with 450+ pre-designed layouts and 75+ modules through an intuitive visual interface, while providing developers with a flexible, extensible platform.

---

## Target Users

### Primary Users
1. **Small Business Owners** - Need professional websites without hiring developers
2. **E-commerce Entrepreneurs** - Want to launch online stores quickly with built-in payment processing
3. **Content Creators/Bloggers** - Need blogs with rich media support and SEO optimization
4. **Marketing Teams** - Require landing pages, newsletters, and campaign management tools

### Secondary Users
1. **Web Agencies** - Use white-label features to deploy client sites
2. **Hosting Companies** - Integrate via Plesk/cPanel plugins for customer onboarding
3. **Developers** - Extend functionality via modular architecture and API

---

## Features

### In Scope (Current Phase)

#### Core CMS Features
- **Visual Drag-and-Drop Editor** - Real-time WYSIWYG editing with 450+ pre-designed layouts
- **Multi-Panel Admin Interface** - Filament v5-based admin with role-based access control
- **Content Management** - Pages, posts, products with categories and tags
- **Template System** - 40+ responsive templates with live preview
- **Media Library** - Image upload, optimization, and CDN integration
- **Multi-language Support** - Full i18n with translation management

#### E-commerce Features
- **Product Management** - Unlimited products with variants and custom fields
- **Shopping Cart** - Persistent cart with coupon/discount support
- **Checkout Flow** - Multi-step checkout with guest checkout option
- **Payment Integration** - 10+ payment gateways (Stripe, PayPal, Authorize.net, Mollie, etc.)
- **Order Management** - Order tracking, invoices, customer communication
- **Tax & Shipping** - Configurable tax rules and shipping methods

#### Module System (92 Modules)
- **Content Modules** - Blog, FAQ, Testimonials, Team, Gallery
- **E-commerce Modules** - Cart, Product, Checkout, Coupons, Taxes
- **Marketing Modules** - Newsletter, Google Analytics, Social Links
- **Utility Modules** - Contact Forms, Search, Sitemap, Backup
- **Integration Modules** - AI Chat, File Manager, Media Library

#### User Management
- **Authentication** - Laravel Fortify with social login (Google, Facebook, etc.)
- **Role-Based Access** - Admin, Editor, Customer roles via Spatie Permissions
- **User Profiles** - Self-service profile management
- **API Access** - Laravel Sanctum for token-based API authentication

#### Technical Features
- **Modern Stack** - Laravel 11, Filament v5, Livewire v4, Tailwind v4, PHP 8.3+
- **Database Support** - MySQL, PostgreSQL, SQLite, SQL Server
- **File Storage** - Local, S3, and CDN support via Laravel Storage
- **Caching** - Multi-level caching with Redis support
- **Queue System** - Background job processing for emails, exports
- **Asset Management** - Webpack/Vite build pipeline with SCSS support

### Out of Scope (Future Phases)

- **Multi-tenancy** - Single codebase, multiple isolated tenants
- **Mobile App** - Native iOS/Android applications
- **Advanced AI Features** - AI content generation beyond current integrations
- **Real-time Collaboration** - Multi-user simultaneous editing
- **Headless CMS Mode** - API-only usage without frontend
- **Marketplace** - Third-party module marketplace (internal marketplace exists)

---

## Technical Architecture

### Technology Stack

| Layer | Technology | Version |
|-------|------------|---------|
| **Framework** | Laravel | 11.x |
| **PHP** | PHP | 8.3+ |
| **Admin Panel** | Filament | v5.x |
| **Frontend** | Livewire | v4.x |
| **Styling** | Tailwind CSS | v4.x |
| **Database** | MySQL/PostgreSQL/SQLite | 8.0/14/3+ |
| **Cache** | Redis | 6.0+ |
| **Queue** | Database/Redis | - |
| **Build Tool** | Vite | 5.x |

### Key Components

#### Panel Architecture (Multi-Panel)
```
┌─────────────────────────────────────────────────────────┐
│                    Filament Registry                     │
└─────────────────────────────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
   ┌────▼────┐      ┌──────▼──────┐     ┌────▼────┐
   │  Admin  │      │   Billing   │     │ Customer│
   │ (main)  │      │  (admin)    │     │ Panels  │
   └────┬────┘      └──────┬──────┘     └────┬────┘
        │                  │                  │
   • Dashboard         • Subscriptions    • Profile
   • Content           • Invoices         • Checkout
   • Shop              • Payments         • Billing
   • Settings          • Reports          • Orders
```

**Panel Types:**
1. **Admin Panel** (`/admin`) - Full administrative access
2. **Billing Admin** (`/admin/billing`) - Billing management
3. **Newsletter Admin** (`/admin/newsletter`) - Marketing campaigns
4. **Customer Billing** (`/billing`) - Customer subscription management
5. **Profile** (`/profile`) - User account settings
6. **Checkout** (`/checkout`) - Public checkout flow

#### Module System
- **Registration**: Auto-discovery via `module.json` in `Modules/` directory
- **Architecture**: Laravel Modules package with service providers
- **Filament Integration**: Automatic resource/page/widget discovery
- **Migrations**: Module-specific database migrations
- **Assets**: Module-specific CSS/JS assets

#### Data Layer
- **Eloquent Models** - Standard Laravel ORM with relationships
- **Repository Pattern** - Abstracted data access layer
- **Media Handling** - Spatie Laravel Media Library for file uploads
- **Permissions** - Spatie Laravel Permission for RBAC
- **Settings** - Key-value configuration storage

### Data Models (Key Entities)

| Entity | Description | Key Relationships |
|--------|-------------|-------------------|
| **User** | Platform users | Has roles, owns content/orders |
| **Content** | Pages, posts, products | Belongs to user, has categories |
| **Product** | E-commerce products | Has variants, media, categories |
| **Cart** | Shopping cart | Has items, belongs to session/user |
| **Order** | Purchase records | Has items, customer, payments |
| **Category** | Taxonomy | Hierarchical, polymorphic |
| **Media** | Uploaded files | Polymorphic to content/products |
| **Template** | Theme files | Has layouts, styles, settings |
| **Module** | Extension modules | Has settings, migrations |
| **Settings** | Configuration | Key-value pairs per module |

### External Integrations

#### Payment Gateways
- Stripe (Credit Cards)
- PayPal (Express Checkout)
- Authorize.net
- Mollie (EU)
- Przelewy24 (Poland)
- MTN Mobile Money (Africa)

#### Marketing & Analytics
- Google Analytics 4
- Google Tag Manager
- Facebook Pixel
- MailerLite API
- OpenAI (AI Chat)

#### Storage & CDN
- AWS S3
- Cloudflare R2
- DigitalOcean Spaces

#### Social Authentication
- Google OAuth
- Facebook Login
- Twitter/X OAuth
- GitHub OAuth

---

## Requirements

### Functional Requirements

1. **User Registration & Authentication**
   - Email/password registration with verification
   - Social login integration (OAuth 2.0)
   - Password reset functionality
   - Session management with remember me

2. **Content Management**
   - Create/edit/delete pages with WYSIWYG editor
   - Blog post management with categories/tags
   - Product catalog with variants and inventory
   - Media library with drag-and-drop upload

3. **E-commerce Operations**
   - Add/remove/update cart items
   - Apply coupon codes with validation
   - Calculate taxes based on location
   - Process payments via multiple gateways
   - Generate invoices and send email confirmations

4. **Administration**
   - User management with role assignment
   - Module installation/activation
   - System settings configuration
   - Backup and restore functionality

5. **Template System**
   - Install templates from marketplace
   - Customize colors, fonts, layouts
   - Live preview before publishing
   - Responsive design across devices

### Non-Functional Requirements

#### Performance
- **Page Load Time** - < 2 seconds for admin panel
- **API Response Time** - < 500ms for standard operations
- **Database Query Time** - < 100ms for indexed queries
- **Concurrent Users** - Support 100+ simultaneous admin users
- **Caching** - Aggressive caching with Redis for frequently accessed data

#### Security
- **Authentication** - Laravel Fortify with CSRF protection
- **Authorization** - RBAC with Spatie Permissions
- **Data Validation** - Server-side validation on all inputs
- **SQL Injection** - Parameterized queries only
- **XSS Protection** - Blade escaping and HTML sanitization
- **File Upload** - Mime type validation and path traversal protection
- **API Security** - Rate limiting and token-based auth
- **Secrets Management** - Environment-based configuration

#### Scalability
- **Horizontal Scaling** - Stateless application design
- **Database** - Support for read replicas
- **Asset Delivery** - CDN integration for static files
- **Queue Processing** - Background jobs for heavy operations
- **Caching Strategy** - Multi-tier caching (application, database, page)

#### Reliability
- **Uptime Target** - 99.9% availability
- **Backup** - Automated daily backups
- **Error Handling** - Graceful degradation with user-friendly messages
- **Logging** - Comprehensive application and error logging

#### Accessibility
- **WCAG 2.1** - Level AA compliance
- **Keyboard Navigation** - Full keyboard support
- **Screen Readers** - ARIA labels and semantic HTML
- **Color Contrast** - Minimum 4.5:1 ratio
- **Focus Indicators** - Visible focus states

---

## Constraints

### Technical Constraints
1. **PHP Version** - Must support PHP 8.3+ only
2. **Laravel Version** - Locked to Laravel 11.x for this phase
3. **Filament Version** - Must use Filament v5 patterns (Schema-based forms)
4. **Browser Support** - Modern browsers (Chrome, Firefox, Safari, Edge - last 2 versions)
5. **Database** - Must work with MySQL 8.0+, PostgreSQL 14+, SQLite 3+

### Business Constraints
1. **Backward Compatibility** - Must maintain API compatibility with v1.x
2. **White Label** - Support custom branding for hosting partners
3. **Open Source** - Core must remain MIT licensed
4. **Upgrade Path** - Smooth migration from Microweber 1.x/2.0

### Timeline Constraints
1. **MVP Release** - Core functionality stable
2. **Module Ecosystem** - 90+ modules tested and documented
3. **Documentation** - API docs and user guides complete
4. **Testing** - 80%+ code coverage on critical paths

---

## Dependencies

### Core Dependencies (Critical Path)
| Package | Purpose | Version |
|---------|---------|---------|
| laravel/framework | Core framework | ^11 |
| filament/filament | Admin panel | ~5.0 |
| livewire/livewire | Frontend interactivity | ^4.2 |
| spatie/laravel-permission | RBAC | ^6.16 |
| spatie/laravel-medialibrary | File uploads | ^11.0 |

### Module Dependencies
| Module | Dependencies |
|--------|-------------|
| Billing | laravel/cashier, omnipay/* |
| Cart | akaunting/laravel-money |
| Newsletter | mailerlite-api |
| AI | openai-php/client, neuron-ai |
| Search | Laravel Scout (optional) |

### Development Dependencies
| Package | Purpose | Version |
|---------|---------|---------|
| larastan/larastan | Static analysis | ^3.9 |
| phpunit/phpunit | Testing | ^11 |
| laravel/dusk | Browser testing | ^8.0 |
| nunomaduro/phpinsights | Code quality | ^2.0 |

---

## Assumptions

1. **Laravel Ecosystem** - Users have Composer and PHP 8.3+ available
2. **Modern Browsers** - End users use modern browsers with JavaScript enabled
3. **Write Permissions** - Web server has write access to `storage/`, `config/`, `userfiles/`
4. **SMTP Available** - Email sending configured for notifications
5. **SSL/TLS** - Production deployments use HTTPS
6. **File Uploads** - PHP `upload_max_filesize` >= 10MB for media

---

## Success Metrics

### Technical Metrics
- **Test Coverage** - > 80% for core modules
- **PHPStan Level** - Level 5 with zero errors
- **Security Audit** - Zero critical vulnerabilities
- **Performance** - Lighthouse score > 90 on public pages

### Business Metrics
- **Module Adoption** - 90+ modules maintained
- **API Stability** - No breaking changes in minor versions
- **Documentation** - Complete API docs and user guides
- **Community** - Active Discord and GitHub community

---

## Risks & Mitigations

| Risk | Probability | Impact | Mitigation |
|------|-------------|--------|------------|
| Filament v5 breaking changes | Medium | High | Comprehensive test suite, staged rollout |
| Module compatibility issues | High | Medium | Automated testing per module, compatibility layer |
| Performance degradation | Low | High | Performance testing, caching strategy, profiling |
| Security vulnerabilities | Low | Critical | Automated security scans, code review process |
| Database migration failures | Medium | High | Backup strategy, rollback procedures, dry runs |

---

## Documentation Requirements

### Technical Documentation
- API documentation (OpenAPI/Swagger)
- Architecture decision records (ADRs)
- Module development guide
- Database schema documentation
- Deployment guides

### User Documentation
- Installation guide
- User manual (end users)
- Admin guide
- Developer getting started
- Troubleshooting FAQ

---

## Version History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 2026-03-21 | Senior Developer | Initial scope definition |

---

**Status**: ✅ Draft Complete - Ready for Planning Phase

**Next Steps**:
1. Technical architecture review
2. Sprint planning and task breakdown
3. Development environment setup
4. Module prioritization

# Microweber Data Model & Schema Review

**Date:** 2026-04-03
**Branch:** filament-5
**Status:** Reviewed

## Entity Relationship Overview

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│    users      │◄────│  cart_orders  │────►│  customers   │
│  (bigInt id)  │     │  (bigInt id)  │     │ (bigInt id)  │
└──────┬───────┘     └──────┬───────┘     └──────┬───────┘
       │                    │                     │
       │              ┌─────┴─────┐               │
       │              │           │               │
       ▼              ▼           ▼               ▼
┌──────────────┐ ┌─────────┐ ┌──────────┐ ┌──────────────┐
│order_status_ │ │  cart    │ │ payments │ │  addresses   │
│  history     │ │(items)  │ │(morph)   │ │              │
└──────────────┘ └────┬────┘ └──────────┘ └──────────────┘
                      │
                      ▼
              ┌──────────────┐     ┌──────────────┐
              │   content    │◄───►│  categories  │
              │  (bigInt id) │     │ (bigInt id)  │
              └──────┬───────┘     └──────────────┘
                     │
        ┌────────────┼────────────┐
        │            │            │
        ▼            ▼            ▼
┌──────────────┐ ┌─────────┐ ┌──────────────┐
│ content_data │ │  media   │ │ tagging_tags │
│   (EAV)      │ │(polymorph│ │              │
└──────────────┘ └─────────┘ └──────────────┘
                      │
                      ▼
              ┌──────────────┐
              │media_folders │
              └──────────────┘

┌──────────────┐     ┌──────────────┐
│    menus     │     │  attributes  │
│  (int id)    │     │              │
└──────────────┘     └──────────────┘
```

## Core Tables

### Content Domain (STI Pattern)
- **content** — Single Table Inheritance for pages, posts, products via `content_type`
- **content_data** — EAV store for extended attributes (rel_type, rel_id, field_name, field_value)
- **categories** — Hierarchical via `parent_id`, linked to content via `categories_items`
- **categories_items** — Many-to-many: content ↔ categories

### Commerce Domain
- **cart_orders** — Orders with status, payment, shipping info
- **cart** — Line items linked to orders and products
- **payments** — Polymorphic payment records (rel_type, rel_id)
- **order_status_history** — Audit trail for order status changes (NEW)
- **order_refunds** — Refund records with type (full/partial) (NEW)
- **invoices** — Invoice generation and tracking
- **customers** — Customer profiles (may differ from users)
- **addresses** — Shipping/billing addresses

### Product Extensions
- **product_variants** — Product variant combinations (size/color)
- **product_variant_attributes** — Variant attribute definitions
- **product_pricing_rules** — Tier pricing, customer group pricing
- **product_inventory_movements** — Stock movement audit trail
- **product_inventory_alerts** — Low stock alerts
- **product_stock_reservations** — Cart-based stock reservations

### Media Domain
- **media** — Files with polymorphic ownership (rel_type, rel_id)
- **media_folders** — Hierarchical folder structure
- **media_thumbnails** — Cached thumbnail paths

### Navigation
- **menus** — Menu items and menu containers (item_type: 'menu' | 'menu_item')

### System
- **users** — Authentication, roles (Spatie)
- **sessions** — Laravel session store
- **cache** — Laravel cache store
- **notifications** — Laravel notifications
- **job_batches** — Queue job batches

## Schema Design Decisions

### 1. Single Table Inheritance (Content)
The `content` table uses `content_type` to distinguish pages, posts, and products. Extended product fields are stored in `content_data` (EAV pattern). This simplifies queries across content types but means product-specific columns are in a separate table.

### 2. Polymorphic Relations
- **media**: `rel_type` + `rel_id` — any model can own media
- **payments**: `rel_type` + `rel_id` — any model can have payments
- **content_data**: `rel_type` + `rel_id` — EAV for any model

### 3. Soft Delete Strategy
- `content`: Uses `is_deleted` flag (not Laravel SoftDeletes)
- `categories`: Uses `is_deleted` flag
- `cart_orders`, `cart`: Uses Laravel `deleted_at` column

## Index Strategy

### Existing Indexes (Good Coverage)
- **content**: parent, is_deleted, is_active, subtype, content_type, url, title, position, active_site_template
- **cart_orders**: order_status, customer_id, email, is_paid, order_reference_id, created_at, deleted_at, session_id
- **content_data**: composite (rel_type, rel_id, field_name)
- **categories**: composite (parent_id, data_type), parent_id
- **cart**: composite (session_id, order_completed), order_id

### Indexes Added in This Review
- **order_status_history**: `user_id` (was missing)
- **order_refunds**: `refunded_by` (was missing)

### Recommended Future Indexes
- **menus**: parent_id, content_id, categories_id, is_active
- **media**: composite (rel_type, rel_id), created_by
- **invoices**: customer_id, status, paid_status

## Migration Plan

### Applied (This Review)
1. `2026_04_03_000001_fix_order_tables_column_types_and_indexes.php`
   - Fix `order_status_history.order_id` from unsignedInteger to unsignedBigInteger (matches cart_orders.id)
   - Fix `order_status_history.user_id` from unsignedInteger to unsignedBigInteger (matches users.id)
   - Add index on `order_status_history.user_id`
   - Add index on `order_refunds.refunded_by`

### Known Type Mismatches (Pre-existing, Low Risk)
- `cart.order_id` is string — legacy design, changing would require data migration
- `menus.id` is 32-bit integer while `content.id` is 64-bit — menus table is small, no practical risk
- `content_data.rel_id` is string — polymorphic design, intentional

### No-Action Items
- Foreign key constraints are intentionally absent — Microweber uses application-level integrity checks and supports multiple DB drivers (MySQL, SQLite, PostgreSQL)
- The `is_deleted` flag pattern (vs SoftDeletes) is a legacy convention used across the content/category domain — migration would be high-risk for minimal benefit

# Project Knowledge Base

## Overview
- Microweber is a drag-and-drop CMS/e-commerce platform built on Laravel 11, PHP 8.3+, Filament 5, Livewire 4, and a large internal module system.
- The codebase mixes a Laravel application shell with internal framework packages (`src/MicroweberPackages/`) and feature modules (`Modules/`).

## Architecture & Module Map
- `app/` contains the thin Laravel app shell and providers.
- `src/MicroweberPackages/` contains cross-cutting framework code such as auth, cache, database helpers, Filament integrations, and user management.
- `Modules/` contains product features such as Content, Product, Order, Payment, Shipping, Media, AI, and Live Edit.
- `packages/` contains frontend asset and theme packages, including `frontend-assets` and `microweber-filament-theme`.
- `Templates/` contains installable templates with their own tests and frontend assets.

## Domain Logic
- Content creation, pages/posts/products, categories, and related metadata are centered in `Modules/Content`.
- User/authentication concerns are centered in `src/MicroweberPackages/User`.
- Live-edit behavior spans Filament admin pages, Livewire components, and the frontend asset packages.
- AI/MCP integrations are implemented in `Modules/Ai`.

## Data Models
- `Modules/Content/Models/Content.php` is the central content/domain model and includes search, media, category, menu, and multilingual traits.
- `src/MicroweberPackages/User/Models/User.php` is the central user model and mixes API tokens, roles, notifications, email verification, and social/auth integrations.
- Core settings/state are also spread across options and module-specific models.

## Integrations & External Services
- OAuth/API auth uses Laravel Passport and Sanctum.
- Payments use Omnipay drivers plus module-specific integrations.
- Frontend/docs/tooling use Node-based builds from the repo root and package subdirectories.
- CI runs Composer audit and npm audit in `.github/workflows/cicd-pipeline.yml`.

## Deployment & Infrastructure
- Laravel bootstraps from `bootstrap/app.php` and loads `web.php`, `api.php`, `ecommerce-api.php`, and `module-api.php`.
- CI targets PHP 8.3 by default and Node 22 in GitHub Actions.
- The local environment used by the agent currently serves Apache at `127.0.0.1`, but Laravel admin routes are not currently wired through at `/admin/login`.

## Environment Variables
- Application environment setup follows standard Laravel `.env` flow from `.env.example`.
- CI rewrites `.env` to `APP_ENV=testing` before automated suite execution.
- Production-safe defaults should keep `APP_DEBUG=false`; local/test variants may differ.

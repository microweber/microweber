# Frontend

The public-site routing layer for Microweber CMS. This package is **pure glue**: it registers the
front-end web routes (home page, the catch-all page/slug resolver, and the 404 fallback) and
delegates all rendering to the Microweber application's `FrontendController`.

## What it registers

`FrontendServiceProvider::boot()` loads `src/routes/web.php`, which defines:

- **`GET|POST /`** → `FrontendController@index` (route name `home`) — renders the site home page.
- **`ANY {slug}`** (route name `website`) → `FrontendController@index` — the catch-all that resolves any
  URL to a Microweber content page. The `where('slug', ...)` constraint **excludes** reserved
  prefixes (`vendor`, `packages`, `template`, `modules`, `css`, `storage`, `userfiles`, `js`, `admin`,
  `search`, `shop`, `customer`, `faq`, `testimonials`, `tag`/`tags`, `categories`, `captcha`) so those
  fall through to real module routes or the fallback instead of rendering a placeholder page.
- **`Route::fallback()`** — a context-aware 404:
  - admin URLs (`/<admin-prefix>/…`) render the admin-styled `admin.errors.404` view,
  - front-end URLs render `frontend.errors.404` extending the active template's master layout
    (with `X-Robots-Tag: noindex, nofollow`),
  - with a `text/plain` last-resort fallback. All responses carry `X-Fallback-Message` / `X-Powered-By`
    headers.

`FrontendController` here simply extends `MicroweberPackages\App\Http\Controllers\FrontendController` —
the package exists to own the route wiring, not the page-building logic.

## Installation

```bash
composer require microweber-packages/microweber-frontend
```

Register the provider (Microweber registers it from its app bootstrap; add it manually in a
standalone app):

```php
$app->register(MicroweberPackages\Frontend\Providers\FrontendServiceProvider::class);
```

## Usage

There is no public API to call — installing the package is what wires the front-end site routes.
Visiting any non-reserved URL is resolved to a content page by the controller:

```
GET /            → home page
GET /about-us    → content page with slug "about-us"
GET /no-such-url → context-aware 404 (fallback)
```

To serve a user-uploaded static file through the app, the package also ships
`ServeStaticFileController::serveFromUserfiles(Request $request)`.

## Key classes

- `MicroweberPackages\Frontend\Providers\FrontendServiceProvider` — loads the front-end routes.
- `MicroweberPackages\Frontend\Http\Controllers\FrontendController` — extends the App front controller.
- `MicroweberPackages\Frontend\Http\Controllers\ServeStaticFileController` — serves userfiles.
- `src/routes/web.php` — home / catch-all / fallback route definitions.

## Authors

Part of [Microweber](https://microweber.com), built by:

- Peter Ivanov

## License

MIT

# Cloudflare

Cloudflare CDN integration. Automatically configures trusted proxy settings when requests arrive through Cloudflare's network.

## Key Features

- Automatic detection of Cloudflare requests via `cdn-loop` header
- Dynamic trusted proxy configuration using Cloudflare's published IP ranges
- Ensures correct client IP resolution behind Cloudflare's reverse proxy

## How It Works

On the `mw.trust_proxies` event, the module checks if the incoming request has a `cdn-loop: cloudflare` header. If detected and no custom proxy configuration exists, it fetches Cloudflare's IP ranges and sets them as trusted proxies.

## Key Classes

| Class | Purpose |
|---|---|
| `Helpers\CloudflareHelpers` | Fetches and caches Cloudflare IP ranges |

## Configuration

No manual configuration required. The module activates automatically when Cloudflare is detected. Custom trusted proxy settings in `config('trustedproxy.proxies')` take precedence.

## Usage

Install and enable the module. It operates transparently -- no template tags or manual setup needed. The trusted proxy list is set automatically for requests routed through Cloudflare.

<?php

/*
|--------------------------------------------------------------------------
| Cross-Origin Resource Sharing (CORS)
|--------------------------------------------------------------------------
|
| Microweber exposes a headless `/api/module/*` namespace that is
| intended to be consumed from third-party frontends (SPAs, mobile apps,
| other sites talking to this CMS with a Passport bearer token). Those
| frontends live on different origins, so the browser's CORS preflight
| must succeed for writes to work.
|
| Origins are driven by env so each installation can lock down who is
| allowed to talk to the API without editing code:
|
|   CORS_ALLOWED_ORIGINS           — comma-separated list of full origins
|                                    e.g. "https://app.example.com,https://admin.example.com"
|   CORS_ALLOWED_ORIGIN_PATTERNS   — comma-separated regex patterns
|                                    e.g. "#^https://.*\.example\.com$#"
|   CORS_ALLOWED_HEADERS           — comma-separated header list; `*`=any
|   CORS_EXPOSED_HEADERS           — headers the browser is allowed to read
|   CORS_MAX_AGE                   — preflight cache lifetime in seconds
|   CORS_SUPPORTS_CREDENTIALS      — true/false
|
| When origins are unset we fall back to `*` (fully open). That keeps
| fresh installs working, but `supports_credentials` is auto-disabled
| in that case because the CORS spec forbids the combination.
|
*/

$originsList = array_filter(array_map(
    'trim',
    explode(',', (string) env('CORS_ALLOWED_ORIGINS', ''))
));

$originPatterns = array_filter(array_map(
    'trim',
    explode(',', (string) env('CORS_ALLOWED_ORIGIN_PATTERNS', ''))
));

$allowedOrigins = empty($originsList) && empty($originPatterns)
    ? ['*']
    : $originsList;

// The CORS spec disallows `Access-Control-Allow-Credentials: true` when
// the allowed origin is `*`. We honour the env flag but force it off in
// the wildcard case so browsers don't reject every request we return.
$supportsCredentialsEnv = filter_var(
    env('CORS_SUPPORTS_CREDENTIALS', true),
    FILTER_VALIDATE_BOOLEAN,
    FILTER_NULL_ON_FAILURE
);

$supportsCredentials = $allowedOrigins === ['*']
    ? false
    : ($supportsCredentialsEnv ?? true);

$allowedHeadersRaw = (string) env('CORS_ALLOWED_HEADERS', '*');
$allowedHeaders = array_values(array_filter(array_map('trim', explode(',', $allowedHeadersRaw))));

// `env()` returns '' (not the default) when the var is explicitly set to an
// empty string. Fall back to a sane default so operators can't accidentally
// drop the rate-limit headers by clearing the var in .env.
$exposedHeadersRaw = (string) env('CORS_EXPOSED_HEADERS', '');
if ($exposedHeadersRaw === '') {
    $exposedHeadersRaw = 'X-RateLimit-Limit,X-RateLimit-Remaining,Retry-After';
}
$exposedHeaders = array_values(array_filter(array_map('trim', explode(',', $exposedHeadersRaw))));

return [

    // Paths that run through the CORS middleware. `api/module/*` is the
    // headless namespace third-party frontends call; `api/*` covers the
    // rest of the API including the legacy routes, Passport OAuth and
    // Sanctum CSRF cookie.
    'paths' => [
        'api/*',
        'api/module/*',
        'oauth/*',
        'login',
        'logout',
        'sanctum/csrf-cookie',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => $allowedOrigins,

    'allowed_origins_patterns' => $originPatterns,

    'allowed_headers' => empty($allowedHeaders) ? ['*'] : $allowedHeaders,

    'exposed_headers' => $exposedHeaders,

    'max_age' => (int) env('CORS_MAX_AGE', 600),

    'supports_credentials' => $supportsCredentials,
];

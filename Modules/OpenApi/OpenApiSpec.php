<?php

declare(strict_types=1);

namespace Modules\OpenApi;

/**
 * Root-level OpenAPI 3.x spec for the Microweber Headless API.
 *
 * Scanned by zircote/swagger-php via the `base_path('Modules')` annotation
 * path in Modules/OpenApi/config/l5-swagger.php. Per-endpoint operation
 * annotations (OA\Get, OA\Post, etc. — noted without the leading `@`
 * here so the docblock parser doesn't try to materialise them as
 * separate operations) live on the individual *ApiController classes in
 * each module.
 *
 * Two security schemes are declared:
 *
 *   - bearerAuth  — Passport personal-access tokens sent as
 *                   `Authorization: Bearer <token>`. This is the default
 *                   used by every `/api/module/*` endpoint that requires
 *                   authentication.
 *   - passport    — Full OAuth2 authorization-code and password flows for
 *                   third-party integrations that want to authenticate end
 *                   users against the site's Passport server.
 *
 * @OA\Info(
 *     title="Microweber Headless API",
 *     version="1.0.0",
 *     description="JSON API for Microweber modules. Authenticate with a Passport personal-access token sent as an `Authorization: Bearer <token>` header, or with a full OAuth2 flow via the `passport` security scheme. Most read endpoints are publicly accessible and rate-limited via the `public` throttle; write endpoints require `auth:api` plus the `api` throttle.",
 *     @OA\Contact(
 *         name="Microweber",
 *         url="https://microweber.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Passport personal-access token",
 *     description="Laravel Passport personal-access token. Issue one from the admin under Account → API Applications, then send it as `Authorization: Bearer <token>`."
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="passport",
 *     type="oauth2",
 *     description="Laravel Passport OAuth2 — authorization-code flow for third-party apps and password grant for first-party clients.",
 *     @OA\Flow(
 *         flow="authorizationCode",
 *         authorizationUrl="/oauth/authorize",
 *         tokenUrl="/oauth/token",
 *         refreshUrl="/oauth/token/refresh",
 *         scopes={}
 *     ),
 *     @OA\Flow(
 *         flow="password",
 *         tokenUrl="/oauth/token",
 *         refreshUrl="/oauth/token/refresh",
 *         scopes={}
 *     )
 * )
 */
final class OpenApiSpec
{
}

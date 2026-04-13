# OpenApi Module for Microweber

This module provides functionality for generating and serving OpenAPI documentation for your API endpoints.

## Features

- Automatically generates OpenAPI documentation based on your routes and controllers.
- Provides a user-friendly interface for accessing API documentation.

## Installation

To install the OpenApi module, run the following command:

```sh
php artisan module:install OpenApi
```

## Publish module assets

```sh
php artisan module:publish OpenApi
```


## API Documentation

You can access the generated OpenAPI documentation at the following endpoint:
```
/api/documentation
```

## MCP documentation note

The JSON-RPC MCP endpoint currently lives in `Modules/Ai` at `POST /api/mcp`.

Its implementation contract is documented in `Modules/Ai/README.md` under the `MCP contract` section, including:

- JSON-RPC request/response envelopes
- authentication header requirements
- supported MCP methods and tool schemas
- transport vs JSON-RPC error semantics
- token rotation expectations

If MCP is later exposed through this OpenAPI module, that section should be used as the baseline contract to mirror into generated documentation.

## Testing

Run the tests using the following command:

```sh
php artisan test --filter OpenApi
```

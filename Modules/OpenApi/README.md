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

## Testing

Run the tests using the following command:

```sh
php artisan test --filter OpenApi
```


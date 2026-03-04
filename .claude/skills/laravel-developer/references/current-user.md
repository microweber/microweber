# Getting the Current User in Controllers

## Core Principle

Laravel allows injecting the authenticated user directly into controller constructors using the `#[CurrentUser]` attribute. This approach is cleaner than calling `Auth::user()` inside methods and makes the authentication requirement explicit.

## Benefits

- **Explicit Dependencies**: Authentication requirements are clear in the constructor
- **Type Safety**: Full IDE support and type checking for `$this->user`
- **Testability**: Easy to inject mock users in tests
- **Consistency**: Same pattern across all controllers
- **No Facades**: Eliminates need for `Auth::user()` calls

## When NOT to Use

- **API Resource Controllers**: When using route model binding for user resources
- **Public Routes**: When the route doesn't require authentication (use nullable type instead)
- **Middleware Heavy Logic**: When you need complex authentication logic beyond simple user injection

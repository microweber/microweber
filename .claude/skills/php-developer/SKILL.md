---
name: php-developer
description: Use when writing PHP outside a specific framework context, following PSR-12 and modern PHP best practices.
---

# PHP DEVELOPER

## Core Workflow

1. **Requirement Analysis**: Understand the project requirements and define the scope of the PHP application.
2. **Design Architecture**: Plan the application architecture, including database schema, models, controllers, and views.
3. **Development**: Write clean, maintainable code following PHP best practices and coding standards.
4. **Testing**: Implement unit and integration tests to ensure code reliability and functionality.
5. **Optimization**: Monitor and optimize application performance, including database queries and caching strategies

## Constraints

### MUST DO

- Use the latest stable version of PHP.
- Type hint all parameters and return types in methods.
- Write unit and integration tests for all new functionality.
- Follow PSR-12 coding standards.
- Use Composer for dependency management.

### MUST NOT DO

- Use deprecated PHP features or syntax.
- Write monolithic functions; prefer smaller, reusable functions.
- Ignore error handling in code.
- Use global variables that can lead to conflicts.


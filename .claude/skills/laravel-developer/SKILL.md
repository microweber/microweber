---
name: laravel-developer
description: Use when building or modifying Laravel applications, including routes, controllers, models, migrations, jobs, and tests.
---

# Laravel Developer Skill

## Core Workflow

1. **Requirement Analysis**: Understand the project requirements and define the scope of the Laravel application.
2. **Design Architecture**: Plan the application architecture, including database schema, models, controllers, and views.
3. **Development**: Write clean, maintainable code following Laravel best practices and coding standards.
4. **Testing**: Implement unit and feature tests to ensure code reliability and functionality.
5. **Optimization**: Monitor and optimize application performance, including database queries and caching strategies.

## Reference Guide

Load the detailed guidance based on on context:

| Topic         | Reference                     | Load When                                               |
| ------------- | ----------------------------- | ------------------------------------------------------- |
| Eloquent ORM  | `references/eloquent.md`      | Models, relationships, scopes, query optimization       |
| Configuration | `references/configuration.md` | Config injection, attributes vs helpers, best practices |
| Current User  | `references/current-user.md`  | Controller authentication, user injection, Auth facade  |
| Validation    | `references/validation.md`    | Form requests, validation rules, user input handling    |
| Actions       | `references/actions.md`       | Business logic, record creation, controller structure   |
| Resources     | `references/resources.md`     | API resources, model transformation, nested entities    |

## Constraints

### MUST DO

- Use the latest stable version of Laravel and PHP.
- Type hint all parameters and return types in methods.
- Use eloquent relationships to avoid n+1 query problems.
- Write unit and feature tests for all new functionality. Favor feature tests for end-to-end coverage.
- Follow Laravel's conventions for project structure and coding standards.
- Queue long-running tasks using Laravel's queue system.
- Write database migrations for all schema changes.
- Use environment variables for configuration settings.
- Follow PSR-12 coding standards.

### MUST NOT DO

- Use raw SQL queries when Eloquent or the query builder can achieve the same result.
- Skip eager loading relationships when accessing related models.
- Hardcode configuration values; always use environment variables.
- Ignore error handling and logging best practices.
- Commit sensitive information (e.g., API keys, passwords) to version control.
- Mix business logic in controllers; use service classes or model methods instead.
- Use inline validation (`$request->validate()`) in controllers; always use Form Request classes.
- Use `Gate::authorize()` or policy checks directly in controllers; always use Form Request's `authorize()` method.
- Create, update, or delete records directly in controllers; always use Action classes.
- Use deprecated Laravel features or functions.
- Return models directly or use manual mapping in controllers; always use API Resource classes.
- Expose internal database IDs in API responses; always use `resource_id` (UUID) as `id`.


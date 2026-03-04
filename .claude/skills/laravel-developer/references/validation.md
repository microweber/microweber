# Laravel Validation Reference

## Always Use Form Request Classes

All validation MUST be in dedicated Form Request classes. Never use `$request->validate()` or `$this->validate()` directly in controllers.

## Summary

- **ALWAYS** use Form Request classes for validation
- **NEVER** use `$request->validate()` in controllers
- Use array notation for validation rules
- Leverage Rule classes for complex validation
- Keep business logic validation in custom Rule classes
- Use `authorize()` for permission checks
- Access validated data with `$request->validated()`
- Write tests for validation rules

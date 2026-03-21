# Tax Calculation Engine

A comprehensive location-based tax calculation system for Microweber CMS.

## Overview

The Tax Calculation Engine provides flexible, location-aware tax calculations for e-commerce orders. It supports multiple tax rates, location-based rules, compound taxes, and validity periods.

## Features

- **Location-Based Rules**: Country, state, city, and ZIP code-based tax rates
- **Tax Types**: Percentage and fixed amount taxes
- **Compound Taxes**: Support for taxes calculated on top of other taxes
- **Validity Periods**: Time-limited tax rates with start and end dates
- **Priority System**: Sophisticated matching with specificity-based priority
- **Caching**: Efficient caching of applicable tax rates
- **Wildcards**: ZIP code patterns with wildcard support (e.g., "100*")

## Architecture

### Models

#### TaxRate

The `TaxRate` model stores individual tax rates with location rules:

```php
$taxRate = TaxRate::create([
    'name' => 'California Sales Tax',
    'type' => 'percentage',
    'rate' => 8.25,
    'country_code' => 'US',
    'state_code' => 'CA',
    'zip_code_pattern' => null, // Specific ZIP or pattern like '902*'
    'city' => null,
    'compound_tax' => false,
    'is_active' => true,
    'priority' => 10,
    'is_default' => false,
    'valid_from' => now(),
    'valid_until' => null,
]);
```

### Services

#### TaxCalculator

Main service for calculating taxes:

```php
use Modules\Tax\Services\TaxCalculator;

$calculator = app(TaxCalculator::class);

// Basic calculation
$result = $calculator->calculate(100.00, [
    'country_code' => 'US',
    'state_code' => 'CA',
    'city' => 'Los Angeles',
    'zip_code' => '90210',
]);

// Result structure:
[
    'amount' => 8.25,
    'breakdown' => [
        [
            'rate_id' => 1,
            'name' => 'California Sales Tax',
            'rate' => 8.25,
            'type' => 'percentage',
            'amount' => 8.25,
            'taxable_amount' => 100.00,
            'compound' => false,
            'location' => 'US, CA',
        ]
    ]
]
```

#### Legacy TaxManager

The existing `TaxManager` class remains available for backward compatibility. The new system integrates seamlessly while preserving existing functionality.

## Usage

### Creating Tax Rates

#### Global Tax Rate
```php
TaxRate::create([
    'name' => 'Global VAT',
    'type' => 'percentage',
    'rate' => 10.00,
    'country_code' => null,
    'is_active' => true,
]);
```

#### Country-Specific
```php
TaxRate::create([
    'name' => 'UK VAT',
    'type' => 'percentage',
    'rate' => 20.00,
    'country_code' => 'GB',
    'is_active' => true,
]);
```

#### State-Specific
```php
TaxRate::create([
    'name' => 'California Tax',
    'type' => 'percentage',
    'rate' => 8.00,
    'country_code' => 'US',
    'state_code' => 'CA',
    'is_active' => true,
]);
```

#### ZIP Code Pattern
```php
TaxRate::create([
    'name' => 'NYC Tax',
    'type' => 'percentage',
    'rate' => 8.875,
    'country_code' => 'US',
    'state_code' => 'NY',
    'zip_code_pattern' => '100*', // All ZIPs starting with 100
    'is_active' => true,
]);
```

#### Compound Tax
```php
TaxRate::create([
    'name' => 'Compound Tax',
    'type' => 'percentage',
    'rate' => 5.00,
    'compound_tax' => true,
    'priority' => 5,
    'is_active' => true,
]);
```

#### Time-Limited
```php
TaxRate::create([
    'name' => 'Holiday Tax',
    'type' => 'percentage',
    'rate' => 8.00,
    'valid_from' => now()->addMonth(),
    'valid_until' => now()->addMonths(2),
    'is_active' => true,
]);
```

### Calculating Taxes

```php
use Modules\Tax\Services\TaxCalculator;

$calculator = app(TaxCalculator::class);

// Simple calculation
$tax = $calculator->calculateAmount(100.00);

// With location
$tax = $calculator->calculateAmount(100.00, [
    'country_code' => 'US',
    'state_code' => 'CA',
]);

// Full calculation with breakdown
$result = $calculator->calculate(100.00, $location, $context);
```

### Checking Applicability

```php
$taxRate = TaxRate::find(1);

// Check if rate applies to location
$applies = $taxRate->appliesToLocation([
    'country_code' => 'US',
    'state_code' => 'CA',
    'zip_code' => '90210',
]);

// Check validity
$isValid = $taxRate->isValid();

// Get formatted display
$displayRate = $taxRate->formatted_rate; // "8.25%"
$displayLocation = $taxRate->location_description; // "US, CA"
```

## Admin Interface

The Tax Rate resource is available in the Filament admin panel under **Shop Settings > Tax Rates**.

### Features
- Create/edit tax rates with location rules
- Set validity periods with date pickers
- Configure compound taxes
- Set priority levels
- Filter and search tax rates
- Enable/disable tax rates

## Integration with Cart

The tax calculation is automatically integrated into the cart totals service:

```php
// In your checkout controller
$totalsService = app(CartTotalsService::class);

// Pass location data for tax calculation
$totals = $totalsService->totals('all', [
    'country_code' => 'US',
    'state_code' => 'CA',
]);

// Get detailed tax breakdown
$taxBreakdown = $totalsService->getTaxBreakdown([
    'country_code' => 'US',
    'state_code' => 'CA',
]);
```

The cart automatically retrieves location data from the checkout session if available.

## Priority and Specificity

Tax rates are matched based on specificity (most specific first):

1. **ZIP code pattern** - Highest specificity (100 points)
2. **City** - High specificity (50 points)
3. **State** - Medium specificity (30 points)
4. **Country** - Low specificity (20 points)
5. **Global** - No specificity (0 points)

Additionally, the `priority` field can boost specific rates.

## Database Schema

```sql
create table tax_rates (
    id bigint unsigned auto_increment primary key,
    name varchar(255) not null,
    description text null,
    country_code varchar(2) null,
    state_code varchar(10) null,
    zip_code_pattern varchar(20) null,
    city varchar(255) null,
    type enum('percentage', 'fixed') default 'percentage',
    rate decimal(10, 4) default 0,
    compound_tax tinyint(1) default 0,
    priority int default 0,
    is_default tinyint(1) default 0,
    is_active tinyint(1) default 1,
    valid_from timestamp null,
    valid_until timestamp null,
    applies_to_products json null,
    applies_to_categories json null,
    applies_to_customer_groups json null,
    created_at timestamp null,
    updated_at timestamp null
);
```

## Testing

Run tax-specific tests:

```bash
./vendor/bin/phpunit Modules/Tax/Tests/TaxCalculatorTest.php
./vendor/bin/phpunit Modules/Tax/Tests/TaxRateModelTest.php
```

## Migration

Run the migration to create the tax_rates table:

```bash
php artisan migrate --path=Modules/Tax/database/migrations
```

## Environment Variables

```env
# No specific environment variables required
# Tax rates are configured through the admin interface
```

## API Usage

### Calculate Tax

```php
POST /api/tax/calculate
{
    "amount": 100.00,
    "country_code": "US",
    "state_code": "CA",
    "zip_code": "90210"
}

Response:
{
    "amount": 8.25,
    "breakdown": [...]
}
```

### Get Applicable Rates

```php
GET /api/tax/rates?country_code=US&state_code=CA

Response:
{
    "rates": [...]
}
```

## Backward Compatibility

The existing `TaxManager` and `TaxType` system remains functional. The new `TaxCalculator` integrates with the existing cart system while maintaining full backward compatibility.

## Performance

- Tax rates are cached per request
- Database queries are optimized with proper indexes
- Compound calculations are done in-memory
- Location validation is efficient with minimal overhead

## Security

- All location inputs are validated and sanitized
- SQL injection prevention through parameterized queries
- Admin access control via Filament policies

## Future Enhancements

- Product/category-specific tax rates
- Customer group-specific rates
- Tax exemption rules
- Multi-currency tax calculations
- Tax reporting and analytics

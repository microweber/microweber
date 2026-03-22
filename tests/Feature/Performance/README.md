# Load and Performance Testing

This directory contains comprehensive load testing and performance benchmarks for the Microweber application.

## Test Files

### 1. LoadTestingTest.php
Core load testing suite covering:
- **Concurrent User Simulation**: Tests how the system handles multiple simultaneous requests
- **Response Time Thresholds**: Validates response times against defined thresholds
- **Memory Usage**: Monitors memory consumption under load
- **Critical Path Testing**: Tests homepage, product listings, content pages, cart operations
- **Database Query Performance**: Validates database operation efficiency

### 2. ResponseTimeBenchmarkTest.php
Specific benchmarks for:
- **Critical Pages**: Homepage, shop, cart, checkout
- **API Endpoints**: Content, products, cart APIs
- **E-commerce Operations**: Add to cart, get cart, checkout flow
- **Admin Panel**: Dashboard, content management, product management
- **Search Operations**: Product and content search
- **Static Assets**: CSS/JS file delivery

## Configuration

### Response Time Thresholds

| Endpoint Type | Threshold | Maximum |
|--------------|-----------|---------|
| Homepage | 500ms | 2000ms |
| Product Listing | 600ms | 2000ms |
| Cart Operations | 400ms | 1500ms |
| Checkout | 700ms | 2000ms |
| API Endpoints | 300ms | 1000ms |
| Admin Panel | 1500ms | 3000ms |
| Search | 800ms | 2000ms |
| Database Queries | 200ms | 500ms |
| Cache Operations | 50ms | 100ms |

## Running Tests

### Run All Performance Tests

```bash
./vendor/bin/phpunit tests/Feature/Performance
```

### Run Specific Test File

```bash
./vendor/bin/phpunit tests/Feature/Performance/LoadTestingTest.php
```

### Run Benchmark Tests Only

```bash
./vendor/bin/phpunit --group benchmark tests/Feature/Performance
```

### Run With Filter

```bash
./vendor/bin/phpunit --filter=concurrent tests/Feature/Performance
```

### Using Artisan Command

```bash
# Run all load tests
php artisan test:load

# Run benchmark tests
php artisan test:load --benchmark

# Run with specific filter
php artisan test:load --filter=homepage

# Run specific group
php artisan test:load --group=benchmark
```

## Test Coverage

### Load Testing
- ✓ Homepage under baseline load
- ✓ Product listing page performance
- ✓ Content page rendering
- ✓ API endpoint performance
- ✓ Cart operations
- ✓ Database query performance
- ✓ Memory usage stability
- ✓ Concurrent request handling
- ✓ Admin panel performance
- ✓ Search functionality
- ✓ Cache operations

### Benchmarks
- ✓ Critical page response times
- ✓ API endpoint response times
- ✓ Authentication flows
- ✓ E-commerce operations
- ✓ Database operations
- ✓ Admin operations
- ✓ Static asset delivery
- ✓ Concurrent operations

## Understanding Results

### Pass Criteria

A test passes when:
1. HTTP status code is 200 (or expected redirect)
2. Response time is below the defined threshold
3. Memory usage remains stable
4. No errors or exceptions occur

### Interpreting Failures

If a test fails:

1. **Check the error message** for specific threshold exceeded
2. **Review recent changes** that might affect performance
3. **Enable profiling** to identify bottlenecks
4. **Check database indexes** for slow queries
5. **Review cache configuration** for cache misses

## Performance Optimization

### If Tests Fail

1. **Enable Query Logging**
   ```php
   DB::enableQueryLog();
   // Run test
   dump(DB::getQueryLog());
   ```

2. **Check Cache Hit Rate**
   ```php
   cache()->store()->getStore()->getStats();
   ```

3. **Profile with Xdebug**
   - Enable profiler in php.ini
   - Review cachegrind files

4. **Use Telescope** (in dev)
   - Review request duration
   - Analyze database queries
   - Check memory usage

### Optimization Checklist

- [ ] Enable OPcache in production
- [ ] Use Redis for cache and sessions
- [ ] Enable full page caching
- [ ] Optimize database indexes
- [ ] Use CDN for static assets
- [ ] Enable gzip compression
- [ ] Configure proper PHP-FPM settings
- [ ] Monitor slow queries

## Environment Variables

Tests use these environment settings:

```env
CACHE_STORE=array      # Uses array cache in tests
SESSION_DRIVER=array   # Array session for speed
QUEUE_CONNECTION=sync  # Synchronous for tests
```

## CI/CD Integration

Add to your CI pipeline:

```yaml
- name: Run Performance Tests
  run: |
    php artisan test:load --benchmark
  continue-on-error: true  # Don't fail build, just report
```

## Related Documentation

- [Advanced Caching](../../../docs/ADVANCED_CACHING.md)
- [Database Optimization](../../../docs/)
- [Production Deployment](../../../docs/)

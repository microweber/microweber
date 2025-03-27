# Google Analytics Module for Microweber

This module provides Google Analytics 4 (GA4) integration for your Microweber website, including enhanced e-commerce tracking and conversion tracking.

## Features

- Google Analytics 4 (GA4) integration
- Enhanced e-commerce tracking
- Conversion tracking
- User data tracking with privacy compliance
- Event tracking for:
  - Page views
  - User logins
  - Sign ups
  - Add to cart actions
  - Begin checkout
  - Add shipping info
  - Add payment info
  - Purchases
  - Custom conversions

## Installation

### Run module migrations
```sh
php artisan module:migrate GoogleAnalytics
```

### Publish module assets
```sh
php artisan module:publish GoogleAnalytics
```

## Configuration

1. Go to your Google Analytics account and set up a GA4 property
2. Get your Measurement ID (starts with G-)
3. Create an API Secret in your GA4 property settings
4. In your Microweber admin panel:
   - Navigate to Modules > Google Analytics
   - Enable Google Analytics tracking
   - Enter your Measurement ID and API Secret
   - Optionally enable Enhanced Conversions if needed

## Enhanced Conversions Setup

If you want to track conversions with more detailed user data:

1. Enable Enhanced Conversions in your GA4 property
2. Get your Conversion ID and Label
3. In the module settings:
   - Enable Enhanced Conversions
   - Enter your Conversion ID and Label

## Usage

Once configured, the module will automatically:

- Add the GA4 tracking code to your site
- Track basic analytics data
- Track e-commerce events if you're using Microweber's shop features
- Send enhanced conversion data when available

### Technical Implementation

The module works by:

1. **Event Tracking**:
   - Listens to Microweber's event system
   - Maps events to GA4 measurement protocol
   - Uses the `DispatchGoogleEventsJs` class to handle event conversion

2. **Enhanced Conversions**:
   - Requires Conversion ID and Label
   - Validates e-commerce data before sending
   - Uses the `StatsEvent` model to track conversion status

3. **Testing**:
   ```php
   public function test_enhanced_conversions_are_handled_correctly()
   {
       // Tests verify:
       // 1. Correct payload structure
       // 2. Conversion marking
       // 3. Data validation
   }
   ```

### Manual Event Tracking

For custom events, use:

```php
use Microweber\Modules\GoogleAnalytics\Support\GoogleAnalyticsEvents;

GoogleAnalyticsEvents::trackEvent('event_name', [
    'param1' => 'value1',
    'param2' => 'value2' 
]);
```

### Debugging

Enable debug mode in config to log events:
```php
'debug' => env('GOOGLE_ANALYTICS_DEBUG', false)
```

Debug logs will show:
- Event payloads
- API responses
- Conversion status

## License

This module is licensed under the MIT License.

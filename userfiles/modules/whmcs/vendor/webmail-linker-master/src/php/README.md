Webmail Linker
===========================================================================================

PHP Library
-------------------------------------------------------------------------------------------


### Usage

```php
$wl = new WebmailLinker();

if($provider = $wl->getProviderByEmailAddress('user@example.com')) {
  $html = '<a href="%s">Check your email at %s</a>';
  printf($html, htmlspecialchars($provider['url']), htmlspecialchars($provider['name']));
}
```

### Compatibility

PHP 5.2+


### Dependencies

Requires the Webmail Linker config, so please include the whole Webmail Linker repository.
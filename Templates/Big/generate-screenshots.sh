COMPOSER_ALLOW_SUPERUSER=1  composer update "laravel/dusk" --prefer-dist --no-interaction --no-progress -W
php artisan dusk:install
php artisan dusk:chrome-driver

COMPOSER_ALLOW_SUPERUSER=1  composer require --dev staudenmeir/dusk-updater
php artisan package:discover --ansi
php artisan dusk:update --detect


php artisan dusk --testsuite MicroweberTemplatesTests

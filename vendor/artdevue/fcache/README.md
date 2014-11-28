# File cache driver - Fcache
========

**File cache driver distrubutes cache to folders depending to key-parameter. This is Laravel 4 integration of Fcache.**

## Quick Start

You can install this Fcache class quickly and easily with Composer.

Edit your composer.json and add next:

```php
"artdevue/fcache": "dev-master"
```

Run Composer to install or update the new dependencies: 

```php
$ composer update
```
## Laravel 4 Integration

The Fcache Cache class supports Laravel 4 integration. Best practice is to add the ServiceProvider of the Intervention Fcache in Laravel 4 installment.

Open your Laravel config file config/app.php and add the following:

In the *$providers* section add service providers for this package.

```php
'Artdevue\Fcache\FcacheServiceProvider'
```
Open your Laravel global file start/global.php and add the following lines.

```php
Cache::extend('fcache', function($app)
{
    $store = new Artdevue\Fcache\Fcache;
    return new Illuminate\Cache\Repository($store);
});
```
## Usage

Open your Laravel config file config/cache.php and change driver to fcache.
```php
'driver' => 'fcache',
```
**Note!** All commands are identical to the driver file cache, except *forget*

To create a directory cache it is enough to separate the slash key directory, the file will be the last word.

For example, create the key *folder/onesubfolder/onefile*.

 ```php
Cache::put('folder/onesubfolder/onefile', 'value', $minutes);
```

The system automatically will create subfolders - *folder* and *onesubfolder*, and the file *onefile.cache*
```php
folder
	onesubfolder
		onefile.cache
		twofile.cache
	twosubfolder
		onefile.cache
		twofile.cache
file.cache
```
With the file cache Fcache you can delete the entire cache, and the cache folder.
To delete a file *folder/onesubfolder/onefile.cache*

```php
Cache::forget('folder/onesubfolder/onefile');
```
If you need to remove all the files in a directory *folder/onesubfolder*, then change the key to *folder/onesubfolder*.
```php
Cache::forget('folder/onesubfolder');
```

You can group your cache folders, it gives you ability to delete the whole group cache files.

## Using tags
Also file driver Fcache works with Tags. When you add a cache, you can use comma-separated parameter *tags*. 
Example:
 ```php
Cache::tags('country,all')->put('key', 'value', $minutes);
```
or
 ```php
$value = Cache::tags('users,all')->rememberForever('users', function()
{
    return DB::table('users')->get();
});
```
Now you can easily remove any cache tag. For example, to delete the cache tag with the *users*:
 ```php
Cache::forgetTags('users');
```
**Note!** If you want to delete the cache with different tags, assign comma-separated tags. 
For example:
 ```php
Cache::forgetTags('users,all');
```



### Authors
<table>
  <tr>
    <td><img src="http://www.gravatar.com/avatar/39ef1c740deff70b054c1d9ae8f86d02?s=60"></td><td valign="middle">Valentin Rasulov<br>artdevue.com<br><a href="http://artdevue.com">http://artdevue.com</a></td>
  </tr>
</table>

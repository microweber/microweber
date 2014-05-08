# Keboola CSV reader/writer [![Build Status](https://secure.travis-ci.org/keboola/php-csv.png)](http://travis-ci.org/keboola/php-csv)

## Usage

### Read CSV

```php
$csvFile = new Keboola\Csv\CsvFile(__DIR__ . '/_data/test-input.csv');
foreach($csvFile as $row) {
	var_dump($row);
}
```

### Write CSV

```php
$csvFile = new Keboola\Csv\CsvFile(__DIR__ . '/_data/test-output.csv');
$rows = array(
	array(
		'col1', 'col2',
	),
	array(
		'line without enclosure', 'second column',
	),
);

foreach ($rows as $row) {
	$csvFile->writeRow($row);
}
```

## Installation

Library is available as composer package.
To start using composer in your project follow these steps:

**Install composer**

```bash
curl -s http://getcomposer.org/installer | php
mv ./composer.phar ~/bin/composer # or /usr/local/bin/composer
```

**Create composer.json file in your project root folder:**

```json
{
    "require": {
        "php" : ">=5.3.2",
        "keboola/csv": "1.1.*"
    }
}
```

**Install package:**

```bash
composer install
```


**Add autoloader in your bootstrap script:**

```bash
require 'vendor/autoload.php';
```


Read more in [Composer documentation](http://getcomposer.org/doc/01-basic-usage.md)

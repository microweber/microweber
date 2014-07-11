## Valitron: Easy Validation That Doesn't Suck

Valitron is a simple, minimal and elegant stand-alone validation library
with NO dependencies. Valitron uses simple, straightforward validation
methods with a focus on readable and concise syntax. Valitron is the
simple and pragmatic validation library you've been looking for.

[![Build
Status](https://travis-ci.org/vlucas/valitron.png?branch=master)](https://travis-ci.org/vlucas/valitron)
[![Latest Stable Version](https://poser.pugx.org/vlucas/valitron/v/stable.png)](https://packagist.org/packages/vlucas/valitron)
[![Total Downloads](https://poser.pugx.org/vlucas/valitron/downloads.png)](https://packagist.org/packages/vlucas/valitron)

## Why Valitron?

Valitron was created out of frustration with other validation libraries
that have dependencies on large components from other frameworks like
Symfony's HttpFoundation, pulling in a ton of extra files that aren't
really needed for basic validation. It also has purposefully simple
syntax used to run all validations in one call instead of individually
validating each value by instantiating new classes and validating values
one at a time like some other validation libraries require.

In short, Valitron is everything you've been looking for in a validation
library but haven't been able to find until now: simple pragmatic
syntax, lightweight code that makes sense, extensible for custom
callbacks and validations, well tested, and without dependencies. Let's
get started.

## Installation

Valitron uses [Composer](http://getcomposer.org) to install and update:

```
curl -s http://getcomposer.org/installer | php
php composer.phar require vlucas/valitron
```

The examples below use PHP 5.4 syntax, but Valitron works on PHP 5.3+.

## Usage

Usage is simple and straightforward. Just supply an array of data you
wish to validate, add some rules, and then call `validate()`. If there
are any errors, you can call `errors()` to get them.

```php
$v = new Valitron\Validator(array('name' => 'Chester Tester'));
$v->rule('required', 'name');
if($v->validate()) {
    echo "Yay! We're all good!";
} else {
    // Errors
    print_r($v->errors());
}
```

Using this format, you can validate `$_POST` data directly and easily,
and can even apply a rule like `required` to an array of fields:

```php
$v = new Valitron\Validator($_POST);
$v->rule('required', ['name', 'email']);
$v->rule('email', 'email');
if($v->validate()) {
    echo "Yay! We're all good!";
} else {
    // Errors
    print_r($v->errors());
}
```

Setting language and language dir globally:

```php

// boot or config file

use Valitron\Validator as V;

V::langDir(__DIR__.'/validator_lang'); // always set langDir before lang.
V::lang('ar');

```



## Built-in Validation Rules

 * `required` - Required field
 * `equals` - Field must match another field (email/password confirmation)
 * `different` - Field must be different than another field
 * `accepted` - Checkbox or Radio must be accepted (yes, on, 1, true)
 * `numeric` - Must be numeric
 * `integer` - Must be integer number
 * `length` - String must be certain length
 * `lengthBetween` - String must be between given lengths
 * `lengthMin` - String must be greater than given length
 * `lengthMax` - String must be less than given length
 * `min` - Minimum
 * `max` - Maximum
 * `in` - Performs in_array check on given array values
 * `notIn` - Negation of `in` rule (not in array of values)
 * `ip` - Valid IP address
 * `email` - Valid email address
 * `url` - Valid URL
 * `urlActive` - Valid URL with active DNS record
 * `alpha` - Alphabetic characters only
 * `alphaNum` - Alphabetic and numeric characters only
 * `slug` - URL slug characters (a-z, 0-9, -, _)
 * `regex` - Field matches given regex pattern
 * `date` - Field is a valid date
 * `dateFormat` - Field is a valid date in the given format
 * `dateBefore` - Field is a valid date and is before the given date
 * `dateAfter` - Field is a valid date and is after the given date
 * `contains` - Field is a string and contains the given string
 * `creditCard` - Field is a valid credit card number


## Credit Card Validation usage

Credit card validation currently allows you to validate a Visa `visa`,
Mastercard `mastercard`, Dinersclub `dinersclub`, American Express `amex`
or Discover `discover`

This will check the credit card against each card type

```php
$v->rule('creditCard', 'credit_card');
```

To optionally filter card types, add the slug to an array as the next parameter:

```php
$v->rule('creditCard', 'credit_card', ['visa', 'mastercard']);
```

If you only want to validate one type of card, put it as a string:

```php
$v->rule('creditCard', 'credit_card', 'visa');
```

If the card type information is coming from the client, you might also want to
still specify an array of valid card types:

```php
$cardType = 'amex';
$v->rule('creditCard', 'credit_card', $cardType, ['visa', 'mastercard']);
$v->validate(); // false
```


## Adding Custom Validation Rules

To add your own validation rule, use the `addRule` method with a rule
name, a custom callback or closure, and a error message to display in
case of an error. The callback provided should return boolean true or
false.

```php
Valitron\Validator::addRule('alwaysFail', function($field, $value, array $params) {
    return false;
}, 'Everything you do is wrong. You fail.');
```

## Alternate syntax for adding rules

As the number of rules grows, you may prefer the alternate syntax
for defining multiple rules at once.

```php
$rules = [
    'required' => 'foo',
    'accepted' => 'bar',
    'integer' =>  'bar'
];

$v = new Valitron\Validator(array('foo' => 'bar', 'bar' => 1));
$v->rules($rules);
$v->validate();
```

If your rule requires multiple parameters or a single parameter
more complex than a string, you need to wrap the rule in an array.

```php
$rules = [
    'required' => [
        ['foo'],
        ['bar']
    ],
    'length' => [
        ['foo', 3]
    ]
];
```
You can also specify multiple rules for each rule type.

```php
$rules = [
    'length'   => [
        ['foo', 5],
        ['bar', 5]
    ]
];
```

Putting these techniques together, you can create a complete
rule definition in a relatively compact data structure.

You can continue to add individual rules with the `rule` method
even after specifying a rule definition via an array. This is
especially useful if you are defining custom validation rules.

```php
$rules = [
    'required' => 'foo',
    'accepted' => 'bar',
    'integer' =>  'bar'
];

$v = new Valitron\Validator(array('foo' => 'bar', 'bar' => 1));
$v->rules($rules);
$v->rule('min', 'bar', 0);
$v->validate();
```

## Adding field label to messages

You can do this in two different ways, you can add a individual label to a rule or an array of all labels for the rules.

To add individual label to rule you simply add the `label` method after the rule.

```php
$v = new Valitron\Validator(array());
$v->rule('required', 'name')->message('{field} is required')->label('Name');
$v->validate();
```

There is a edge case to this method, you wouldn't be able to use a array of field names in the rule definition, so one rule per field. So this wouldn't work:

```php
$v = new Valitron\Validator(array());
$v->rule('required', array('name', 'email'))->message('{field} is required')->label('Name');
$v->validate();
```

However we can use a array of labels to solve this issue by simply adding the `labels` method instead:

```php
$v = new Valitron\Validator(array());
$v->rule('required', array('name', 'email'))->message('{field} is required');
$v->labels(array(
    'name' => 'Name',
    'email' => 'Email address'
));
$v->validate();
```

This introduces a new set of tags to your error language file which looks like `{field}`, if you are using a rule like `equals` you can access the second value in the language file by incrementing the field with a value like `{field1}`.

## Running Tests

The test suite depends on the Composer autoloader to load and run the
Valitron files. Please ensure you have downloaded and installed Composer
before running the tests:

1. Download Composer `curl -s http://getcomposer.org/installer | php`
2. Run 'install' `php composer.phar install`
3. Run the tests `phpunit`

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Make your changes
4. Run the tests, adding new ones for your own code if necessary (`phpunit`)
5. Commit your changes (`git commit -am 'Added some feature'`)
6. Push to the branch (`git push origin my-new-feature`)
7. Create new Pull Request
8. Pat yourself on the back for being so awesome


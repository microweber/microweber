# Microweber Forms Builder

 
To add new element, you need to create a new class that implements `MicroweberPackages\FormBuilder\Elements\ElementInterface` and implement the `render()` method.

```php
// In YourServiceProvider.php in register method add this
public function register()
    {
    $this->app->resolving(\MicroweberPackages\FormBuilder\FormElementBuilder::class, 
    function (\MicroweberPackages\FormBuilder\FormElementBuilder $formElementBuilder) {
        $formElementBuilder->extend('my-element', function () {
            return new \MyPackage\MyElement();
        });
    });
}
```


## Building form elements

```php

/**
* @var \MicroweberPackages\FormBuilder\FormElementBuilder $formBuilder
*/
$formBuilder = app()->make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
print $formBuilder->make('my-element', 'My Element')
```

# Microweber Package to set custom data to any model



## Operation

You can set and get custom data which is related to any model 


### Setting Content Data 


```php
$product = new Product();
$product->title = 'Test product with content data';
$product->setContentData(['phone' => 'nokia', 'sku' => 5]);
$product->save();
```



### Getting Content Data  

```php
$product = Product::find($prod_id);
$contentData = $product->getContentData();
print $contentData['phone'];
print $contentData['sku'];
```




### Deleting Content Data  

```php
$product = Product::find($prod_id);
$product->deleteContentData(['phone']);
$product->save();
```



### Searching content by Content Data  

```php
$product = Product::whereContentData(['sku' => '5'])->first();
```
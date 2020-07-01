# Database Manager ![Build Status](https://api.travis-ci.org/microweber-packages/microweber-database-manager.svg?branch=master)
https://travis-ci.org/microweber-packages/microweber-database-manager

## Database SAVE

Allows you to save data to ANY table in the database.

### Summary

    db_save($table, $data);

This is one of the core Microweber function and most of the other _save_ functions are really wrappers to this one. It allows you to save data anywhere in any db table.


**Note: This is raw function and does not validate data input or user permissions. **

This function is dangerous and its not meant to be used directly in templates or module views. Better make your own wrapper functions to this one which validates the input  and use them when required.



### Usage
```php
$table='content';

$data = array(); 
$data['id'] = 0; 
$data['title'] = 'My title'; 
$data['content'] = 'My content'; 
$data['allow_html'] = true; //if true will allow you to save html 

$saved = db_save($table,$data);
```

### Parameters

| parameter            | description        |
| -------------  |:-------------|
| `$table`            |  the name of your database table	 | 
| `$data`            |  a key=>value array of your data to save, key must the the name of the db field  | 
 

By default this function removes html tags. In order to save plain html you must set `$data['allow_html'] = true;` in your data array

 
 
#######################

## Database GET

Allows you to get data from ANY table in the database, and caches the result.

### Summary

   db_get($params);

### Usage

    $content = db_get('table=content');

    foreach ($content as $item) {
        print $item['id'];
        print $item['title'];
        print $item['url'];
        print $item['description'];
        print $item['content'];

    } 

### Parameters

You can pass parameters as `string` or as `array`. They can be field names in the database table defined with the `table` parameter.

| parameter            | description        |   usage        |
| -------------  |:-------------|:-------------|
| `table`            |  the name of your database table	 |  db_get('table=content') |
| `single`            |  if set to true will return only the 1st row as array	 | db_get('table=content&id=5&single=true') |
| `orderby`            | you can order by any field name		 |  db_get('table=content&orderby=id desc') |
| `count`            | if set to true it will return the results count		 |  db_get('table=content&count=true') |
| `limit`            | set limit of the returned dataset, use "no_limit" to return all results			 |  db_get('table=content&limit=10') |
| `page`            | set offset of the returned dataset				 |  db_get('table=content&limit=10&page=2') |
| `page_count`            | returns the number of result pages				 |  db_get('table=content&limit=10&page_count=true') |
| `$fieldname`            | you can filter data by passing your fields as params	|  db_get('table=some_table&my_field=value') |
| `keyword`            | if set it will search for keyword		| db_get('table=content&limit=10&keyword=my title') |
| `nocache`            |  if set to true will skip caching the db result		| db_get('table=content&nocache=true') |



 
### Get everything

     //get 5 users
    $users = db_get('table=users&limit=5');

    //get next 5 users
    $users = db_get('table=users&limit=5&page=2');

    //get 5 categories
    $categories = db_get('table=categories&limit=5');

    //get 5 comments
    $comments = db_get('table=comments&limit=5');

 
#######################

## Database DELETE

db_delete — deletes a record from a database table

### Summary

    db_delete($table_name, $id = 0, $field_name = 'id')

### Return Values

`true` or `false` if the item is not deleted

### Usage

    $category_id = 5;

    $delete = db_delete('categories', $category_id);

 
 
 
 # Saving and getting module data



## Defining Schema
When installing a module Microweber checks the `config.php` file for the `$config['tables']` array.
Each key represents a table name and its value is an array of column definitions.
The ID column is automatically created and all columns are nullable by default.

*Example* `userfiles/modules/paintings/config.php`
```
$config = array();
$config['name'] = "My Paintings";
$config['author'] = "Pablo Picasso";
$config['ui'] = true; 
$config['ui_admin'] = true; 
$config['position'] = "98";
$config['version'] = "0.01";
$config['tables'] = array(
        'paintings' => array(
            'id' => 'integer',
        	'name' => 'string',
        	'price' => 'float',
        	'description' => 'text',
        	'created_by' => 'integer',
            'created_at' => 'dateTime',
        )
);
```

## Custom Tables

For more options of the data storage you can read here.

[Read more about making custom tables here](modules_schema.md).


### Getting and saving data

You can use the [db_get](../functions/db_get.md "db_get"), [db_save](../functions/db_save.md "db_save") and [db_delete](../functions/db_delete.md "db_delete") functions to work with data from your those tables.

```
// Getting
$data = db_get("table=paintings")

// Saving
$save = array(
	'name' => 'Mona Lisa',
	'description' => 'Paiting by Leonardo da Vinci'
);
$id = db_save('paintings', $save);

// Deleting
db_delete('paintings', $id);

```



#### Create
The `db_save` function accepts table name as first argument and row data as a second argument. In order to create rows in a table simply don't specify an ID.




*Example*
```
$data = array(
	'name' => 'Three Musicians',
	'price' => 2700,
	'description' => 'My greatest work'
);
db_save('paintings', $data);
```

#### <a name="crud-read"></a> Read
Call the `db_get` function and set the `table` key in the argument array to retrieve rows from a given database table.

*Example*
```
$rows = db_get(array('table' => 'paintings'));
```

Set the `single` key to `true` in the argument array for the function to return a single row.
Any non-reserved key name is treated as a `WHERE` condition for given column name. Reference the [`db_get` function docs](../functions/db_get.md) for more details.

*Example*
```
$row = db_get(array(
	'table' => 'paintings',
	'name' => 'Three Musicians',
	'single' => true
	));
```

Alternatively the above query can be written like that `db_get('table=paintings&name=Three Musicians&single=true')`



#### Update
If the `db_save` function receives an array containing an `id` key it performs an update operation on the corresponding row.

*Example*
```php
// Gets single row with id = 3
$row = db_get(array(
	'table' => 'paintings',
	'id' => 3,
	'single' => true
	));
$row['title'] = 'My Awesome Painting';
echo 'Updating row with ID ', $row['id'];
db_save('paintings', $row);
```

#### Delete
The `db_delete` function returns `true` after successfully deleting row with a specified ID.

*Example*
```
db_delete('paintings', $id = 3);
```

## Advanced Queries
Reference the [`db_get`](../functions/db_get.md) and [`db_save`](../functions/db_save.md) documentation pages for a list of all available parameters.

XmlStreamer [![Build Status](https://travis-ci.org/prewk/XmlStreamer.svg?branch=master)](https://travis-ci.org/prewk/XmlStreamer)
==========

# About

Written by oskar.thornblad@gmail.com.

Contributions from:

 * Valiton GmbH
 * Michael Härtl

Licensed under the [MIT license](http://opensource.org/licenses/MIT).

# Installation
Install with composer by adding the following to your composer.json file:

```json
{
    "require": {
        "prewk/xml-streamer": "dev-master"
    }
}
````

Then, run `composer install` (assuming you have [composer](https://getcomposer.org/) installed.)

# Usage

Extend the class and implement the `processNode()` method.

## Example


```php
<?php
class SimpleXmlStreamer extends \Prewk\XmlStreamer
{
    public function processNode($xmlString, $elementName, $nodeIndex)
    {
        $xml = simplexml_load_string($xmlString);
        $something = (string)$xml->Something->SomethingElse->ReadThis;
        echo "$nodeIndex: Extracted string '$something' from parent node '$elementName'\n";     
        return true;
    }
}

$streamer = new SimpleXmlStreamer("myLargeXmlFile.xml");
if ($streamer->parse()) {
    echo "Finished successfully";
} else {
    echo "Couldn't find root node";
}
```

## Advanced example

To improve performance on DB inserts you can also make use of the `chunkCompleted()` method.
It gets called after a chunk of data was processed.

```php
<?php
class SimpleXmlStreamer extends \Prewk\XmlStreamer
{
    protected $pdo;
    protected $sql = array();
    protected $values = array();

    /**
     * Called after the constructor completed class setup
     */
    public function init()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=test', 'user','pass');
    }

    public function processNode($xmlString, $elementName, $nodeIndex)
    {
        $xml = simplexml_load_string($xmlString);
        $this->sql[] = '(?,?,?)';
        $this->values[] = (string)$xml->name;
        $this->values[] = (string)$xml->email;
        $this->values[] = (string)$xml->phone;
    }

    /**
     * Called after a file chunk was processed (16KB by default, see constructor)
     */
    public function chunkCompleted()
    {
        if($this->sql===array()) {
            return;
        }
        $command = $this->pdo->prepare('INSERT INTO mytable VALUES '.implode(',',$this->sql));
        $command->execute($this->values);

        $this->sql = $this->values = array();
    }
}
```


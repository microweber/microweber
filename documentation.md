Microweber Documentation
===

Microweber is a new generation cms and web development framework. 

Our goal is to make the creation of websites and web apps easy and enjoyable to the developer and the end user. 


Microweber can be used as a CMS to manage your website or simply as a MVC framework that can power your custom applications. 


Basicks

folder structure:
 






# Advanced Functionality

## Hooks
### Rerouting

So let's get back to coding. You can declare a page obsolete and redirect your visitors to another site/page:-

``` php
$f3->route('GET|HEAD /obsoletepage',
    function($f3) {
        $f3->reroute('/newpage');
    }
);
```
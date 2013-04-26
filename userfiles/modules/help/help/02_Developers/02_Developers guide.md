 

This quick guide will only show you the very basics of the Microweber architecture and you will continue to learn more in the next chapters.

 

# Basics

Microweber (MW) has many powerful features, but its basic operation is quite simple. When a request is made to a page, the system determines what page to display and which template to use.

The template is standard HTML and there are no restrictions on what it can contain. You can also use PHP freely in your templates.

How MW displays information depends on how you structure your content.

## Content architecture
The content is divided in few content types. With them you can organize your website with the structure you need.

There are few basic content types that we have by default.

1. The ***Pages*** are the main content items for your website.
2. Pages have ***Categories***
3. *Categories* are used to store ***Posts*** or ***Products***


 ![Image](_img/content_architecture.png?raw=true)




## Template basics

The templates are set of files that offers you different ways to display information. They are simple PHP files and you can use any php function in them.

You can make any html or php page a template by putting it into the template directory and loading it into the browser. 

####[Working with templates guide](02.1-Working-with-templates)

 

# Live edit
You can define editable regions in your template where the user will be able to type text and *Drag and Drop* modules


Example:
```html
<div class="edit"  field="your_region_name" rel="content">
      <div class="element">Edit your content</div>
</div>
```

To define your editable region you must set few parameters on the html element in your template

* You must add class "edit"
* Add attribute *field="some name"* and set the name of your field.
* Add attribute *rel="content"* and set the scope of your field.
* The main editable region of the page must have attribute *field="content"*

####[Working with editable regions guide](02.1-Live-edit)



# Modules


The Mw modules will help you to make easy modifications and add functionality to your pages.

Every module is a PHP script or a program that executes when the user have dropped it into a page.
It can be very simple, or it can be hugely complex, there are no limitations.

 ![Image](_img/banner_modules.png?raw=true)

The module works as a stand alone script and its isolated from the global PHP scope, but it have access to all Microweber functions.

It can also have editable regions and the user can edit the text into it.


Modules are loaded with the \<module /> tag:
```html

      <module type="pictures" />

```

####[See the Modules guide](02.3-Modules)


#PHP API

Microweber have many powerful functions you can use

Check out the [PHP API](PHP-API)

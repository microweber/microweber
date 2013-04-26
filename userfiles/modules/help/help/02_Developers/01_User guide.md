## Developers guide


 


## The Microweber architecture
This quick guide will only show you the very basics of the Microweber architecture and you will continue to learn more in the next chapters.

### Content architecture
The content is divided in few content types. With them you can organize your website with the structure you need.

There are few basic content types that we have by default.

1. The ***Pages*** are the main content items for your website.
2. Pages have ***Categories***
3. *Categories* are used to store ***Posts*** or ***Products***


 ![Image](../wiki/_img/content_architecture.png?raw=true)




### Template basics

The templates are set of files that offers you different ways to display information.


**Directory structure**

The templates are stored in the following folders

	/userfiles/templates   //the main directory for the templates
	/userfiles/templates/My theme   // the  directory for your theme
	/userfiles/templates/My theme/layouts   // the  directory for your theme's layouts


**Requred template files**

Each template must have the following files

	header.php
	footer.php
	index.php
	layouts/clean.php

Optional files are

	layouts/inner.php
	layouts/inner.php



### Live edit
You can define editable regions in your template where the user will be able to type text and *Drag and Drop* modules


Example:
```html
<div class="edit"  field="content" rel="content">
      <div class="element">Edit your content</div>
</div>
```

To define your editable region you must set few parameters on the html element in your template

* You must add class "edit"
* Add attribute *field="some name"* and set the name of your field.
* Add attribute *rel="content"* and set the scope of your field.

 
**The *rel* attribute**

The *rel* attribute is responsible for the scope of the displayed content.

It is used, so you can have editable regions that can display different content, depending on the page they are, or in the module they are.


Supported values are for the *rel* attribute :

* *rel="global"* - Put them on your header and footer regions. Changes that the user makes in those fields are global and visible for the whole website.
* *rel="content"* - Used to define editable regoins which are dynamic and the content in them is different for every *page* or *post* . Use it over your main content regions in your template.
* *rel="page"* - Content you put in those regions is different for every page and subpage, but its inherited in the posts inner layouts.
* *rel="post"* - use it to define editable regoins which are different for each post. Typically use them in your *layouts/inner.php* file.
* *rel="inherit"* - All the changes the user makes in those regions are displayed on all sub-pages and posts
* *rel="any_custom_value"* - You can also use any value as rel parameter.  This way you can define custom scope of your editable regions.


 Changes that the user makes in those fields are global and visible for the whole website.






























Other directories


    /application - the MW system directory where the system files are located.
    /application/functions - the main functions of MW
    /userfiles/modules - the directory where the modules are installed
    /userfiles/templates - the directory for the templates


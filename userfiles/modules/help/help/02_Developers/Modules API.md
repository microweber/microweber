
#Modules parameters


#Content modules

##Module: pages
![module_pages.png](_img/module_pages.png?raw=true "Pages module")


Allows you to show pages navigation

Usage of the `pages` module

```html
 <module type="pages" />
 
or with parameters

 <module type="pages" include_categories="true"  maxdepth="2" />
```
Parameters

|parameter  | description |  usage|
|--------------|--------------|--------------|
|`content_id`  | the id of the parent page | ` <module type="pages" content_id="2" />` |
|`maxdepth`  | the depth of the navigation tree | ` <module type="pages" maxdepth="1" />` |
|`include_categories`  | if set will also show the categories for each page | ` <module type="pages" include_categories="1" />` |
|`ul_class`  | you can set the CSS class name for the UL  | ` <module type="pages" ul_class="nav" />` |
|`li_class`  | you can set the CSS class name for the LI  | ` <module type="pages" li_class="nav-item" />` |
|`link`  | allows you to set custom link of each page  | ` <module type="pages" link="somewhere/{title}/{id}" />` |
|`template`  | the name of the template to use  | ` <module type="pages" template="stacked_pills" />` |
  
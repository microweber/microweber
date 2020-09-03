# Helper functions 

This package contains some simple helper functions that are used by other packages


## Installation

`composer require microweber-packages/microweber-helpers`


### URL Functions 


|  Function name | Description  |
|---|---|
| `site_url()`  |  returns the current site url for example http://localhost/ |
| `is_https()`  |  returns `true` if the site is opened with https |
| `is_ajax()`  |  returns `true` if the request is ajax |
| `url_current()`  |   returns URL of the current page |
| `url_segment($seg)`  |   returns URL segment  |
| `parse_params('param=value&param2=value2')`  | parses get parameters from string and returns them as `array` |


 
 
 
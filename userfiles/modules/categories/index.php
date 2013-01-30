
<?php
if(isset($params['class'])){
unset($params['class']);
}
     $params['ul_class'] = 'nav nav-list';

category_tree($params); ?>


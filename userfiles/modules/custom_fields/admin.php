<?php
must_have_access();
echo view('microweber-module-custom-fields::admin-module',
    ['params'=>$params]
);
?>

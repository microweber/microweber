<?php
sleep(1); 
echo json_encode(array(
    'files' => $_FILES,
    'post' => $_POST
));
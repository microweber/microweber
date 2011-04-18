<?php

if (count($_POST)) {
    if (isset($_POST["_method"]) && "put" == $_POST["_method"]) {
        print "PUT";
    } else {
        print "POST";
    }
} else {
    print "GET";
}

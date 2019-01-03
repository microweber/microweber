<?php

include 'functions/sender_functions.php';
include 'functions/template_functions.php';
include 'functions/subscriber_functions.php';
include 'functions/campaign_functions.php';
include 'functions/list_functions.php';

function array_search_multidimensional($array, $column, $key){
    return (array_search($key, array_column($array, $column)));
}

event_bind('website.privacy_settings', function () {
	print '<h2>Newsletter settings</h2><module type="newsletter/privacy_settings" />';
});

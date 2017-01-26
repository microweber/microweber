<?php
$config = array();
$config['name'] = "Calendar";
$config['author'] = "nick@easy-host.uk";
$config['ui'] = true; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = true; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.1;

/*
CREATE TABLE IF NOT EXISTS `calendar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `startdate` varchar(48) NOT NULL,
  `enddate` varchar(48) NOT NULL,
  `allDay` varchar(5) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
*/

$config['tables'] = array(
    "calendar" => array(
        '$id' => "integer",
        'content_id' => "integer",
        'title' => "text",
		'startdate' => "text",
		'enddate' => "text",
		'allDay' => "text",
		'description' => "text",
    )
);
<?php
include('../common.php');
header("Content-type: text/octect-stream");
header("Content-Disposition: attachment;filename=nexty");

$absolute_url = $config['url'];
list($username,$password) = $sql->getList("SELECT username,password FROM ${config['db_prefix']}User WHERE id='$_SESSION[user]'");

print <<<END
#!/bin/sh

#No arguments
if [ $# -eq 0 ] ; then
curl --cookie-jar /tmp/nexty-cookies.txt --data-ascii "layout=cli&auth_username=$username&auth_password=$password" "$absolute_url"

#The task is the argument
else
curl --cookie-jar /tmp/nexty-cookies.txt --data-ascii "task=`echo $@|tr ' ' '+'`&layout=cli&auth_username=$username&auth_password=$password" "${absolute_url}tasks/add.php"
fi
END;

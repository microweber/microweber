#!/bin/bash
cd /home/update/public_html_git 
git fetch origin && git reset --hard origin/master 
git pull origin master --force 

rsync -av  --delete --exclude 'cache' --exclude '.git' --exclude 'application/config.php' /home/update/public_html_git/*  /home/update/public_html/

chmod -Rv 755 /home/update/public_html/

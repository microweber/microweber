#!/bin/sh
#cd /home/microweber/domains/update.microweber.me/public_html &&  git clean -f && git pull -f origin master
cd /home/microweber/domains/update.microweber.me/public_html &&  git fetch origin && git reset --hard origin/master && git pull origin master --force
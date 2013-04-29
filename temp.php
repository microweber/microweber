 C:\xampp\apache\bin\ab.exe -n 1000 -c 50 http://192.168.0.3/1k/apidocs/docs/core/content/get_content.php

 C:\xampp\apache\bin\ab.exe -n 1000 -c 50 http://192.168.0.3/1k/home

 C:\xampp\apache\bin\ab.exe -n 1000 -c 50 http://api.microweber.net/home

 C:\xampp\apache\bin\ab.exe -n 200 -c 20 http://api.microweber.net/home

 C:\xampp\apache\bin\ab.exe -n 1000 -c 50 http://api.microweber.net/temp.php

C:\xampp\apache\bin\ab.exe -n 200 -c 20 http://serv.microweber.net/
 C:\xampp\apache\bin\ab.exe -n 2000 -c 500 http://serv.microweber.net/home
C:\xampp\apache\bin\ab.exe -n 200 -c 20 http://localhost/wordpress/?p=4
 C:\xampp\apache\bin\ab.exe -n 2000 -c 100 http://54.243.124.15/
 C:\xampp\apache\bin\ab.exe -n 2000 -c 500 http://clould.microweber.net/

 C:\xampp\apache\bin\ab.exe -n 2000 -c 500 http://help.microweber.net/how-to-install-mw

 C:\xampp\apache\bin\ab.exe -n 200 -c 50  http://pecata/Microweber/how-to-install-mw?debug=1

====================================================================

Joyent

sdc-createmachine -a microweber -u https://us-east-1.api.joyentcloud.com -n getting-started

sdc-listmachinesnapshots d8abe9f6-020a-474d-a5ff-8ca3a97b9503

sdc-getmachinesnapshot -m d8abe9f6-020a-474d-a5ff-8ca3a97b9503 20130307141540

StartMachineFromSnapshot

sdc-startmachinefromsnapshot -n just-booted d8abe9f6-020a-474d-a5ff-8ca3a97b9503

sdc-startmachinefromsnapshot -n just-booted d8abe9f6-020a-474d-a5ff-8ca3a97b9503 20130307141540

sdc-getmachinemetadata -c d8abe9f6-020a-474d-a5ff-8ca3a97b9503


sm-create-vhost -s apache temp2.microweber.net /home/admin/public

sdc-setup https://us-east-1.api.joyentcloud.com


export SDC_CLI_URL=https://us-east-1.api.joyentcloud.com
export SDC_CLI_ACCOUNT=microweber
export SDC_CLI_KEY_ID=deployer







==============


rsync -e ssh  -avz  --exclude '.git' --exclude 'cache'   api@api.microweber.net:/home/api/public_html /home/api/public_html

rsync -avz --delete api@54.243.113.235:/home/api/public_html/ /home/api/public_html/


rsync -e ssh  -avzp  api@api.microweber.net:/home/api/.ssh/ /home/api/.ssh/


rsync -avz --progress -e "ssh -i /home/api/.ssh/id_dsa.pub" api@54.243.113.235:/home/api/public_html/ /home/api/public_html/

cat .ssh/id_dsa.pub | ssh api@api.microweber.net 'cat >> .ssh/authorized_keys'

scp api@api.microweber.net:/home/api/public_html/ /home/api/public_html/


ssh api@api.microweber.net
cat ~/.ssh/id_dsa.pub.transferred >> ~/.ssh/authorized_keys
rm ~/.ssh/id_dsa.pub.transferred
exit

rsync -e ssh  --verbose  --progress --stats --compress  --exclude '.git' --exclude 'cache'   api@api.microweber.net:/home/api/public_html /home/api/public_html

rsync -e ssh  --verbose  --progress --stats --compress  --exclude '.git' --exclude 'cache' --exclude 'history' --exclude 'userfiles/media'   --exclude '?'   /home/api/master /home/api/public_html

54.243.113.235

rsync --verbose  --progress --stats --avzp   --exclude '.git' --exclude 'cache' --exclude '?' --exclude 'cache' --exclude 'history' --exclude 'userfiles/media'   /home/api/master/ /home/api/public_html/

rsync --verbose  --progress --stats --compress -r --exclude '.git' --exclude 'cache' --exclude '?' --exclude 'cache' --exclude 'history' --exclude 'userfiles/media'   /home/api/master/ /home/api/public_html/

rsync --verbose  --progress --stats --compress --update -r --exclude '.git' --exclude 'cache' --exclude '?' --exclude 'cache' --exclude 'history' --exclude 'userfiles/media'   /home/api/master/ /home/api/public_html/


rsync --verbose  --progress --stats --compress -r --exclude '.git'  --exclude '?' --exclude 'cache/mw_cache_' --exclude 'history' --exclude 'userfiles/media'   api@54.243.113.235:/home/api/public_html/ /opt/local/share/httpd/htdocs/
rsync --verbose  --progress --stats --compress -r --exclude '.git'  --exclude '?' --exclude 'cache/mw_cache_' --exclude 'history'     api@54.243.113.235:/home/api/public_html/ /opt/local/share/httpd/htdocs/




 --stats. So


=======================================================================================================================

United States
Business Name: 	  	None


Bank Account: 	  	Checking (Confirmed)
Routing Number: 325272063
Bank Account Number: 076792569968188
Balance: 	  	8000.00 USD


 Credit Card: 	  	Visa   4887380643164367
 Exp Date:  11/2017


Test Account:		boksio_1352898155_biz@gmail.com 	Nov 14, 2012 05:03:45 PST
API Username: 	boksio_1352898155_biz_api1.gmail.com
API Password: 	1352898225
Signature: 	A3gEt3WXnVKeHHoeh0uLGXFuaRVvAxtzdKdF0t46v02YJBxqmFALdIHJ

Blez830106758z
==================================

memcache 54.243.113.235

vi /etc/sysconfig/memcached


  /etc/sysconfig/memcached
Change

# set ram size to 2048 - 2GiB

    CACHESIZE="4096"
Type the following command:

memcached-tool IP_ADDRESS:Port
memcached-tool 127.0.0.1:11211 display
memcached-tool 127.0.0.1:11211 stats
54.243.113.235

http://54.243.113.235


For blocking the port for all ip's except one you can issue an iptables command like:

iptables -A INPUT -s 2.2.2.2/32 -p tcp --destination-port 11211 -j ALLOW
iptables -A INPUT -s 0.0.0.0/0 -p tcp --destination-port 11211 -j DROP
or:

iptables -A INPUT -s !2.2.2.2/32 -p tcp --destination-port 11211 -j DROP
Also as I understand your webserver and memcached server are on the same machine? If so the














apigen --source ~/nella/Nella --source ~/doctrine2/lib/Doctrine --source ~/doctrine2/lib/vendor --source ~/nette/Nette --skip-doc-path "~/doctrine2/*" --skip-doc-prefix Nette --exclude "*/tests/*" --destination ~/docs/ --title "Nella Framework"



rsync --progress -r --perms --chmod=777 --stats --exclude '.git' --exclude 'cache' --exclude '?' --exclude 'cache' --exclude 'history' --exclude 'userfiles/media'  --exclude '/backup/'  --exclude '/config.php'  /cygdrive/c/xampp/htdocs/Microweber /cygdrive/c/xampp/htdocs/mw2

apigen --source  C:\xampp\htdocs\1k\application --skip-doc-path "~/cache/*" --skip-doc-prefix Nette --exclude "*/tests/*" --destination C:\xampp\htdocs\1k\apigen --title "MW Framework"


apigen --config C:\xampp\htdocs\1k\apigen.ini






ab -n 100 -c 10 -r "http://localhost/1k/title-20121011104847"


ab -n 100 -c 10 "http://abv.bg/"


ab -n 100 -c 10 "http://microweber.com"..



ab -n 100 -c 10 -  "http://pecata/wordpress"
ab -n 100 -c 10 -  "http://localhost/wordpress/?p=1"

ab -n 100 -c 10 -  "http://localhost/wordpress/?p=1"

ab -n 100 -c 10 -r "http://pecata/1k/peter?test_cookie=9"

ab -n 100 -c 10 -r "http://pecata/1k/peter"

ab -n 100 -c 10 -r "http://pecata/1k/title-20121011104847?test_cookie=9"
ab -n 1000 -c 50 -r "http://pecata/1k/metodi-page"



ab -n 1000 -c 100 -r "http://pecata/1k/sdfsdfsdfsdfsd"



ab -n 100 -c 10 -r "http://pecata/1k/title-20121011104847"
ab -n 100 -c 10 -r "http://en.blog.wordpress.com/"

ab -n 100 -c 10 -r "http://pecata/1k/title-20121011104847"

C:\xampp\apache\bin\ab.exe -n 100 -c 10 -r "http://192.168.0.3/1k/my-product?test=1"


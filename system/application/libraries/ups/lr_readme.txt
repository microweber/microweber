
Labels receiver, this is half/class, in most cases you need to implement your
config.php       - all configuration is done here !must be in same directory as getupsimage.php (in included from inside of getupsimage.php )
callback.handler.php - thiss is response from ups, handler (optimal) may update your order status with tracking number here


PATH_TO_THISFILE - constant, where this getupsimage.php is located (i.e. include/ups.com/labels by default)
UPS_USERID	 - constant, your registered ups accout userid
UPS_PASSWORD     - constant, your registered ups account password
$tempDir4Cookies - variable, you must set to path, where cookie file will be stored (full path, default is $_SERVER['DOCUMENT_ROOT']."/temp")
$labelsDir	 - vadiable, you must set to path, where received labels will be cached (full path, default is $_SERVER['DOCUMENT_ROOT']."/temp/labels")
Also if you wish, you may update the labels response receiver (line 26 of code)


XML & Acces Keys do not required for labels reveiver.

FILE USAGE :

http://www.yoursite.com/include/ups.com/labels/getupsimage.php?Company_to=MY%20COMPANY&selectedService=003&PostalCode_to=65625&City_to=Cassville&State_to=Missouri&Address1_to=Addr1%20ADDR2&Country_to=US&package1DeclaredValue=1496&package1Weight=2&OrderId=126

With $_GET Parameters you must pass (sample values):

Company_to=MY%20COMPANY

selectedService=003

PostalCode_to=65625

City_to=Cassville

State_to=Missouri

Address1_to=Addr1%20ADDR2

Country_to=US

package1DeclaredValue=1496

package1Weight=2

OrderId=126


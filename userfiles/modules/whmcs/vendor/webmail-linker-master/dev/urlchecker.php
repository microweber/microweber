<?php

/**
 * Webmail Linker – Collection of Email Providers' Webmail Sites
 * 
 * @link    https://github.com/thomasbachem/webmail-linker
 * @license http://opensource.org/licenses/MIT
 * @author  Thomas Bachem <mail@thomasbachem.com>
 */

require(__DIR__ . '/../src/php/WebmailLinker.php');

// You can provide the index number or name of a specific provider
// to (re-)check or provide none to check all providers
$providerToCheck = !empty($argv[1]) ? $argv[1] : null;

// Whether to print verbose cURL output (for debugging)
$verbose = !empty($argv[2]) ? true : false;

// As some servers use user agent sniffing
$userAgent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.103 Safari/537.36';

if(!extension_loaded('curl')) {
	exit('Needs the PHP cURL extension.');
}

$errors = 0;

$providers = (new WebmailLinker())->getProviders();
$total = count($providers);

if($providerToCheck) {
	print 'Checking provider ' . (is_numeric($providerToCheck) ? $providerToCheck : '"' . $providerToCheck . '"') . '...' . "\n";
	print "\n";
} else {
	print 'Checking ' . $total . ' providers...' . "\n";
	print "\n";
}

foreach($providers as $i => $provider) {
	if($providerToCheck) {
		if((is_numeric($providerToCheck) && $i + 1 != $providerToCheck) || (!is_numeric($providerToCheck) && $provider['name'] != $providerToCheck)) {
			// Skip this provider as it's not the one to check
			continue;
		}
	}

	print "\x1B[7m";
	print '(' . str_pad($i + 1, strlen($total), '0', STR_PAD_LEFT) . '/' . $total . ')';
	print "\x1B[27m";
	print "\x1B[1m";
	print ' ' . $provider['name'] . "\n";
	print "\x1B[22m";
	
	foreach(array('URL:  ' => $provider['url'], 'Icon: ' => $provider['icon']) as $label => $url) {
		print $label . $url . ' - ';
		
		$tries = 0;
		do {
			// Sadly we cannot use HEAD requests because some servers fail to handle those
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_VERBOSE, $verbose);
			curl_setopt($curl, CURLOPT_SSLVERSION, $tries == 0 ? 3 : 2); 
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, true);
			curl_setopt($curl, CURLOPT_COOKIEFILE, '');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
			$headers = curl_exec($curl);
		// Retry with SSLv2 if a SSL connection error occurred
		} while(++$tries < 2 && ($errno = curl_errno($curl)) && ($errno === CURLE_SSL_CONNECT_ERROR || $errno === CURLE_SSL_CACERT));

		if($headers && preg_match('#^HTTP/1[.][01] (?<status>[0-9]+)#', $headers, $m)) {
			if($m['status'] == 200 || $m['status'] == 302 || $m['status'] == 303 || $m['status'] == 307) {
				// Green: Ok or Temporary Redirect
				print "\x1B[32m" . $m['status'] . "\x1B[39m";
			} elseif($m['status'] == 301 || $m['status'] == 308 || $m['status'] == 503) {
				// Yellow: Permanent Redirect or Temporary Unavailable
				print "\x1B[33m" . $m['status'] . "\x1B[39m";
			} else {
				// Red: Anything else
				print "\x1B[31m" . $m['status'] . "\x1B[39m";
				++$errors;
			}
		} else {
			print "\x1B[31m" . curl_error($curl) . ' (' . curl_errno($curl) . ')' . "\x1B[39m";
			++$errors;
		}
		print "\n";

		curl_close($curl);
	}

	print "\n";
}

exit($errors ? 1 : 0);
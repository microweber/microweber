<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/9/2021
 * Time: 11:46 AM
 */

namespace MicroweberPackages\Package\Helpers;


use Composer\Util\RemoteFilesystem;

class ComposerRemoteFilesystem extends RemoteFilesystem
{
    /**
     * Get contents of remote URL.
     *
     * @param string $originUrl The origin URL
     * @param string $fileUrl The file URL
     * @param resource $context The stream context
     *
     * @return string|false The response contents or false on failure
     */
    protected function getRemoteContents($originUrl, $fileUrl, $context, array &$responseHeaders = null)
    {

        if (function_exists('curl_init')) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fileUrl); //set url
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //do not show in browser the response
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow any redirects


            curl_setopt($ch, CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$headers, &$responseHeaders) {
                    $responseHeaders = ($header);
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) // ignore invalid headers
                        return $len;

                    $headers[strtolower(trim($header[0]))][] = trim($header[1]);

                    return $len;
                }
            );

            $result = curl_exec($ch);
            curl_close($ch);
            $responseHeaders = array();
            return $result;


        } else {
            try {
                $e = null;
                $result = file_get_contents($fileUrl, false, $context);
            } catch (\Throwable $e) {
            } catch (\Exception $e) {
            }

            $responseHeaders = isset($http_response_header) ? $http_response_header : array();

            if (null !== $e) {
                throw $e;
            }

            return $result;
        }
    }
}
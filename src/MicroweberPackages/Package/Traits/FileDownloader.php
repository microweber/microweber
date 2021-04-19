<?php

namespace MicroweberPackages\Package\Traits;

trait FileDownloader
{
    /**
     * Copy remote file over HTTP one small chunk at a time.
     *
     * @param $url The full URL to the remote file
     * @param $dest The path where to save the file
     */
    public function downloadBigFile($url, $dest)
    {
        $options = array(
            CURLOPT_FILE => is_resource($dest) ? $dest : fopen($dest, 'w'),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_URL => $url,
            CURLOPT_FAILONERROR => true, // HTTP code > 400 will throw curl error
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $return = curl_exec($ch);

        if ($return === false) {
            return curl_error($ch);
        } else {
            return true;
        }
    }

}

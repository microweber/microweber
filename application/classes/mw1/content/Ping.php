<?php
namespace mw\content;


class Ping
{

    /**
     * Async caller for content_ping_servers
     * @category Content
     * @package Content
     * @subpackage Advanced
     * @uses content_ping_servers()
     * @see content_ping_servers();
     */
    static function content_ping_servers_async()
    {
        $scheduler = new \mw\utils\Events();
        $scheduler->registerShutdownEvent("content_ping_servers");
    }


    /**
     * Pings the bots with the new pages and posts
     * @category Content
     * @package Content
     * @subpackage Advanced
     * @uses is_fqdn()
     * @uses get_content();
     */
    static  function content_ping_servers()
    {

        if ($_SERVER ["SERVER_NAME"] == 'localhost') {
            return false;
        }

        if ($_SERVER ["SERVER_NAME"] == '127.0.0.1') {
            return false;
        }

        $fqdn = is_fqdn(site_url());


        if ($fqdn != false) {
            $q = \mw\Content::get('is_active=y&is_deleted=n&is_pinged=n&limit=5&cache_group=content/ping');

            //$q = get_content('is_active=y');
            $server = array(
                'Google' => 'http://blogsearch.google.com/ping/RPC2',
                'Feed 2' => 'http://ping.pubsub.com/ping',
                'Feed 3' => 'http://api.my.yahoo.co.jp/RPC2');


            if (isarr($q)) {


                foreach ($server as $line_num => $line) {

                    $line = trim($line);


                    if ($line != '') {

                        foreach ($q as $the_post) {

                            $pages = array();
                            $pages [] = $the_post ['title'];
                            $pages [] = content_link($the_post ['id']);

                            $save = array('id' => $the_post ['id'], 'is_pinged' => 'y', 'debug' => 'y');
                            mw_var('FORCE_SAVE_CONTENT', MW_DB_TABLE_CONTENT);
                            mw_var('FORCE_SAVE', MW_DB_TABLE_CONTENT);
                            mw_var('FORCE_ANON_UPDATE', MW_DB_TABLE_CONTENT);
                            save(MW_DB_TABLE_CONTENT, $save);
                            if (function_exists('xmlrpc_encode_request') and function_exists('stream_context_create') and function_exists('xmlrpc_decode')) {
                                $request = xmlrpc_encode_request("weblogUpdates.ping", $pages);
                                $context = stream_context_create(array('http' => array(
                                    'method' => "POST",
                                    'header' => "Content-Type: text/xml",
                                    'content' => $request
                                )));
                                $file = @file_get_contents($line, false, $context);
                                $response = xmlrpc_decode($file);
                                if ($response && xmlrpc_is_fault($response)) {
                                    // trigger_error("xmlrpc: $response[faultString] ($response[faultCode])");
                                } else {
                                    //print_r($response);
                                }
                            }


                        }

                    }
                }
                $curl = new \mw\utils\Curl();
                $curl->url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . site_url('sitemap.xml');
                $curl->timeout = 3;
                $result1 = $curl->execute();
                mw('cache')->delete('content/ping');
            }
        }
    }
}
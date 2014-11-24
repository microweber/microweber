<?php
namespace Microweber\Adapters\Ping;


class SearchEngines
{

    public $app;

    function __construct($app = null)
    {

        if (!is_object($this->app)) {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }

        }
    }

    public function ping()
    {
        return $this->content_ping_servers();
    }

    /**
     * Async caller for content_ping_servers
     * @category Content
     * @package Content
     * @subpackage Advanced
     * @uses content_ping_servers()
     * @see content_ping_servers();
     */
    private function content_ping_servers_async()
    {
        $scheduler = new \Microweber\Utils\Events();
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
    private function content_ping_servers()
    {

        if ($_SERVER ["SERVER_NAME"] == 'localhost') {
            return false;
        }

        if ($_SERVER ["SERVER_NAME"] == '127.0.0.1') {
            return false;
        }

        $fqdn = $this->is_fqdn(site_url());


        if ($fqdn != false) {
            $q = $this->app->content->get('is_active=y&is_deleted=n&is_pinged=n&limit=5&cache_group=content/ping');

            //$q = get_content('is_active=y');
            $server = array(
                'Google' => 'http://blogsearch.google.com/ping/RPC2',
                'Feed 2' => 'http://ping.pubsub.com/ping',
                'Feed 3' => 'http://api.my.yahoo.co.jp/RPC2');


            if (is_array($q)) {


                foreach ($server as $line_num => $line) {

                    $line = trim($line);


                    if ($line != '') {

                        foreach ($q as $the_post) {

                            $pages = array();
                            $pages [] = $the_post ['title'];
                            $pages [] = $this->app->content->link($the_post ['id']);

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
                                    // trigger_mw_error("xmlrpc: $response[faultString] ($response[faultCode])");
                                } else {
                                    //print_r($response);
                                }
                            }


                        }

                    }
                }

                $curl = new \Microweber\Utils\Curl();
                $curl->url = 'http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $this->app->url->site('sitemap.xml');
                $curl->timeout = 3;
                $result1 = $curl->execute();
                $this->app->cache_manager->delete('content/ping');
            }
        }
    }

    private function is_fqdn($FQDN)
    {
        return (!empty($FQDN) && preg_match('/(?=^.{1,254}$)(^(?:(?!\d|-)[a-z0-9\-]{1,63}(?<!-)\.)+(?:[a-z]{2,})$)/i', $FQDN) > 0);
    }
}
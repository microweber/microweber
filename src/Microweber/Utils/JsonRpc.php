<?php
namespace Microweber\Utils;
/**
 * jsonRPCClient.php
 *
 * Written using the JSON RPC specification -
 * http://json-rpc.org/wiki/specification
 *
 * @author Kacper Rowinski <kacper.rowinski@gmail.com>
 */

class JsonRpc
{
    protected $url = null, $is_notification = false, $is_debug = false;

    // http errors - more can be found at
    // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
    public $http_errors = array
    (
        400 => '400 Bad Request',
        500 => '500 Internal Server Error'
    );

    /**
     * Takes the connection parameter and checks for extentions
     *
     * @param string $url - url name like http://example.com/
     * @return void
     */
    public function __construct( $url )
    {
        $validateParams = array
        (
            false === extension_loaded('curl') => 'The curl extension must be loaded for using this class !',
            false === extension_loaded('json') => 'The json extension must be loaded for using this class !'
        );
        $this->checkForErrors( $validateParams );

        // set an url to connect to
        $this->url = $url;
    }

    /**
     * Set debug mode
     *
     * @param boolean $is_debug
     * @return void
     */
    public function setDebug( $is_debug )
    {
        $this->is_debug = !empty($is_debug);
    }

    /**
     * Set request to be a notification
     *
     * @param boolean $is_notification
     * @return void
     */
    public function setNotification( $is_notification  )
    {
        $this->is_is_notification = !empty($is_notification);
    }

    /**
     * Performs a request and gets the results
     *
     * @param string $method - A String containing the name of the method to be invoked.
     * @param array $params - An Array of objects to pass as arguments to the method.
     * @return array
     */
    public function make_call( $method, $params )
    {
        static $counter;

        // check if given params are correct
        $validateParams = array
        (
             false === is_scalar($method) => 'Method name has no scalar value',
             false === is_array($params) => 'Params must be given as array'
        );
        $this->checkForErrors( $validateParams );

        // if this is_notification - JSON-RPC specification point 1.3
        $requestId = true === $this->is_notification ? null : ++$counter;

        // Request (method invocation) - JSON-RPC specification point 1.1
        $request = json_encode( array ( 'method' => $method, 'params' => array_values($params), 'id' => $requestId ) );

        // if is_debug mode is true then add request to is_debug
        $this->debug( 'Request: ' . $request . "\r\n", false );

        $response = $this->getResponse( $request );

        // if is_debug mode is true then add response to is_debug and display it
        $this->debug( 'Response: ' . $response . "\r\n", true );

        // decode and create array ( can be object, just set to false )
        $response = json_decode( utf8_encode($response), true );

        // if this was just is_notification
        if ( true === $this->is_notification )
        {
            return true;
        }

        // check if response is correct
        $validateParams = array
        (
            !is_null($response['error']) => 'Request have return error: ' . $response['error'],
            $response['id'] != $requestId => 'Request id: '.$requestId.'is different from Response id: ' . $response['id'],

        );
      //  $this->checkForErrors( $validateParams );

        return $response['result'];
    }

    /**
     *     When the method invocation completes, the service must reply with a response.
     *     The response is a single object serialized using JSON
     *
     * @param string $request
     * @return string
     */
    protected function & getResponse( & $request )
    {
        // do the actual connection
        $ch = curl_init();
        // set URL
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        // send the request
        $response = curl_exec($ch);
        // check http status code
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ( isset($this->http_errors[$http_code])  )
        {
            throw new Exception('Response Http Error - ' . $this->http_errors[$http_code] );
        }
        // check for curl error
        if ( 0 < curl_errno($ch) )
        {
            throw new Exception('Unable to connect to '.$this->url . ' Error: ' . curl_error($ch) );
        }
        // close the connection
        curl_close($ch);
        return $response;
    }

    /**
     * Check for errors
     *
     * @param array $validateArray
     * @return void
     */
    protected function checkForErrors( & $validateArray )
    {
        foreach ( $validateArray as $test => $error )
        {
            if ( $test )
            {
                throw new Exception( $error );
            }
        }
    }

    /**
     * For is_debug and performance stats
     *
     * @param string $add
     * @param boolean $show
     * @return void
     */
    protected function debug( $add, $show = false )
    {
        static $debug, $startTime;
        // is_debug off return
        if ( false === $this->is_debug )
        {
            return;
        }
        // add
        $debug .= $add;
        // get starttime
        $startTime = empty($startTime) ? array_sum(explode(' ', microtime())) : $startTime;
        if ( true === $show and !empty($debug)  )
        {
            // get endtime
            $endTime = array_sum(explode(' ', microtime()));
            // performance summary
            $debug .= 'Request time: ' . round($endTime - $startTime, 3) . ' s Memory usage: ' . round(memory_get_usage() / 1024) . " kb\r\n";
            echo nl2br($debug);
            // send output imidiately
            flush();
            // clean static
            $debug = $startTime = null;
        }
    }
}
?>

<?php
namespace Microweber\email;
$_mw_email_transport_object = false;
api_expose('email/Sender/test');
class Sender
{

    public $transport = false;
    public $debug = false;
    public $email_from = false;
    public $email_from_name = false;
    public $cc = false;
    public $smtp_host = false;
    public $smtp_port = false;
    public $smtp_username = false;
    public $smtp_password = false;
    public $smtp_auth = false;
    public $smtp_secure = false;
    private $here = false;

    function __construct($transport = false)
    {

        if ($transport != false) {
            $this->transport = $transport;
        }

        $email_from = mw('option')->get('email_from_name', 'email');
        if ($email_from == false or trim($email_from) == '') {
            $email_from = getenv("USERNAME");
        }
        $this->email_from_name = $email_from;

        $this->smtp_host = trim(mw('option')->get('smtp_host', 'email'));
        $this->smtp_port = intval(mw('option')->get('smtp_port', 'email'));

        $this->smtp_username = trim(mw('option')->get('smtp_username', 'email'));
        $this->smtp_password = trim(mw('option')->get('smtp_password', 'email'));
        $this->smtp_auth = trim(mw('option')->get('smtp_auth', 'email'));

        $sec = mw('option')->get('smtp_secure', 'email');

        $this->smtp_secure = intval($sec);

        $email_from = mw('option')->get('email_from', 'email');
        if ($email_from == false or trim($email_from) == '') {
            if ($this->email_from_name != '') {
                $email_from = mw('url')->slug($this->email_from_name) . "@" . mw('url')->hostname();

            } else {
                $email_from = "noreply@" . mw('url')->hostname();

            }
        }
        $this->email_from = $email_from;

        $this->here = dirname(__FILE__);

        $class = $this->here . DS . 'lib' . DS . 'dSendMail2.php';

        require_once $class;

    }



    public static function send($to, $subject, $message, $add_hostname_to_subject = false, $no_cache = false, $cc = false) {

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        $cache_group = "notifications/email";
        $cache_content = mw()->cache->get($function_cache_id, $cache_group);

        if ($no_cache == false and ($cache_content) != false) {

            return $cache_content;
        }

        $res = self::email_get_transport_object();
        if (is_object($res)) {

            $email_from = mw('option')->get('email_from', 'email');
            if ($email_from == false or $email_from == '') {
            } else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
            }

            if ($add_hostname_to_subject != false) {
                $subject = '[' . mw('url')->hostname() . '] ' . $subject;
            }

            if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {
                //  $res -> debug = 1;
                if (isset($cc) and ($cc) != false and (filter_var($cc, FILTER_VALIDATE_EMAIL))) {
                    $res -> setCc($cc);
                }

                $res -> exec_send($to, $subject, $message);
                mw()->cache->save(true, $function_cache_id, $cache_group);
                return true;
            } else {
                return false;
            }

        }
    }

    public static function test($params)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            
             return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $res = self::email_get_transport_object();
        if (is_object($res)) {

            $email_from = mw('option')->get('email_from', 'email');
            if ($email_from == false or $email_from == '') {
            } else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
            }
            if (isset($params['to']) and (filter_var($params['to'], FILTER_VALIDATE_EMAIL))) {
                $to = $params['to'];
                $subject = "Test mail";

                if (isset($params['subject'])) {
                    $subject = $params['subject'];
                }

                $message = "Hello! This is a simple email message.";
                $res->debug = 1;
                $res->exec_send($to, $subject, $message);
            } else {
                return array('error' => 'Test E-mail is not valid');

 
            }

        }

        return true;

    }

    public static function email_get_transport_object()
    {

        global $_mw_email_transport_object;

        if (is_object($_mw_email_transport_object)) {
            return $_mw_email_transport_object;
        }

        $email_advanced = mw('option')->get('email_transport', 'email');
        if ($email_advanced == false or $email_advanced == '') {
            $email_advanced = 'php';
        }

        $transport_type = trim($email_advanced);

        try {
            $_mw_email_obj = new \Microweber\email\Sender($transport_type);
            $_mw_email_transport_object = $_mw_email_obj;
            return $_mw_email_obj;
        } catch (Exception $e) {
            return ($e->getMessage());
        }

        return false;

    }

    public function setCc($to)
    {
        $this->cc = $to;
    }

    function email_send_test($params)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $res = self::email_get_transport_object();
        if (is_object($res)) {

            $email_from = mw('option')->get('email_from', 'email');
            if ($email_from == false or $email_from == '') {
            } else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
            }
            if (isset($params['to']) and (filter_var($params['to'], FILTER_VALIDATE_EMAIL))) {
                $to = $params['to'];
                $subject = "Test mail";

                if (isset($params['subject'])) {
                    $subject = $params['subject'];
                }

                $message = "Hello! This is a simple email message.";
                $res->debug = 1;
                $res->exec_send($to, $subject, $message);
            } else {
                return mw('format')->notif("", 'error');
            }

        }

        return true;

    }

    public function exec_send($to, $subject, $message)
    {

        $from_address = $this->email_from;
        $from_name = $this->email_from_name;

        $m = new \dSendMail2;
        $m->setTo($to);
        if ($this->cc != false) {
            $m->setBcc($this->cc);
        }

        $m->setFrom($from_address);
        $m->setSubject($subject);
        $message = htmlspecialchars_decode($message);

        if (stristr($message, '{SITE_URL}')) {
            //d(MW_ROOTPATH);
            //$message = mw('url')->replace_site_url_back($message);
            //$m -> setMessage($message, true);
            $m->importHTML($message, $baseDir = MW_ROOTPATH, $importImages = true);
        } else {
            $message = mw('url')->replace_site_url_back($message);
            $m->setMessage($message, true);
        }

        $m->setCharset('UTF-8');
        $m->headers['Reply-To'] = $from_address;

        $transport = $this->transport;

        switch ($transport) {
            case 'smtp' :
                $m->sendThroughSMTP($this->smtp_host, $this->smtp_port, $this->smtp_username, $this->smtp_password, $this->smtp_secure);

                break;

            case 'gmail' :
                $m->sendThroughGMail($this->smtp_username, $this->smtp_password);

                break;

            case 'yahoo' :
                $m->sendThroughYahoo($this->smtp_username, $this->smtp_password);

                break;

            case 'hotmail' :
                $m->sendThroughHotMail($this->smtp_username, $this->smtp_password);

                break;

            default :
                break;
        }

        $m->debug = $this->debug;

        $s = $m->send();
        unset($m);
        return true;

    }

}

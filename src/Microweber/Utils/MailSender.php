<?php
namespace Microweber\Utils;
$_mw_email_transport_object = false;
api_expose_admin('Microweber/Utils/MailSender/test');

use \Config;
use \View;

class MailSender
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

    function __construct()
    {

        $views = MW_PATH . 'Views' . DS;

        View::addNamespace('mw_email_send', $views);

        $email_from = mw()->option_manager->get('email_from_name', 'email');
        if ($email_from == false or trim($email_from) == '') {
            $email_from = getenv("USERNAME");
        }
        $this->email_from_name = $email_from;

        $this->smtp_host = trim(mw()->option_manager->get('smtp_host', 'email'));
        $this->smtp_port = intval(mw()->option_manager->get('smtp_port', 'email'));

        $this->smtp_username = trim(mw()->option_manager->get('smtp_username', 'email'));
        $this->smtp_password = trim(mw()->option_manager->get('smtp_password', 'email'));
        $this->smtp_auth = trim(mw()->option_manager->get('smtp_auth', 'email'));
        $this->transport = trim(mw()->option_manager->get('email_transport', 'email'));;


        $sec = mw()->option_manager->get('smtp_secure', 'email');

        $this->smtp_secure = intval($sec);

        $email_from = mw()->option_manager->get('email_from', 'email');
        if ($email_from == false or trim($email_from) == '') {
            if ($this->email_from_name != '') {
                $email_from = ($this->email_from_name) . "@" . mw()->url_manager->hostname();

            } else {
                $email_from = "noreply@" . mw()->url_manager->hostname();

            }
        }
        $this->email_from = $email_from;

        $this->here = dirname(__FILE__);



        Config::set('mail.from.name', $this->email_from_name);
        Config::set('mail.from.address', $this->email_from);

        Config::set('mail.username', $this->smtp_username);
        Config::set('mail.password', $this->smtp_password);

        if ($this->transport == '' or $this->transport == 'php') {
            Config::set('mail.driver', 'mail');
        }

        if ($this->transport == 'gmail') {
            Config::set('mail.host', 'smtp.gmail.com');
            Config::set('mail.port', 587);
            Config::set('mail.encryption', 'tls');
        } else {
            Config::set('mail.host', $this->smtp_host);
            Config::set('mail.port', $this->smtp_port);
            Config::set('mail.encryption', $this->smtp_auth);
        }


    }


    public function send($to, $subject, $message, $add_hostname_to_subject = false, $no_cache = false, $cc = false)
    {

        $function_cache_id = false;

        $args = func_get_args();

        foreach ($args as $k => $v) {

            $function_cache_id = $function_cache_id . serialize($k) . serialize($v);
        }

        $function_cache_id = __FUNCTION__ . crc32($function_cache_id);
        $cache_group = "notifications/email";
        $cache_content = mw()->cache_manager->get($function_cache_id, $cache_group);

        if ($no_cache == false and ($cache_content) != false) {

            return $cache_content;
        }


        $email_from = mw()->option_manager->get('email_from', 'email');
        if ($email_from == false or $email_from == '') {
        } else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
        }

        if ($add_hostname_to_subject != false) {
            $subject = '[' . mw()->url_manager->hostname() . '] ' . $subject;
        }

        if (isset($to) and (filter_var($to, FILTER_VALIDATE_EMAIL))) {


            $this->exec_send($to, $subject, $message);
            if (isset($cc) and ($cc) != false and (filter_var($cc, FILTER_VALIDATE_EMAIL))) {
                $this->exec_send($cc, $subject, $message);
            }
            mw()->cache_manager->save(true, $function_cache_id, $cache_group);
            return true;
        } else {
            return false;
        }


    }

    public function test($params)
    {

        $is_admin = is_admin();
        if ($is_admin == false) {

            return array('error' => 'Error: not logged in as admin.' . __FILE__ . __LINE__);
        }


        $email_from = mw()->option_manager->get('email_from', 'email');
        if ($email_from == false or $email_from == '') {
            return array('error' => 'Sender E-mail is not set');
        } else if (!filter_var($email_from, FILTER_VALIDATE_EMAIL)) {
            return array('error' => 'Sender E-mail is not valid');
        }
        if (isset($params['to']) and (filter_var($params['to'], FILTER_VALIDATE_EMAIL))) {
            $to = $params['to'];
            $subject = "Test mail";

            if (isset($params['subject'])) {
                $subject = $params['subject'];
            }

            $message = "Hello! This is a simple email message.";

            $this->exec_send($to, $subject, $message);
        }


        return true;

    }

    public static function email_get_transport_object()
    {

        global $_mw_email_transport_object;

        if (is_object($_mw_email_transport_object)) {
            return $_mw_email_transport_object;
        }

        $email_advanced = mw()->option_manager->get('email_transport', 'email');
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


    public function exec_send($to, $subject, $text)
    {
        $from_address = $this->email_from;
        $from_name = $this->email_from_name;
        $text = mw()->url_manager->replace_site_url_back($text);
        $self = $this;
        $content = array();
        $content['content'] = $text;
        $content['subject'] = $subject;
        $content['to'] = $to;




        return \Mail::send('mw_email_send::emails.simple', $content, function ($message) use ($to, $subject) {
            $message->to($to)->subject($subject);
        });


        return;


    }

}

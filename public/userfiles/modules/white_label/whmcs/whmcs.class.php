<?php

/**
 * WHMCS PHP
 * @author Kay Leacock
 */
class WHMCS
{

    public $url;
    public $username;
    public $password;
    public $accesskey;
    public $secret;
    public $identifier;

    public function __construct($url = 'http://whmcs.com/include/api.php', $username = 'username', $password = 'password', $accesskey = '')
    {
        $this->url = $url;
        $this->username = $username;
        $this->password = $password;
        $this->accesskey = $accesskey;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Verify the authentication details
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function authenticate($username, $password)
    {
        $response = $this->api("validatelogin", array("email" => $username, "password2" => $password));
        if ($response->userid) {
            return true;
        }

        return false;
    }

    /**
     * Get Domains
     * @param int $uid
     * @param int $domainId
     * @param string $domain
     * @param int $start
     * @param int $limit
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Clients_Domains
     */
    public function getDomains($uid = 0, $domainId = 0, $domain = '', $start = 0, $limit = 0)
    {
        if ($limit <= 0) {
            $limit = 9999;
        }

        $params['limitnum'] = $limit;
        $params['limitstart'] = $start;

        if ($uid > 0) {
            $params['clientid'] = $uid;
        }

        if ($domainId > 0) {
            $params['domainid'] = $domainId;
        }

        if ($domain) {
            $params['domain'] = $domain;
        }

        $response = $this->api("getclientsdomains", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Domain Nameservers
     * @param int $domainId
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Domain_Nameservers
     */
    public function getDomainNameservers($domainId)
    {
        $params['domainid'] = $domainId;
        $response = $this->api("domaingetnameservers", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get domainlock
     * @param int $domainId
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Domain_Locking_Status
     */
    public function getDomainLock($domainId)
    {
        $params['domainid'] = $domainId;
        $response = $this->api("domaingetlockingstatus", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get domain WHOIS
     * @param int $domainId
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Domain_WHOIS
     */
    public function getDomainWHOIS($domainId)
    {
        $params['domainid'] = $domainId;
        $response = $this->api("domaingetwhoisinfo", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get products
     * @param int $pid
     * @param int $gid
     * @param string $module
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Products
     */
    public function getProducts($pid = 0, $gid = 0, $module = null)
    {

        $params = [];

        if ($pid > 0) {
            $params['pid'] = $pid;
        }

        if ($gid > 0) {
            $params['gid'] = $gid;
        }

        if ($module != null) {
            $params['module'] = $module;
        }

        $response = $this->api("getproducts", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Services
     * @param int $uid
     * @param int $serviceId
     * @param string $domain
     * @param int $productId
     * @param string $serviceUsername
     * @param int $start
     * @param int $limit
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Clients_Products
     */
    public function getServices($uid = 0, $serviceId = 0, $domain = '', $productId = 0, $serviceUsername = '', $start = 0, $limit = 0)
    {
        if ($limit <= 0) {
            $limit = 9999;
        }

        $params['limitnum'] = $limit;
        $params['limitstart'] = $limitstart;

        if ($uid > 0) {
            $params['clientid'] = $uid;
        }

        if ($serviceId > 0) {
            $params['serviceid'] = $serviceId;
        }

        if ($domain) {
            $params['domain'] = $domain;
        }

        if ($productId) {
            $params['pid'] = $productId;
        }

        if ($serviceUsername) {
            $params['username2'] = $serviceUsername;
        }

        $response = $this->api("getclientsproducts", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Transactions
     * @param int $uid
     * @param int $invoiceId
     * @param int $transactionId
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Transactions
     */
    public function getTransactions($uid = 0, $invoiceId = 0, $transactionId = 0)
    {
        if ($uid > 0) {
            $params['clientid'] = $uid;
        }

        if ($invoiceId > 0) {
            $params['invoiceid'] = $invoiceId;
        }

        if ($transactionId > 0) {
            $params['transid'] = $transactionId;
        }

        $response = $this->api("gettransactions", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Emails
     * @param int $uid Client ID
     * @param string $filter
     * @param string $filterdate
     * @param int $start
     * @param int $limit
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Emails
     */
    public function getEmails($uid, $filter = '', $filterdate = '', $start = 0, $limit = 0)
    {
        $params['clientid'] = $uid;

        if ($filter) {
            $params['subject'] = $filter;
        }

        if ($filterdate) {
            $params['date'] = $filterdate;
        }

        if (!$limit <= 0) {
            $limit = 9999;
        }

        $params['limitnum'] = $limit;
        $params['limitstart'] = $start;

        $response = $this->api("getemails", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Add Credit
     * @param array $data Array with parameters, see {@link http://docs.whmcs.com/API:Add_Credit#Attributes}
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Add_Credit
     */
    public function addCredit($data)
    {
        $attributes = array("clientid", "description", "amount");

        foreach ($attributes as $k) {
            $credit[$k] = $data[$k];
        }

        if (!$credit['clientid'] || !$credit['description'] || !$credit['amount']) {
            throw new WhmcsException("Required fields missing.");
        }

        $response = $this->api("addcredit", $credit);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Credits
     * @param id $uid
     * @return object
     * @link http://docs.whmcs.com/API:Get_Credits
     */
    public function getCredits($uid)
    {
        return $this->api("getcredits", array("clientid" => $uid));
    }

    /**
     * Update Client
     * @param int $uid
     * @param array $update Array with parameters, see {@link http://docs.whmcs.com/API:Update_Client#Optional_Attributes}
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Update_Client
     */
    public function updateClient($uid = 0, $update=false)
    {
        $attributes = array("firstname", "lastname", "companyname", "email", "address1", "address2", "city", "state", "postcode", "country", "phonenumber", "password2", "credit", "taxexempt", "notes", "cardtype", "cardnum", "expdate", "startdate", "issuenumber", "language", "customfields", "status", "latefeeoveride", "overideduenotices", "disableautocc");

        foreach ($attributes as $k) {
            if (isset($update[$k])) {
                $params[$k] = $update[$k];
            }
        }

        $params['clientid'] = $uid;

        $response = $this->api("updateclient", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Add Client
     * @param array $data Array with parameters, see {@link http://docs.whmcs.com/API:Add_Client#Optional_Attributes}
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Add_Client
     */
    public function addClient($data)
    {
        $attributes = array("firstname", "lastname", "companyname", "email", "address1", "address2", "city", "state", "postcode", "country", "phonenumber", "password2", "currency", "clientip", "language", "groupid", "securityqid", "securityqans", "notes", "cctype", "cardnum", "expdate", "startdate", "issuenumber", "customfields", "noemail", "skipvalidation");

        foreach ($attributes as $k) {
            $customer[$k] = $data[$k];
        }

        if ($customer['skipvalidation'] != true) {
            if (!$customer['firstname'] || !$customer['lastname'] || !$customer['email'] || !$customer['address1'] || !$customer['city'] || !$customer['state'] || !$customer['postcode'] || !$customer['country'] || !$customer['phonenumber'] || !$customer['password2']) {
                throw new WhmcsException("Required fields missing.");
            }
        }

        $response = $this->api("addclient", $customer);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Client
     * @param int $uid
     * @param string $email
     * @return object|boolean
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Clients_Details
     */
    public function getClient($uid = 0, $email = '')
    {
        if ($uid > 0) {
            $params = array("clientid" => $uid);
        } elseif ($email) {
            $params = array("email" => $email);
        } else {
            return false;
        }

        $params['stats'] = true;

        $response = $this->api("getclientsdetails", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Add Contact
     * @param array $data Array with parameters, see {@link http://docs.whmcs.com/API:Add_Contact#Optional_Attributes}
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Add_Contacts
     */
    public function addContact($data)
    {
        $attributes = array("clientid", "firstname", "lastname", "companyname", "email", "address1", "address2", "city", "state", "postcode", "country", "phonenumber", "password2", "permissions", "generalemails", "productemails", "domainemails", "invoiceemails", "supportemails", "skipvalidation");

        foreach ($attributes as $k) {
            $contact[$k] = $data[$k];
        }

        if ($contact['skipvalidation'] != true) {
            if (!$contact['clientid'] || !$contact['firstname'] || !$contact['lastname'] || !$contact['email'] || !$contact['address1'] || !$contact['city'] || !$contact['state'] || !$contact['postcode'] || !$contact['country'] || !$contact['phonenumber'] || !$contact['password2']) {
                throw new WhmcsException("Required fields missing.");
            }
        }

        $response = $this->api("addcontact", $contact);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Invoice details
     * @param int $invoiceid
     * @return object
     * @link http://docs.whmcs.com/API:Get_Invoice
     */
    public function getInvoice($invoiceid)
    {
        return $this->api("getinvoice", array("invoiceid" => $invoiceid));
    }

    /**
     * Get Invoices
     * @param int $uid
     * @param string $status
     * @param int $start
     * @param int $limit
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Invoices
     */
    public function getInvoices($uid = 0, $status = '', $start = 0, $limit = 0)
    {
        if ($uid > 0) {
            $params['userid'] = $uid;
        }

        if ($status == "Unpaid" || $status == "Paid" || $status == "Refunded" || $status == "Cancelled" || $status == "Collections") {
            $params['status'] = $status;
        }

        if (!$limit <= 0) {
            $limit = 9999;
        }

        $params['limitnum'] = $limit;
        $params['limitstart'] = $start;

        $response = $this->api("getinvoices", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Create Invoice
     * @param array $data Array with parameters, see {@link http://docs.whmcs.com/API:Create_Invoice}
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Create_Invoice
     */
    public function createInvoice($data)
    {
        $attributes = array("userid", "date", "duedate", "paymentmethod",
            "sendinvoice",
            // optional
            "taxrate", "taxrate2", "notes", "sendinvoice",
            "autoapplycredit");

        foreach ($attributes as $a) {
            if (!empty($params[$a])) {
                $params[$a] = $data[$a];
            }
        }

        for ($i = 0; $i < count($data['items']); $i++) {
            $params['itemdescription' . $i] = $data['items'][$i]['description'];
            $params['itemamount' . $i] = $data['items'][$i]['amount'];
            $params['itemtaxed' . $i] = $data['items'][$i]['taxed'];
        }

        $response = $this->api("createinvoice", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Add Invoice Payment
     * @param int $invoiceid
     * @param int $txid
     * @param int $amount
     * @param string $gateway
     * @param string $date
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Add_Invoice_Payment
     */
    public function addInvoicePayment($invoiceid=false, $txid=false, $amount = 0, $gateway=false, $date = '')
    {
        if ($amount > 0) {
            $params['amount'] = $amount;
        }

        if ($date) {
            $params['date'] = $date;
        }

        $params['transid'] = $txid;
        $params['gateway'] = $gateway;
        $params['invoiceid'] = $invoiceid;

        $response = $this->api("addinvoicepayment", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Orders
     * @param int $uid
     * @param int $orderId
     * @param string $status
     * @param int $start
     * @param int $limit
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Orders
     */
    public function getOrders($uid = 0, $orderId = 0, $status = '', $start = 0, $limit = 0)
    {
        if ($uid > 0) {
            $params['userid'] = $uid;
        }

        if ($orderId > 0) {
            $params['id'] = $invoiceId;
        }

        if ($status == "Pending" || $status == "Active" || $status == "Fraud" || $status == "Cancelled") {
            $params['status'] = $status;
        }

        if (!$limit <= 0) {
            $limit = 9999;
        }

        $params['limitnum'] = $limit;
        $params['limitstart'] = $start;

        $response = $this->api("getorders", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Add order
     * @param int $uid
     * @param array $productdata
     * @param string $paymentmethod
     * @param string $clientip
     * @param string $promocode
     * @param int $affid
     * @param boolean $noemail
     * @param boolean $noinvoice
     * @param boolean $noinvoiceemail
     * @param array $otherparams
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Add_Order
     */
    public function addOrder($uid, $productdata, $paymentmethod, $clientip, $promocode = null, $affid = null, $noemail = false, $noinvoice = false, $noinvoiceemail = false, $otherparams = null)
    {
        if ($promocode) {
            $params['promocode'] = $promocode;
        }
        if ($affid) {
            $params['affid'] = $affid;
        }
        if ($noemail) {
            $params['noemail'] = "true";
        }
        if ($noinvoice) {
            $params['noinvoice'] = "true";
        }
        if ($noinvoiceemail) {
            $params['noinvoiceemail'] = "true";
        }

        $params['clientid'] = $uid;
        $params['paymentmethod'] = $paymentmethod;
        $params['clientip'] = $clientip;

        $i = 0;
        foreach ($productdata as $product) {
            foreach ($product as $key => $val) {
                if ($key == "customfields" || $key == "configoptions" || $key == "domainfields") {
                    $val = base64_encode(serialize($val));
                }
                $params[$key . '[' . $i . ']'] = $val;
            }
            $i++;
        }

        if (isset($otherparams)) {
            foreach ($otherparams as $key => $val) {
                $params[$key] = $val;
            }
        }

        $response = $this->api('addorder', $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Accept order
     * @param int $orderid
     * @param int $serverid
     * @param string $serviceusername
     * @param string $servicepassword
     * @param int $registrar
     * @param boolean $autosetup
     * @param boolean $sendregistrar
     * @param boolean $sendemail
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Accept_Order
     */
    public function acceptOrder($orderid, $serverid = null, $serviceusername = null, $servicepassword = null, $registrar = null, $autosetup = null, $sendregistrar = null, $sendemail = null)
    {
        if ($serverid) {
            $params['serverid'] = $serverid;
        }
        if ($serviceusername) {
            $params['serviceusername'] = $serviceusername;
        }
        if ($servicepassword) {
            $params['servicepassword'] = $servicepassword;
        }
        if ($registrar) {
            $params['registrar'] = $registrar;
        }
        if ($autosetup) {
            $params['autosetup'] = $autosetup;
        }
        if ($sendemail) {
            $params['sendemail'] = $sendemail;
        }

        $params['orderid'] = $orderid;

        $response = $this->api('acceptorder', $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Get Stats
     * @return object
     * @throws WhmcsException
     * @link http://docs.whmcs.com/API:Get_Stats
     */
    public function getStats()
    {
        $response = $this->api("getstats", $params);

        if ($response['result'] == 'error') {
            throw new WhmcsException("WHMCS complained: " . $response->message);
        }

        return $response;
    }

    /**
     * Excecute API Command
     * @param string $action Action string
     * @param array $params Parameter array
     * @return object
     * @throws Exception
     */
    private function api($action, $params)
    {

        $postfields = array();
        $postfields['responsetype'] = 'json';
        $postfields['action'] = $action;

        if ($this->username) {
            $postfields['username'] = $this->username;
        }

        if ($this->password) {
            $postfields['password'] = md5($this->password);
        }

        if ($this->identifier) {
            $postfields['identifier'] = $this->identifier;
        }

        if ($this->secret) {
            $postfields['secret'] = $this->secret;
        }

        if (isset($params)) {
            foreach ($params as $k => $v) {
                $postfields[$k] = $v;
            }
        }

        $queryString = http_build_query($postfields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);

        return (json_decode($response, true));
    }
}

class WhmcsException extends Exception
{

}

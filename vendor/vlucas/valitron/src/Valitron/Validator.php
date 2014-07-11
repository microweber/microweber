<?php

namespace Valitron;

use InvalidArgumentException;

/**
 * Validation Class
 *
 * Validates input against certain criteria
 *
 * @package Valitron
 * @author Vance Lucas <vance@vancelucas.com>
 * @link http://www.vancelucas.com/
 */
class Validator
{
    /**
     * @var string
     */
    const ERROR_DEFAULT = 'Invalid';

    /**
     * @var array
     */
    protected $_fields = array();

    /**
     * @var array
     */
    protected $_errors = array();

    /**
     * @var array
     */
    protected $_validations = array();

    /**
     * @var array
     */
    protected $_labels = array();

    /**
     * @var string
     */
    protected static $_lang;

    /**
     * @var string
     */
    protected static $_langDir;

    /**
     * @var array
     */
    protected static $_rules = array();

    /**
     * @var array
     */
    protected static $_ruleMessages = array();

    /**
     * @var array
     */
    protected $validUrlPrefixes = array('http://', 'https://', 'ftp://');

    /**
     * Setup validation
     *
     * @param array $data
     * @param array $fields
     * @param string $lang
     * @param string $langDir
     * @throws \InvalidArgumentException
     */
    public function __construct($data, $fields = array(), $lang = null, $langDir = null)
    {
        // Allows filtering of used input fields against optional second array of field names allowed
        // This is useful for limiting raw $_POST or $_GET data to only known fields
        foreach ($data as $field => $value) {
            if (empty($fields) || (!empty($fields) && in_array($field, $fields))) {
                $this->_fields[$field] = $value;
            }
        }

        // set lang in the follow order: constructor param, static::$_lang, default to en
        $lang = $lang ?: static::lang();

        // set langDir in the follow order: constructor param, static::$_langDir, default to package lang dir
        $langDir = $langDir ?: static::langDir();

        // Load language file in directory
        $langFile = rtrim($langDir, '/') . '/' . $lang . '.php';
        if (stream_resolve_include_path($langFile) ) {
            $langMessages = include $langFile;
            static::$_ruleMessages = array_merge(static::$_ruleMessages, $langMessages);
        } else {
            throw new \InvalidArgumentException("fail to load language file '$langFile'");
        }
    }

    /**
     * Get/set language to use for validation messages
     *
     * @param string $lang
     * @return string
     */
    public static function lang($lang = null)
    {
        if ($lang !== null) {
            static::$_lang = $lang;
        }
        return static::$_lang ?: 'en';
    }

    /**
     * Get/set language file path
     *
     * @param string $dir
     * @return string
     */
    public static function langDir($dir = null)
    {
        if ($dir !== null) {
            static::$_langDir = $dir;
        }
        return static::$_langDir ?: dirname(dirname(__DIR__)) . '/lang';
    }

    /**
     * Required field validator
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateRequired($field, $value)
    {
        if (is_null($value)) {
            return false;
        } elseif (is_string($value) && trim($value) === '') {
            return false;
        }
        return true;
    }

    /**
     * Validate that two values match
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateEquals($field, $value, array $params)
    {
        $field2 = $params[0];
        return isset($this->_fields[$field2]) && $value == $this->_fields[$field2];
    }

    /**
     * Validate that a field is different from another field
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateDifferent($field, $value, array $params)
    {
        $field2 = $params[0];
        return isset($this->_fields[$field2]) && $value != $this->_fields[$field2];
    }

    /**
     * Validate that a field was "accepted" (based on PHP's string evaluation rules)
     *
     * This validation rule implies the field is "required"
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateAccepted($field, $value)
    {
        $acceptable = array('yes', 'on', 1, true);
        return $this->validateRequired($field, $value) && in_array($value, $acceptable, true);
    }

    /**
     * Validate that a field is numeric
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateNumeric($field, $value)
    {
        return is_numeric($value);
    }

    /**
     * Validate that a field is an integer
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateInteger($field, $value)
    {
        return filter_var($value, \FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate the length of a string
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateLength($field, $value, $params)
    {
        $length = $this->stringLength($value);
        // Length between
        if (isset($params[1])) {
            return $length >= $params[0] && $length <= $params[1];
        }
        // Length same
        return $length == $params[0];
    }

    /**
     * Validate the length of a string (between)
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @return boolean
     */
    protected function validateLengthBetween($field, $value, $params)
    {
        $length = $this->stringLength($value);
        return $length >= $params[0] && $length <= $params[1];
    }

    /**
     * Validate the length of a string (min)
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     *
     * @return boolean
     */
    protected function validateLengthMin($field, $value, $params)
    {
        return $this->stringLength($value) >= $params[0];
    }

    /**
     * Validate the length of a string (max)
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     *
     * @return boolean
     */
    protected function validateLengthMax($field, $value, $params)
    {
        return $this->stringLength($value) <= $params[0];
    }

    /**
     * Get the length of a string
     *
     * @param string $value
     * @return int
     */
    protected function stringLength($value)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($value);
        }
        return strlen($value);
    }

    /**
     * Validate the size of a field is greater than a minimum value.
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateMin($field, $value, $params)
    {
        return !(bccomp($params[0], $value, 14) == 1);
    }

    /**
     * Validate the size of a field is less than a maximum value
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateMax($field, $value, $params)
    {
        return !(bccomp($value, $params[0], 14) == 1);
    }

    /**
     * Validate a field is contained within a list of values
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateIn($field, $value, $params)
    {
        $isAssoc = array_values($params[0]) !== $params[0];
        if ($isAssoc) {
            $params[0] = array_keys($params[0]);
        }
        return in_array($value, $params[0]);
    }

    /**
     * Validate a field is not contained within a list of values
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateNotIn($field, $value, $params)
    {
        return !$this->validateIn($field, $value, $params);
    }

    /**
     * Validate a field contains a given string
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @return bool
     */
    protected function validateContains($field, $value, $params)
    {
        if (!isset($params[0])) {
            return false;
        }
        if (!is_string($params[0]) || !is_string($value)) {
            return false;
        }
        return (strpos($value, $params[0]) !== false);
    }

    /**
     * Validate that a field is a valid IP address
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateIp($field, $value)
    {
        return filter_var($value, \FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Validate that a field is a valid e-mail address
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateEmail($field, $value)
    {
        return filter_var($value, \FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate that a field is a valid URL by syntax
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateUrl($field, $value)
    {
        foreach ($this->validUrlPrefixes as $prefix) {
            if (strpos($value, $prefix) !== false) {
                return filter_var($value, \FILTER_VALIDATE_URL) !== false;
            }
        }
        return false;
    }

    /**
     * Validate that a field is an active URL by verifying DNS record
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateUrlActive($field, $value)
    {
        foreach ($this->validUrlPrefixes as $prefix) {
            if (strpos($value, $prefix) !== false) {
                $url = str_replace($prefix, '', strtolower($value));

                return checkdnsrr($url);
            }
        }
        return false;
    }

    /**
     * Validate that a field contains only alphabetic characters
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateAlpha($field, $value)
    {
        return preg_match('/^([a-z])+$/i', $value);
    }

    /**
     * Validate that a field contains only alpha-numeric characters
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaNum($field, $value)
    {
        return preg_match('/^([a-z0-9])+$/i', $value);
    }

    /**
     * Validate that a field contains only alpha-numeric characters, dashes, and underscores
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateSlug($field, $value)
    {
        return preg_match('/^([-a-z0-9_-])+$/i', $value);
    }

    /**
     * Validate that a field passes a regular expression check
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @return bool
     */
    protected function validateRegex($field, $value, $params)
    {
        return preg_match($params[0], $value);
    }

    /**
     * Validate that a field is a valid date
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateDate($field, $value)
    {
        return strtotime($value) !== false;
    }

    /**
     * Validate that a field matches a date format
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateDateFormat($field, $value, $params)
    {
        $parsed = date_parse_from_format($params[0], $value);

        return $parsed['error_count'] === 0;
    }

    /**
     * Validate the date is before a given date
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateDateBefore($field, $value, $params)
    {
        $vtime = ($value instanceof \DateTime) ? $value->getTimestamp() : strtotime($value);
        $ptime = ($params[0] instanceof \DateTime) ? $params[0]->getTimestamp() : strtotime($params[0]);
        return $vtime < $ptime;
    }

    /**
     * Validate the date is after a given date
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @internal param array $fields
     * @return bool
     */
    protected function validateDateAfter($field, $value, $params)
    {
        $vtime = ($value instanceof \DateTime) ? $value->getTimestamp() : strtotime($value);
        $ptime = ($params[0] instanceof \DateTime) ? $params[0]->getTimestamp() : strtotime($params[0]);
        return $vtime > $ptime;
    }

    /**
     * Validate that a field contains a boolean.
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    protected function validateBoolean($field, $value)
    {
        return (is_bool($value)) ? true : false;
    }

    /**
     * Validate that a field contains a valid credit card
     * optionally filtered by an array
     *
     * @param string $field
     * @param mixed $value
     * @param array $params
     * @return bool
     */
    protected function validateCreditCard($field, $value, $params)
    {
        /**
         * I there has been an array of valid cards supplied, or the name of the users card
         * or the name and an array of valid cards
         */
        if (!empty($params)) {
            /**
             * array of valid cards
             */
            if (is_array($params[0])) {
                $cards = $params[0];
            } elseif (is_string($params[0])){
                $cardType  = $params[0];
                if (isset($params[1]) && is_array($params[1])) {
                    $cards = $params[1];
                    if (!in_array($cardType, $cards)) {
                        return false;
                    }
                }
            }
        }
        /**
         * Luhn algorithm
         *
         * @return bool
         */
        $numberIsValid = function () use ($value) {
            $number = preg_replace('/[^0-9]+/', '', $value);
            $sum = 0;

            $strlen = strlen($number);
            if ($strlen < 13) {
                return false;
            }
            for ($i = 0; $i < $strlen; $i++) {
                $digit = (int) substr($number, $strlen - $i - 1, 1);
                if ($i % 2 == 1) {
                    $sub_total = $digit * 2;
                    if ($sub_total > 9) {
                        $sub_total = ($sub_total - 10) + 1;
                    }
                } else {
                    $sub_total = $digit;
                }
                $sum += $sub_total;
            }
            if ($sum > 0 && $sum % 10 == 0) {
                    return true;
            }
            return false;
        };

        if ($numberIsValid()) {
            if (!isset($cards)) {
                return true;
            } else {
                $cardRegex = array(
                    'visa'          => '#^4[0-9]{12}(?:[0-9]{3})?$#',
                    'mastercard'    => '#^5[1-5][0-9]{14}$#',
                    'amex'          => '#^3[47][0-9]{13}$#',
                    'dinersclub'    => '#^3(?:0[0-5]|[68][0-9])[0-9]{11}$#',
                    'discover'      => '#^6(?:011|5[0-9]{2})[0-9]{12}$#',
                );

                if (isset($cardType)) {
                    // if we don't have any valid cards specified and the card we've been given isn't in our regex array
                    if (!isset($cards) && !in_array($cardType, array_keys($cardRegex))) {
                        return false;
                    }

                    // we only need to test against one card type
                    return (preg_match($cardRegex[$cardType], $value) === 1);

                } elseif (isset($cards)) {
                    // if we have cards, check our users card against only the ones we have
                    foreach ($cards as $card) {
                        if (in_array($card, array_keys($cardRegex))) {
                            // if the card is valid, we want to stop looping
                            if (preg_match($cardRegex[$card], $value) === 1) {
                                return true;
                            }
                        }
                    }
                } else {
                    // loop through every card
                    foreach ($cardRegex as $regex) {
                        // until we find a valid one
                        if (preg_match($regex, $value) === 1) {
                            return true;
                        }
                    }
                }
            }
        }

        // if we've got this far, the card has passed no validation so it's invalid!
        return false;
    }


    /**
     *  Get array of fields and data
     *
     * @return array
     */
    public function data()
    {
        return $this->_fields;
    }

    /**
     * Get array of error messages
     *
     * @param null|string $field
     * @return array|bool
     */
    public function errors($field = null)
    {
        if ($field !== null) {
            return isset($this->_errors[$field]) ? $this->_errors[$field] : false;
        }
        return $this->_errors;
    }

    /**
     * Add an error to error messages array
     *
     * @param string $field
     * @param string $msg
     * @param array $params
     */
    public function error($field, $msg, array $params = array())
    {
        $msg = $this->checkAndSetLabel($field, $msg, $params);

        $values = array();
        // Printed values need to be in string format
        foreach ($params as $param) {
            if (is_array($param)) {
                $param = "['" . implode("', '", $param) . "']";
            }
            if ($param instanceof \DateTime) {
                $param = $param->format('Y-m-d');
            }
            // Use custom label instead of field name if set
            if (isset($this->_labels[$param])) {
                $param = $this->_labels[$param];
            }
            $values[] = $param;
        }

        $this->_errors[$field][] = vsprintf($msg, $values);
    }

    /**
     * Specify validation message to use for error for the last validation rule
     *
     * @param string $msg
     * @return $this
     */
    public function message($msg)
    {
        $this->_validations[count($this->_validations) - 1]['message'] = $msg;
        return $this;
    }

    /**
     * Reset object properties
     */
    public function reset()
    {
        $this->_fields = array();
        $this->_errors = array();
        $this->_validations = array();
        $this->_labels = array();
    }

    /**
     * Run validations and return boolean result
     *
     * @return boolean
     */
    public function validate()
    {
        foreach ($this->_validations as $v) {
            foreach ($v['fields'] as $field) {
                $value = isset($this->_fields[$field]) ? $this->_fields[$field] : null;

                // Don't validate if the field is not required and the value is empty
                if ($v['rule'] !== 'required' && !$this->hasRule('required', $field) && (! isset($value) || $value === '')) {
                    continue;
                }

                // Callback is user-specified or assumed method on class
                if (isset(static::$_rules[$v['rule']])) {
                    $callback = static::$_rules[$v['rule']];
                } else {
                    $callback = array($this, 'validate' . ucfirst($v['rule']));
                }

                $result = call_user_func($callback, $field, $value, $v['params']);
                if (!$result) {
                    $this->error($field, $v['message'], $v['params']);
                }
            }
        }

        return count($this->errors()) === 0;
    }

    /**
     * Determine whether a field is being validated by the given rule.
     *
     * @param string $name The name of the rule
     * @param string $field The name of the field
     * @return boolean
     */
    protected function hasRule($name, $field)
    {
        foreach ($this->_validations as $validation) {
            if ($validation['rule'] == $name) {
                if (in_array($field, $validation['fields'])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Register new validation rule callback
     *
     * @param string $name
     * @param mixed $callback
     * @param string $message
     * @throws \InvalidArgumentException
     */
    public static function addRule($name, $callback, $message = self::ERROR_DEFAULT)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('Second argument must be a valid callback. Given argument was not callable.');
        }

        static::$_rules[$name] = $callback;
        static::$_ruleMessages[$name] = $message;
    }

    /**
     * Convenience method to add a single validation rule
     *
     * @param string $rule
     * @param array $fields
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function rule($rule, $fields)
    {
        if (!isset(static::$_rules[$rule])) {
            $ruleMethod = 'validate' . ucfirst($rule);
            if (!method_exists($this, $ruleMethod)) {
                throw new \InvalidArgumentException("Rule '" . $rule . "' has not been registered with " . __CLASS__ . "::addRule().");
            }
        }

        // Ensure rule has an accompanying message
        $message = isset(static::$_ruleMessages[$rule]) ? static::$_ruleMessages[$rule] : self::ERROR_DEFAULT;

        // Get any other arguments passed to function
        $params = array_slice(func_get_args(), 2);

        $this->_validations[] = array(
            'rule' => $rule,
            'fields' => (array) $fields,
            'params' => (array) $params,
            'message' => '{field} ' . $message
        );
        return $this;
    }

    /**
     * @param string $value
     * @internal param array $labels
     * @return $this
     */
    public function label($value)
    {
        $lastRules = $this->_validations[count($this->_validations) - 1]['fields'];
        $this->labels(array($lastRules[0] => $value));

        return $this;
    }

    /**
     * @param array $labels
     * @return string
     */
    public function labels($labels = array())
    {
        $this->_labels = array_merge($this->_labels, $labels);
        return $this;
    }

    /**
     * @param string $field
     * @param string $msg
     * @param array $params
     * @return array
     */
    private function checkAndSetLabel($field, $msg, $params)
    {
        if (isset($this->_labels[$field])) {
            $msg = str_replace('{field}', $this->_labels[$field], $msg);

            if (is_array($params)) {
                $i = 1;
                foreach ($params as $k => $v) {
                    $tag = '{field'. $i .'}';
                    $label = isset($params[$k]) && !is_array($params[$k]) && isset($this->_labels[$params[$k]]) ? $this->_labels[$params[$k]] : $tag;
                    $msg = str_replace($tag, $label, $msg);
                    $i++;
                }
            }
        } else {
            $msg = str_replace('{field}', ucwords(str_replace('_', ' ', $field)), $msg);
        }

        return $msg;
    }

    /**
     * Convenience method to add multiple validation rules with an array
     *
     * @param array $rules
     */
    public function rules($rules)
    {
        foreach ($rules as $ruleType => $params) {
            if (is_array($params)) {
                foreach ($params as $innerParams) {
                    array_unshift($innerParams, $ruleType);
                    call_user_func_array(array($this, 'rule'), $innerParams);
                }
            } else {
                $this->rule($ruleType, $params);
            }
        }
    }
}

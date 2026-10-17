<?php

namespace MicroweberPackages\Format\Concerns;


use Illuminate\Support\Facades\Crypt;
/**
 * Extracted from FormatService to break up the god class. Composed back into
 * FormatService, so inter-method calls and shared state still work.
 */
trait EncodesData
{
    /**
     * Encode an array/value to base64-encoded JSON.
     */
    public function array_to_base64($var): string
    {
        if ($var === '' || $var === null) {
            return '';
        }

        return base64_encode(json_encode($var));
    }

    /**
     * Decode a base64-encoded JSON string back to an array.
     *
     * @return array|false
     */
    public function base64_to_array($var)
    {
        if (is_array($var)) {
            return $var;
        }
        if ($var === '' || $var === null) {
            return false;
        }

        $var = base64_decode($var, true);
        if ($var === false) {
            return false;
        }

        try {
            $decoded = @json_decode($var, true);
            return is_array($decoded) ? $decoded : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check whether a string is valid base64.
     */
    public function is_base64(string $data): bool
    {
        $decoded = base64_decode($data, true);
        if ($decoded === false || base64_encode($decoded) !== $data) {
            return false;
        }
        return true;
    }

    /**
     * Encrypt a string using Laravel's Crypt.
     */
    public function encrypt(string $string): string
    {
        return Crypt::encrypt($string);
    }

    /**
     * Decrypt a string using Laravel's Crypt.
     *
     * @return mixed
     */
    public function decrypt(string $string)
    {
        return Crypt::decrypt($string);
    }

    /**
     * Encode IDs using Hashids.
     */
    public function encode_ids($data): string
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->encode($data);
    }

    /**
     * Decode Hashids back to IDs.
     */
    public function decode_ids(string $data): array
    {
        $hashids = new \Hashids\Hashids();
        return $hashids->decode($data);
    }
}

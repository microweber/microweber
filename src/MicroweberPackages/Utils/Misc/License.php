<?php

namespace MicroweberPackages\Utils\Misc;

class License
{
    public function getLicense()
    {
        $file = $this->_getLicenseFile();
        if (!is_file($file)) {
            return [];
        }

        $decoded =  json_decode(file_get_contents($file), true);
        if (!empty($decoded)) {
            return $decoded;
        }

        return [];
    }

    public function saveLicense($code, $relType = 'modules/white_label')
    {
        $validate = $this->validateLicense($code, $relType);
        if ($validate) {

            $licenses = $this->getLicense();
            $licenses[$relType] =  $code;

            return file_put_contents($this->_getLicenseFile(), json_encode($licenses, JSON_PRETTY_PRINT));
        }

        return false;
    }

    public function validateLicense($code, $relType)
    {
        $licenses = [
            [
                'rel_type'=>$relType,
                'local_key'=>$code
            ]
        ];

        $validate = app()->update->call('validate_licenses', $licenses);
        if (!empty($validate)) {
           if (isset($validate[$relType]['status'])) {
               if ($validate[$relType]['status'] == 'active') {
                   return true;
               }
           }
        }

        return false;
    }

    public function truncate()
    {
        $file = $this->_getLicenseFile();
        if (is_file($file)) {
            return unlink($file);
        }
        return false;
    }

    private function _getLicenseFile()
    {
        return storage_path() . DS . 'licenses.json';
    }
}

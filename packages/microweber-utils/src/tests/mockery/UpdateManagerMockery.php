<?php
namespace MicroweberPackages\Utils\tests\mockery;

class UpdateManagerMockery {

    public $licenses;

    public function setActiveLicenses(array $licenses) {
        $this->licenses = $licenses;
    }

    public function call($method, $data) {

        if ($method == 'validate_licenses') {
            $firstLicense = reset($data);
            if (in_array($firstLicense['local_key'], $this->licenses)) {
                return [$firstLicense['rel_type'] => ['status' => 'active']];
            }
        }

        return false;
    }

}

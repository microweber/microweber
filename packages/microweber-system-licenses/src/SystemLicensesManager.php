<?php

namespace MicroweberPackages\SystemLicenses;

use Illuminate\Support\Facades\Schema;
use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;
use MicroweberPackages\SystemLicenses\Models\SystemLicense;

class SystemLicensesManager
{
    protected LicenseValidatorInterface $validator;

    /** @var array|null Cached active licenses for the current request. */
    protected ?array $activeLicenses = null;

    public function __construct(LicenseValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    // ------------------------------------------------------------------
    //  Query helpers
    // ------------------------------------------------------------------

    /**
     * Get all licenses from the database.
     *
     * @return array
     */
    public function getAllLicenses(): array
    {
        if (!Schema::hasTable('system_licenses')) {
            return [];
        }

        return SystemLicense::all()->toArray();
    }

    /**
     * Check whether any active license exists (optionally for a specific module).
     */
    public function hasLicense(?string $moduleName = null): bool
    {
        $licenses = $this->getActiveLicenses();

        if (empty($licenses)) {
            return false;
        }

        if ($moduleName === null) {
            return true;
        }

        foreach ($licenses as $license) {
            if (isset($license['rel_type']) && $license['rel_type'] === $moduleName) {
                return true;
            }
        }

        // If there are any licenses at all, consider it active (matches legacy behaviour).
        return !empty($licenses);
    }

    /**
     * Get the cached list of active licenses for this request.
     *
     * @return array
     */
    public function getActiveLicenses(): array
    {
        if ($this->activeLicenses === null) {
            $this->activeLicenses = $this->getAllLicenses();
        }

        return $this->activeLicenses;
    }

    /**
     * Force-refresh the cached active-licenses list.
     */
    public function refreshActiveLicenses(): void
    {
        $this->activeLicenses = null;
    }

    // ------------------------------------------------------------------
    //  CRUD
    // ------------------------------------------------------------------

    /**
     * Save a license key after consuming it via the validator.
     *
     * @return array  Status array compatible with the legacy API (id, success, is_active, is_invalid, warning).
     */
    public function saveLicense(array $params): array
    {
        if (!isset($params['local_key']) || empty(trim($params['local_key']))) {
            return ['is_invalid' => true, 'warning' => 'License key is required'];
        }

        $licenseLocalKey = trim($params['local_key']);

        $consumeResult = $this->validator->consumeLicense($licenseLocalKey);

        if (empty($consumeResult['valid'])) {
            return ['is_invalid' => true, 'warning' => 'License key is not valid'];
        }

        // Already exists?
        $existing = SystemLicense::where('local_key', $licenseLocalKey)->first();
        if ($existing !== null) {
            return ['id' => $existing->id, 'success' => 'License key already saved', 'is_active' => true];
        }

        if (!isset($consumeResult['servers']) || empty($consumeResult['servers'])) {
            return ['is_invalid' => true, 'warning' => 'License key is invalid'];
        }

        $licenseServers = end($consumeResult['servers']);
        $licenseDetails = $licenseServers['details'] ?? [];

        $newLicense = new SystemLicense();
        $newLicense->local_key = $licenseLocalKey;

        if (isset($licenseDetails['md5hash'])) {
            $newLicense->local_key_hash = $licenseDetails['md5hash'];
        }
        if (isset($licenseDetails['registeredname'])) {
            $newLicense->registered_name = $licenseDetails['registeredname'];
        }
        if (isset($licenseDetails['validdomain'])) {
            $newLicense->domains = $licenseDetails['validdomain'];
        }
        if (isset($licenseDetails['status'])) {
            $newLicense->status = $licenseDetails['status'];
        }
        if (isset($licenseDetails['productid'])) {
            $newLicense->product_id = $licenseDetails['productid'];
        }
        if (isset($licenseDetails['serviceid'])) {
            $newLicense->service_id = $licenseDetails['serviceid'];
        }
        if (isset($licenseDetails['billingcycle'])) {
            $newLicense->billing_cycle = $licenseDetails['billingcycle'];
        }
        if (isset($licenseDetails['regdate'])) {
            $newLicense->reg_on = $licenseDetails['regdate'];
        }
        if (isset($licenseDetails['nextduedate'])) {
            $newLicense->due_on = $licenseDetails['nextduedate'];
        }

        $newLicense->save();
        $this->refreshActiveLicenses();

        return ['id' => $newLicense->id, 'success' => 'License key saved', 'is_active' => true];
    }

    /**
     * Validate all existing licenses against the remote server and update their status.
     *
     * @return array|null
     */
    public function validateLicenses(?array $params = null): ?array
    {
        $licenses = $this->getAllLicenses();

        if (empty($licenses)) {
            return null;
        }

        $result = $this->validator->validateRemote($licenses);
        $updatedIds = [];

        if (!empty($result)) {
            foreach ($result as $relType => $details) {
                foreach ($licenses as $license) {
                    if (!isset($license['rel_type']) || $license['rel_type'] === $relType) {
                        if (is_array($details) && isset($details['status'])) {
                            $merged = $license;
                            $merged['status'] = $details['status'];

                            foreach ($license as $key => $value) {
                                if (isset($details[$key])) {
                                    $merged[$key] = $details[$key];
                                }
                            }

                            $updatedIds[] = $this->persistLicenseData($merged);
                        }
                    }
                }
            }
        }

        if (!empty($updatedIds)) {
            $this->refreshActiveLicenses();
            return ['updates' => $updatedIds, 'success' => 'Licenses are checked'];
        }

        return null;
    }

    /**
     * Consume a license by its database ID.
     *
     * @return array
     */
    public function consumeLicense(int $id): array
    {
        $license = SystemLicense::find($id);

        if ($license === null) {
            return ['status' => 'License not found', 'active' => false];
        }

        return $this->validator->consumeLicense($license->local_key);
    }

    /**
     * Delete a license by its database ID.
     *
     * @return array
     */
    public function deleteLicense(int $id): array
    {
        $license = SystemLicense::find($id);

        if ($license === null) {
            return ['error' => 'License not found'];
        }

        $license->delete();
        $this->refreshActiveLicenses();

        return ['id' => 0, 'success' => 'License was deleted'];
    }

    // ------------------------------------------------------------------
    //  File-based license helpers (legacy compatibility)
    // ------------------------------------------------------------------

    /**
     * Get licenses stored in the JSON file (legacy).
     */
    public function getFileLicenses(): array
    {
        $file = $this->getLicenseFilePath();

        if (!is_file($file)) {
            return [];
        }

        $decoded = json_decode(file_get_contents($file), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Save a license to the JSON file after validating it remotely (legacy).
     */
    public function saveFileLicense(string $code, string $relType = 'modules/white_label'): bool
    {
        $licenses = [
            [
                'rel_type'  => $relType,
                'local_key' => $code,
            ],
        ];

        $result = $this->validator->validateRemote($licenses);

        if (!empty($result) && isset($result[$relType]['status']) && $result[$relType]['status'] === 'active') {
            $existing = $this->getFileLicenses();
            $existing[$relType] = [
                'rel_type'  => $relType,
                'local_key' => $code,
            ];

            $saved = file_put_contents($this->getLicenseFilePath(), json_encode($existing, JSON_PRETTY_PRINT));

            return $saved !== false;
        }

        return false;
    }

    /**
     * Validate a single license key + rel_type against the remote server (legacy).
     */
    public function validateFileLicense(string $code, string $relType): bool
    {
        $licenses = [
            [
                'rel_type'  => $relType,
                'local_key' => $code,
            ],
        ];

        $result = $this->validator->validateRemote($licenses);

        return !empty($result)
            && isset($result[$relType]['status'])
            && $result[$relType]['status'] === 'active';
    }

    /**
     * Truncate the JSON license file (legacy).
     */
    public function truncateFileLicenses(): bool
    {
        $file = $this->getLicenseFilePath();

        if (is_file($file)) {
            return unlink($file);
        }

        return false;
    }

    /**
     * Path to the legacy JSON license file.
     */
    public function getLicenseFilePath(): string
    {
        return storage_path('licenses.json');
    }

    // ------------------------------------------------------------------
    //  Internal helpers
    // ------------------------------------------------------------------

    /**
     * Persist license data (upsert by local_key or update by id).
     */
    protected function persistLicenseData(array $data): int
    {
        $license = null;

        if (isset($data['id'])) {
            $license = SystemLicense::find($data['id']);
        }

        if ($license === null && isset($data['local_key'])) {
            $license = SystemLicense::where('local_key', $data['local_key'])->first();
        }

        if ($license === null) {
            $license = new SystemLicense();
        }

        $fillable = [
            'local_key', 'local_key_hash', 'rel_type', 'rel_id',
            'registered_name', 'company_name', 'domains', 'status',
            'product_id', 'service_id', 'billing_cycle', 'reg_on', 'due_on',
        ];

        foreach ($fillable as $field) {
            if (isset($data[$field])) {
                $license->{$field} = $data[$field];
            }
        }

        $license->save();

        return $license->id;
    }
}
<?php

namespace Modules\Customer\Services;

use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Models\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CustomerManager
{
    protected $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createCustomer(array $data): Customer
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->create($data);
    }

    public function updateCustomer(int $id, array $data): Customer
    {
        $validator = Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,'.$id,
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->repository->update($id, $data);
    }

    public function activateAccount(int $customerId): bool
    {
        return $this->repository->update($customerId, ['active' => true]);
    }

    public function deactivateAccount(int $customerId): bool
    {
        return $this->repository->update($customerId, ['active' => false]);
    }

    public function findCustomer(int $id): ?Customer
    {
        return $this->repository->find($id);
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
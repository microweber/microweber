<?php

namespace Modules\Customer\Repositories;

use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;

class EloquentCustomerRepository implements CustomerRepository
{
    protected $model;

    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

    public function create(array $data): Customer
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Customer
    {
        $customer = $this->model->findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function find(int $id): ?Customer
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }
}
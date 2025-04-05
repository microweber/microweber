<?php

namespace Modules\Customer\Repositories;

use Modules\Customer\Models\Customer;

interface CustomerRepository
{
    public function create(array $data): Customer;
    public function update(int $id, array $data): Customer;
    public function find(int $id): ?Customer;
    public function delete(int $id): bool;
}
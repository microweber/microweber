<?php

namespace Modules\Order\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use Modules\Order\Events\OrderIsCreating;
use Modules\Order\Events\OrderIsUpdating;
use Modules\Order\Events\OrderWasCreated;
use Modules\Order\Events\OrderWasDeleted;
use Modules\Order\Events\OrderWasDestoyed;
use Modules\Order\Events\OrderWasUpdated;
use Modules\Order\Models\Order;

class OrderApiRepository extends BaseRepository
{

    public function __construct(Order $Order)
    {
        $this->model = $Order;
    }

    public function create($data)
    {
        event($event = new OrderIsCreating($data));

        $Order = $this->model->create($data);

        event(new OrderWasCreated($Order, $data));

        return $Order;
    }

    public function update($data, $id)
    {
        $Order = $this->model->find($id);

        event($event = new OrderIsUpdating($Order, $data));

        $Order->update($data);

        event(new OrderWasUpdated($Order, $data));

        return $Order;
    }


    public function delete($id)
    {
        $Order = $this->model->find($id);

        event(new OrderWasDeleted($Order));

        return $Order->delete();
    }


    public function destroy($ids)
    {
        event(new OrderWasDestoyed($ids));

        return $this->model->destroy($ids);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}

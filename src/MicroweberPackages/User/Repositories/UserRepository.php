<?php

namespace MicroweberPackages\User\Repositories;

use MicroweberPackages\Core\Repositories\BaseRepository;
use MicroweberPackages\User\Events\UserIsCreating;
use MicroweberPackages\User\Events\UserIsUpdating;
use MicroweberPackages\User\Events\UserWasCreated;
use MicroweberPackages\User\Events\UserWasDeleted;
use MicroweberPackages\User\Events\UserWasDestroyed;
use MicroweberPackages\User\Events\UserWasUpdated;
use MicroweberPackages\User\Models\User;

class UserRepository extends BaseRepository
{

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function create($data)
    {
        event($event = new UserIsCreating($data));

        $user = $this->model->create($data);

        event(new UserWasCreated($user, $data));

        return $user;
    }

    public function update($data, $id)
    {
        $user = $this->model->find($id);

        event($event = new UserIsUpdating($user, $data));

        $user->update($data);

        event(new UserWasUpdated($user, $data));

        return $user;
    }


    public function delete($id)
    {
        $user = $this->model->find($id);

        event(new UserWasDeleted($user));

        return $user->delete();
    }


    public function destroy($ids)
    {
        event(new UserWasDestroyed($ids));

        return $this->model->destroy($ids);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

}

<?php

namespace Modules\Category\Repositories;

use MicroweberPackages\Category\Events\CategoryIsCreating;
use MicroweberPackages\Category\Events\CategoryIsUpdating;
use MicroweberPackages\Category\Events\CategoryWasCreated;
use MicroweberPackages\Category\Events\CategoryWasDeleted;
use MicroweberPackages\Category\Events\CategoryWasDestroyed;
use MicroweberPackages\Category\Events\CategoryWasUpdated;
use MicroweberPackages\Core\Repositories\BaseRepository;
use Modules\Category\Models\Category;

class CategoryRepositoryApi extends BaseRepository
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {

        $category = $this->model->create($data);


        return $category;
    }

    public function update($data, $id)
    {
        $category = $this->model->find($id);

        if (!$category) {
            return;
        }

        $category->update($data);


        return $category;
    }

    public function delete($id)
    {
        $category = $this->model->find($id);


        return $category->delete();
    }


    public function destroy($ids)
    {

        return $this->model->destroy($ids);
    }

    public function hiddenBulk($ids)
    {
        return $this->model->whereIn('id', $ids)->update(['is_hidden' => 1]);
    }

    public function visibleBulk($ids)
    {
        return $this->model->whereIn('id', $ids)->update(['is_hidden' => 0]);
    }

    public function moveBulk($ids, $moveToParentIds, $moveToRelId)
    {

        if (!empty($ids) && !empty($moveToRelId)) {
            $this->model->whereIn('id', $ids)->update(['rel_id' => $moveToRelId]);
        }

        if (!empty($ids) && !empty($moveToParentIds)) {
            foreach ($moveToParentIds as $moveToParentId) {
                if (in_array($moveToParentId, $ids)) {
                    // cannot move to self
                    continue;
                }
                $this->model->whereIn('id', $ids)->update(['parent_id' => $moveToParentId]);
            }
        } else if (!empty($ids) && empty($moveToParentIds)) {
            $this->model->whereIn('id', $ids)->update(['parent_id' => null]);
        }

    }


}

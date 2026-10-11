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

        $moved = 0;
        $blocked = [];

        if (!empty($ids) && !empty($moveToParentIds)) {
            $movedIds = array_map('intval', (array) $ids);

            foreach ($moveToParentIds as $moveToParentId) {
                $moveToParentId = (int) $moveToParentId;

                // A category may not become a child of itself or of one of its
                // own descendants — that would create a cycle and orphan the
                // moved subtree. The target's self + ancestor chain is exactly
                // the set of ids that must NOT be moved under it (if a moved id
                // is the target, or an ancestor of the target, then the target
                // is that category or one of its descendants).
                $forbidden = $this->getSelfAndAncestorIds($moveToParentId);

                $validIds = array_values(array_diff($movedIds, $forbidden));
                $blocked = array_merge($blocked, array_values(array_intersect($movedIds, $forbidden)));

                if (!empty($validIds)) {
                    $this->model->whereIn('id', $validIds)->update(['parent_id' => $moveToParentId]);
                    $moved += count($validIds);
                }
            }
        } else if (!empty($ids) && empty($moveToParentIds)) {
            $this->model->whereIn('id', $ids)->update(['parent_id' => null]);
            $moved = count((array) $ids);
        }

        return [
            'success' => true,
            'moved' => $moved,
            'blocked' => array_values(array_unique($blocked)),
        ];
    }

    /**
     * Return the given category id plus every ancestor id, walking parent_id up
     * to the root. Used to reject moves that would put a category inside itself
     * or one of its descendants. Guarded against pre-existing cycles.
     *
     * @param int $categoryId
     * @return array<int>
     */
    private function getSelfAndAncestorIds($categoryId): array
    {
        $ids = [];
        $current = (int) $categoryId;
        $guard = 0;

        while ($current > 0 && $guard < 1000) {
            if (in_array($current, $ids, true)) {
                break; // already-corrupted cycle — stop walking
            }
            $ids[] = $current;
            $current = (int) $this->model->where('id', $current)->value('parent_id');
            $guard++;
        }

        return $ids;
    }


}

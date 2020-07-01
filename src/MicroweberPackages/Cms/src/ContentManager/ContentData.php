<?php


class ContentData extends DataFields
{
    public function newQuery($excludeDeleted = true)
    {
        return parent::newQuery($excludeDeleted)
            ->whereRelType('content');
    }
}

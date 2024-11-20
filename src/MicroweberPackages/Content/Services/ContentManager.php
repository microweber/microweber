<?php

namespace MicroweberPackages\Content\Services;

use MicroweberPackages\Content\Contracts\ContentManagerInterface;
use Modules\Content\Repositories\ContentRepository;

class ContentManager implements ContentManagerInterface
{
    /**
     * @var ContentRepository Content Repository to query from database
     */
    protected ContentRepository $repository;

    public function __construct(ContentRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @return \Modules\Content\Repositories\ContentRepository
     */
    public function getRepository(): ContentRepository
    {
        return $this->repository;
    }

    /**
     * @param \Modules\Content\Repositories\ContentRepository $repository
     */
    public function setRepository(ContentRepository $repository): void
    {
        $this->repository = $repository;
    }

    public function getById($id)
    {
        return $this->repository->getById($id);
    }


}

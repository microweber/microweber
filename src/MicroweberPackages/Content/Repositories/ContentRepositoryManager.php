<?php


namespace MicroweberPackages\Content\Repositories;


class ContentRepositoryManager
{
    private $contentRepository;
    public $contentRepositoryMemory = [];

    public function __construct(ContentRepository $contentRepository)
    {

         $this->contentRepository = $contentRepository;
    }

    public function all()
    {
        return $this->contentRepository->all();

    }

    public function find($id)
    {

        if (isset($this->contentRepositoryMemory[$id])) {
            return $this->contentRepositoryMemory[$id];
        }
        $result = $this->contentRepository->find($id);
        if ($result) {
            $this->contentRepositoryMemory[$id] = $result;
        } else {
            $this->contentRepositoryMemory[$id] = false;
        }
        return $this->contentRepositoryMemory[$id];

    }
}
<?php


namespace MicroweberPackages\Repository\Controllers;


use MicroweberPackages\Repository\Repositories\Interfaces\ContentRepositoryInterface;

class ContentRepositoryController
{
    private $contentRepository;

    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function all()
    {
        return $this->contentRepository->all();

    }

    public function getById($id)
    {

        return $this->contentRepository->getById($id);

    }
}
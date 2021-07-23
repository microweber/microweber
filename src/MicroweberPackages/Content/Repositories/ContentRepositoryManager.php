<?php


namespace MicroweberPackages\Content\Repositories;

use MicroweberPackages\Content\Content;

class ContentRepositoryManager
{



    /**
     * @var ContentRepositoryModel
     */
    public $repository;

    public function __construct(ContentRepositoryModel $repository)
    {

        $this->repository = $repository;
    }

//
//    /**
//     * @param $name
//     * @param $arguments
//     * @return mixed
//     */
//    public function __call($name, $arguments)
//    {
//        return $this->repository->{$name}($arguments);
//    }



    /**
     * Specify Model class name
     *
     * @return string
     */
 //   protected $model = Content::class;


//    private $contentRepository;
//    public $contentRepositoryMemory = [];
//
//    public function __construct(ContentRepository $contentRepository)
//    {
//
//        $this->contentRepository = $contentRepository;
//    }
//
//    public function all()
//    {
//        return $this->contentRepository->all();
//
//    }
//
//    public function find($id)
//    {
//
//         if (isset($this->contentRepositoryMemory[$id])) {
//            return $this->contentRepositoryMemory[$id];
//        }
//        $result = $this->contentRepository->find($id);
//        if ($result) {
//            $this->contentRepositoryMemory[$id] = $result;
//        } else {
//            $this->contentRepositoryMemory[$id] = false;
//        }
//        return $this->contentRepositoryMemory[$id];
//
//    }
}
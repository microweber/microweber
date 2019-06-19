<?php


namespace Microweber\Utils\Adapters\Packages\Helpers;


use Composer\Repository\CompositeRepository as CompositeRepositoryBase;


class CompositeRepository extends CompositeRepositoryBase
{

    /**
     * List of repositories
     * @var array
     */
    private $repositories;

    /**
     * Constructor
     * @param array $repositories
     */
    public function __construct(array $repositories)
    {
        $this->repositories = array();
        foreach ($repositories as $repo) {
            $this->addRepository($repo);
        }

        //  parent::__construct($repositories);
    }


    public function search($query, $mode = 0, $type = null)
    {
        $matches = array();

        $repos = $this->getRepositories();
     //   $repos = array_unique($repos, SORT_REGULAR);


        //dd($repos);
        // dd(parent::getRepositories());

        foreach ($repos as $repository) {
            /* @var $repository RepositoryInterface */

            $matches[] = $repository->search($query, $mode, $type);
        }


        if (!$matches) {
            return array();
        }
        return $matches ? call_user_func_array('array_merge', $matches) : array();
    }


}
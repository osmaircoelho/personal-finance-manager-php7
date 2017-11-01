<?php
declare(strict_types=1);

namespace SONFin\Auth;


use Jasny\Auth\Sessions;
use Jasny\Auth\User;
use SONFin\Repository\RepositoryInterface;

class JasnyAuth extends \Jasny\Auth

{
    use Sessions;
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * JasnyAuth constructor.
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Fetch a user by ID
     *
     * @param int|string $id
     * @return User|null
     */
    public function fetchUserById($id)
    {
        return $this->repository->find($id, false);
    }

    /**
     * Fetch a user by username
     *
     * @param string $username
     * @return User|null
     */
    public function fetchUserByUsername($username)
    {
       return $this->repository->findByField('email', $username)[0];
    }
}
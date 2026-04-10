<?php


namespace App\Http\Repositories;


interface IRepo
{
    /**
     * @return object
     */
    public function model(): object;

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * @return mixed
     */
    public function getAll() : object;

    /**
     * @param int $id
     * @return object
     */
    public function findById(int $id): object ;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;
}

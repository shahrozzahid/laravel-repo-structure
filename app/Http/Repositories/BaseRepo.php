<?php
namespace App\Http\Repositories;

use App\Helpers\GeneralHelpers;
use App\Helpers\IUserRole;
use Spatie\Permission\Models\Role;


/**
 * Class BaseRepo
 * @package App\Http\Repositories
 */
abstract class BaseRepo implements IRepo
{

    /**
     * @var mixed
     */
    public $model;

    /**
     * BaseRepo constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = new $model;

    }

    /**
     * @return object
     */
    public function model(): object
    {
        // TODO: Implement model() method.
       return $this->model;
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        // TODO: Implement create() method.
       return $this->model->create($data);
    }

    /**
     * @param $files
     * @param $folderName
     * @return bool|string
     */

    public function addFiles($files, $folderName)
    {
        return GeneralHelpers::UPLOAD_FILE($files, $folderName);
    }

    /**
     * @return mixed|object
     */
    public function getAll() : object
    {
        return $this->model::role(IUserRole::USER)->get();
    }

    /**
     * @param int $id
     *
     * @return object
     */
    public function findById(int $id): object
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @param array $data
     *
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }
}

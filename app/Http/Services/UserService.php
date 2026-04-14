<?php


namespace App\Http\Services;


use App\Http\Contracts\IUserServiceContract;
use App\Http\Repositories\UserRepo;
use Illuminate\Support\Facades\DB;
use App\Helpers\IUserRole;
use App\Helpers\IUserPermission;
use Illuminate\Support\Facades\Hash;


/**
 * Class UserService
 * @package App\Http\Services
 */
class UserService implements IUserServiceContract
{

    /**
     * @var UserRepo
     */
    private $_userRepo;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $this->_userRepo = new UserRepo();
    }


    /**
     * @param array $data
     * @return bool|object
     */
    public function userStore(array $data, string $token = null )
    {
        $url = $this->_userRepo->addFiles($data['profile_image'], 'profiles');

        DB::beginTransaction();
        if($user = $this->_userRepo->create($this->_filterRequest($data, $url, $token))){
            $user->assignRole(IUserRole::USER);
//            $user->givePermissionTo(IUserPermission::PROJECT_CREATE);
            DB::commit();
            return  $user;
        }

        DB::rollBack();
        return false;
    }

    /**
     * @param $request
     * @param string $url
     * @return array
     */

    private function _filterRequest($request, string $url, string $token = null){

        return [
            'name'           => $request['name'],
            'email'          => $request['email'],
            'profile_image'  => $url,
            'password'       => Hash::make($request['password']),
            'api_token'      => $token,
        ];
    }

    /**
     * @return mixed|object
     */

    public function getAll() : object
    {
        return $this->_userRepo->getAll();

    }

    /**
     * Return user by id
     *
     * @param $id
     *
     * @return object
     */
    public function findById($id)
    {
        return $this->_userRepo->findById(decrypt($id));
    }
    /**
     * @return object
     */
    public function model(){
        return $this->_userRepo->model();
    }

}

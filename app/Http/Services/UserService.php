<?php


namespace App\Http\Services;


use App\Http\Contracts\IUserServiceContract;
use App\Http\Repositories\UserRepo;
use Illuminate\Support\Facades\DB;
use App\Helpers\IUserRole;
use App\Helpers\IUserPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;


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
            $user->createToken('auth_token')->plainTextToken;
//            $user->givePermissionTo(IUserPermission::PROJECT_CREATE);
            DB::commit();
            return  $user;
        }

        DB::rollBack();
        return false;
    }

    /**
     * @param array $data
     * @return bool|object
     * Two different ways through update user data. Same working but in a different apply logic.
     * 1) userUpdate($id, array $data, string $token = null)
     * 2) updateUser(Model $user, array $data)
     */
    public function userUpdate($id, array $data, string $token = null )    
    {
        $newImage = '';
        // Update profile image if provided
        if (isset($data['profile_image'])) {
            $newImage = $this->_userRepo->addFiles($data['profile_image'], 'profiles');
        }
        DB::beginTransaction();
        if($user = $this->_userRepo->update($id, $this->_filterRequest($data, $newImage))){
            DB::commit();
            return  $user;
        }
        DB::rollBack();
        return false;

    }

    /**
     * @param array $data
     * @return Model|object
     */
    public function updateUser(Model $user, array $data)
    {
        // Update name if provided
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        // Update email if provided
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        // Update password if provided
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Update profile image if provided
        if (isset($data['profile_image'])) {

            $newImage = $this->_userRepo->addFiles($data['profile_image'], 'profiles');

            if ($newImage) {
                $user->profile_image = $newImage;
            }
        }
        $user->save();
        return $user->fresh(); // return fresh instance from DB
    }

    /**
     * @param $request
     * @param string $url
     * @return array
     */

    private function _filterRequest($request, string $url, string $token = null){

        $response = [
            'name'           => $request['name'],
            'email'          => $request['email'],
            
        ];
        if (isset($token) && !empty($token)){
            $response['api_token']  = $token;
        }
        // Only update image if a new URL is provided
        if ($url) {
            $response['profile_image'] = $url;
        }

        // Only update password if user actually typed a new one
        if (!empty($request['password'])) {
            $response['password'] = Hash::make($request['password']);
        }
        return $response;
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

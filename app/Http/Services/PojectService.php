<?php


namespace App\Http\Services;


use App\Http\Contracts\IProjectServiceContract;
use App\Http\Repositories\ProjectRepo;
use Illuminate\Support\Facades\DB;

/**
 * Class PojectService
 * @package App\Http\Services
 */
class PojectService implements IProjectServiceContract
{
    /**
     * @var ProjectRepo
     */
    private $_projectRepo;

    /**
     * PojectService constructor.
     */
    public function __construct()
    {
        $this->_projectRepo = new ProjectRepo();
    }

    /**
     * @param array $data
     * @return bool|object
     */
    public function projectStore(array $data)
    {
        $url = $this->_projectRepo->addFiles($data['project_doc'], 'project-docs');
        DB::beginTransaction();
        if($project = $this->_projectRepo->create($this->_filterRequest($data, $url))){
            DB::commit();
            return  $project;
        }
        DB::rollBack();
        return false;
    }


    /**
     * @return mixed|object
     */

    public function all() : object
    {
        return $this->_projectRepo->all();

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
        return $this->_projectRepo->findById(decrypt($id));
    }

    /**
     * @return object
     */
    public function model(){
       return $this->_projectRepo->model();
    }

    public function destroy($project){
        // remove all assigned users (pivot entries)
        $project->users()->detach();
        // delete the project
        return $project->delete();
    }

    /**
     * Update User
     *
     * @param $id
     * @param $data
     *
     * @return bool
     */
    public function update($id, $data, $request)
    {
        $url =  $request->hasFile('project_doc') ? $this->_projectRepo->addFiles($data['project_doc'], 'project-docs') : $request['old_document'] ;
        if($project = $this->_projectRepo->update(decrypt($id), $this->_filterRequest($data, $url))){
            return  $project;
        }
        return false;
    }

    /**
     * @param $request
     * @param string $url
     * @return array
     */

    private function _filterRequest($request, string $url){

        return [
            'title'       => $request['title'],
            'description' => $request['description'],
            'doc'         => $url,
        ];
    }
    public function assignOrUnassignByClause($user, $project)
    {
        // Check if user already assigned
        if ($user->projects()->where('project_id', $project->id)->exists()) {
            // Remove
            $user->projects()->detach($project->id);
            return true;
        } else {
            // Assign
            $user->projects()->attach($project->id);
            return false;
        }
    }
}

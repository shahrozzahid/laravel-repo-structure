<?php


namespace App\Http\Repositories;

use App\Models\Project;

/**
 * Class ProjectRepo
 * @package App\Http\Repositories
 */
class ProjectRepo extends BaseRepo
{
    /**
     * ProjectRepo constructor.
     */
    public function __construct()
    {
        parent::__construct(Project::class);
    }

}

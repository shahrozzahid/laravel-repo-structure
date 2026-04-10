<?php

namespace App\Helpers;

/**
 * Interface IUserPermission
 * @package App\Http\Contracts
 */

interface IUserPermission
{

//    Admin Permission

    const PROJECT_CREATE = 'create_project';
    const PROJECT_DELETE = 'delete_project';
    const PROJECT_UPDATE = 'update_project';
    const PROJECT_VIEW = 'view_project';

    const TASK_VIEW   = 'view_task';
    const TASK_DELETE = 'delete_task';
    const TASK_UPDATE = 'update_task';
    const TASK_CREATE = 'create_task';

}

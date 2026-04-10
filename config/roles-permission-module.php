<?php

return [
  'roles-set' => [
      \App\Helpers\IUserRole::ADMIN => 'Admin',
      \App\Helpers\IUserRole::USER  => 'User',
  ],

   'permissions-set' => [
       \App\Helpers\IUserPermission::PROJECT_CREATE => 'Create Project',
       \App\Helpers\IUserPermission::PROJECT_DELETE => 'Delete Project',
       \App\Helpers\IUserPermission::PROJECT_UPDATE => 'Update Project',
       \App\Helpers\IUserPermission::PROJECT_VIEW   => 'View Project',

       \App\Helpers\IUserPermission::TASK_VIEW      => 'View Task',
       \App\Helpers\IUserPermission::TASK_DELETE    => 'Delete Task',
       \App\Helpers\IUserPermission::TASK_UPDATE    => 'Update Task',
       \App\Helpers\IUserPermission::TASK_CREATE    => 'Create Task',
]

];

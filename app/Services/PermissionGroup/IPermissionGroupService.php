<?php 


namespace App\Services\PermissionGroup;
use App\Services\IBaseService;

interface IPermissionGroupService extends IBaseService
{
    public function getPermissionGroupData();
}

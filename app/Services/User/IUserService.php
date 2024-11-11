<?php 


namespace App\Services\User;
use App\Services\IBaseService;

interface IUserService extends IBaseService
{
    public function getUserData();
}

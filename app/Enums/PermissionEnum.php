<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case ADMIN_DASHBOARD = 'admin-dashboard';

    case USER_INDEX = 'user.index';
    case USER_LIST = 'user-list';
    case USER_CREATE = 'user.create';
    case USER_STORE = 'user.store';
    case USER_EDIT = 'user.edit';
    case USER_UPDATE = 'user.update';
    case USER_DESTROY = 'user.destroy';

    case PROFILE = 'profile';
    case PROFILE_UPDATE = 'profile.update';
    case PROFILE_PASSWORD_UPDATE = 'password.update';

    case CURD_GENERATOR_CREATE = 'crud.generator.create';
    case CURD_GENERATOR_STORE = 'crud.generator.store';

    case ROLE_INDEX = 'role.index';
    case ROLE_LIST = 'role-list';
    case ROLE_CREATE = 'role.create';
    case ROLE_STORE = 'role.store';
    case ROLE_EDIT = 'role.edit';
    case ROLE_UPDATE = 'role.update';
    case ROLE_DESTROY = 'role.destroy';

    case PERMISSION_GROUP_INDEX = 'permissiongroup.index';
    case PERMISSION_GROUP_LIST = 'permissiongroup-list';
    case PERMISSION_GROUP_CREATE = 'permissiongroup.create';
    case PERMISSION_GROUP_STORE = 'permissiongroup.store';
    case PERMISSION_GROUP_EDIT = 'permissiongroup.edit';
    case PERMISSION_GROUP_UPDATE = 'permissiongroup.update';
    case PERMISSION_GROUP_DESTROY = 'permissiongroup.destroy';

    case PERMISSION_INDEX = 'permission.index';
    case PERMISSION_LIST = 'permission-list';
    case PERMISSION_CREATE = 'permission.create';
    case PERMISSION_STORE = 'permission.store';
    case PERMISSION_EDIT = 'permission.edit';
    case PERMISSION_UPDATE = 'permission.update';
    case PERMISSION_DESTROY = 'permission.destroy';

    case ROLE_HAS_PERMISSION_INDEX = 'rolehaspermission.index';
    case ROLE_HAS_PERMISSION_LIST = 'rolehaspermission-list';
    case ROLE_HAS_PERMISSION_CREATE = 'rolehaspermission.create';
    case ROLE_HAS_PERMISSION_STORE = 'rolehaspermission.store';
    case ROLE_HAS_PERMISSION_EDIT = 'rolehaspermission.edit';
    case ROLE_HAS_PERMISSION_UPDATE = 'rolehaspermission.update';
    case ROLE_HAS_PERMISSION_DESTROY = 'rolehaspermission.destroy';
}
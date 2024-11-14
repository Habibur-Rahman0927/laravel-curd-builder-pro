<?php

namespace App\Enums;

enum PermissionGroupEnum: string
{
    case DASHBOARD = 'dashboard';

    case User = 'user';

    case PROFILE = 'profile';

    case CURD_GENERATOR = 'curd';

    case ROLE = 'role';

    case PERMISSION_GROUP = 'permissiongroup';

    case PERMISSION = 'permission';

    case LANGUAGE = 'language';

    case ROLE_HAS_PERMISSION = 'rolehaspermission';
}


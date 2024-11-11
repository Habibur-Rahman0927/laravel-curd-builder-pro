<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\PermissionGroupEnum;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $superAdminPermissions = [
            ['name' => PermissionEnum::ADMIN_DASHBOARD->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::DASHBOARD->value, 'created_at' => now(), 'updated_at' => now()],
        
            // User permissions
            ['name' => PermissionEnum::USER_INDEX->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_LIST->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_EDIT->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::USER_DESTROY->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::User->value, 'created_at' => now(), 'updated_at' => now()],

            ['name' => PermissionEnum::PROFILE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PROFILE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PROFILE_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PROFILE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PROFILE_PASSWORD_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PROFILE->value, 'created_at' => now(), 'updated_at' => now()],
            
            // Curd permissions
            ['name' => PermissionEnum::CURD_GENERATOR_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::CURD_GENERATOR->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::CURD_GENERATOR_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::CURD_GENERATOR->value, 'created_at' => now(), 'updated_at' => now()],

            // Role permissions
            ['name' => PermissionEnum::ROLE_INDEX->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_LIST->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_EDIT->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_DESTROY->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE->value, 'created_at' => now(), 'updated_at' => now()],

            // User permissions
            ['name' => PermissionEnum::PERMISSION_GROUP_INDEX->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_LIST->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_EDIT->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_GROUP_DESTROY->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION_GROUP->value, 'created_at' => now(), 'updated_at' => now()],
        
            // Permission management
            ['name' => PermissionEnum::PERMISSION_INDEX->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_LIST->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_EDIT->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::PERMISSION_DESTROY->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
        
            // Role has permission
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_INDEX->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_LIST->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_CREATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_EDIT->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_STORE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_UPDATE->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => PermissionEnum::ROLE_HAS_PERMISSION_DESTROY->value, 'guard_name' => 'web', 'group_name' => PermissionGroupEnum::ROLE_HAS_PERMISSION->value, 'created_at' => now(), 'updated_at' => now()],
        ];        

        foreach ($superAdminPermissions as $permission) {
            Permission::create($permission);
        }

        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $permissionIds = Permission::all()->pluck('id')->toArray();
        $superAdminRole->syncPermissions($permissionIds);
        $superAdmin = User::query()->create([
            'name' => "Admin",
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'user_type' => User::USER_TYPE_SUPER_ADMIN,
            'is_active' => User::USER_IS_ACTIVE,
            'role_id' => $superAdminRole->id,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $superAdmin->assignRole($superAdminRole->name);

        $permissionGroups = PermissionGroupEnum::cases();
        foreach ($permissionGroups as $permissionGroup) {
            PermissionGroup::create(['name' => $permissionGroup->value]);
        }
    }
}

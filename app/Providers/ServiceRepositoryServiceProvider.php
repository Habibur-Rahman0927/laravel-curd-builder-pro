<?php

namespace App\Providers;

use App\Repositories\Role\IRoleRepository;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
use App\Services\Role\IRoleService;
use App\Services\Role\RoleService;
use App\Services\User\IUserService;
use App\Services\User\UserService;
use App\Repositories\Permission\IPermissionRepository;
use App\Repositories\Permission\PermissionRepository;
use App\Services\Permission\IPermissionService;
use App\Services\Permission\PermissionService;
use App\Repositories\RoleHasPermission\IRoleHasPermissionRepository;
use App\Repositories\RoleHasPermission\RoleHasPermissionRepository;
use App\Services\CurdGenerator\CurdGeneratorService;
use App\Services\CurdGenerator\ICurdGeneratorService;
use App\Services\RoleHasPermission\IRoleHasPermissionService;
use App\Services\RoleHasPermission\RoleHasPermissionService;
use App\Repositories\PermissionGroup\IPermissionGroupRepository;
use App\Repositories\PermissionGroup\PermissionGroupRepository;
use App\Services\PermissionGroup\IPermissionGroupService;
use App\Services\PermissionGroup\PermissionGroupService;
use App\Repositories\Language\ILanguageRepository;
use App\Repositories\Language\LanguageRepository;
use App\Services\Language\ILanguageService;
use App\Services\Language\LanguageService;
use Illuminate\Support\ServiceProvider;

class ServiceRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $repositories = [
            ILanguageRepository::class => LanguageRepository::class,
            IPermissionGroupRepository::class => PermissionGroupRepository::class,
            IRoleHasPermissionRepository::class => RoleHasPermissionRepository::class,
            IPermissionRepository::class => PermissionRepository::class,
            IRoleRepository::class => RoleRepository::class,
            IUserRepository::class => UserRepository::class,
        ];

        $services = [
            ILanguageService::class => LanguageService::class,
            IPermissionGroupService::class => PermissionGroupService::class,
            IRoleHasPermissionService::class => RoleHasPermissionService::class,
            IPermissionService::class => PermissionService::class,
            IRoleService::class => RoleService::class,
            ICurdGeneratorService::class => CurdGeneratorService::class,
            IUserService::class => UserService::class,
        ];
        $bindings = array_merge($repositories, $services);
        $this->bindServiceRepositories($bindings);
    }

    /**
     * Helper function to bind repository interfaces to their implementations
     */
    protected function bindServiceRepositories(array $repositories): void
    {
        foreach ($repositories as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

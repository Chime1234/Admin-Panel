<?php

namespace Database\Seeders\v130;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;


class PermissionsTableV130Seeder extends Seeder
{

    private array $onlyControllers = [
        'ModuleController',
    ];

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run(): void
    {
        try {
            DB::Table('permissions')->insert(array(
                array(
                    'id' => 235,
                    'name' => 'modules.update',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
                array(
                    'id' => 236,
                    'name' => 'modules.install',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
                array(
                    'id' => 237,
                    'name' => 'modules.index',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ),
                array(
                    'id' => 238,
                    'name' => 'modules.enable',
                    'guard_name' => 'web',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                )));
            DB::Table('role_has_permissions')->insert(array(
                array(
                    'permission_id' => 235,
                    'role_id' => 2,
                ),
                array(
                    'permission_id' => 236,
                    'role_id' => 2,
                ),
                array(
                    'permission_id' => 237,
                    'role_id' => 2,
                ),
                array(
                    'permission_id' => 238,
                    'role_id' => 2,
                ),
            ));
        } catch (Exception $e) {
            Log::error($e);
        }

        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $route) {
            if ($this->match($route)) {
                // PermissionDoesNotExist
                try {
                    Permission::findOrCreate($route->getName(), 'web');
                    // give permissions to admin role
                    Role::findOrFail(2)->givePermissionTo($route->getName());
                } catch (Exception $e) {
                    Log::error($e);
                }
            }
        }
    }

    private function match(\Illuminate\Routing\Route $route): bool
    {
        if ($route->getName() === null) {
            return false;
        } else {
            if (str_contains(class_basename($route->getController()), 'API')) {
                return false;
            }
            if (in_array(class_basename($route->getController()), $this->onlyControllers)) {
                return true;
            }
        }
        return false;
    }
}

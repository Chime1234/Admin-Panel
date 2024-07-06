<?php
/*
 * File name: RefreshPermissionsSeeder.php
 * Last modified: 2021.01.07 at 13:46:50
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2024
 */
namespace Database\Seeders;

use DB;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RefreshPermissionsSeeder extends Seeder
{
    //$ php artisan db:seed --class=RefreshPermissionsSeeder
    private array $exceptNames = [
        'LaravelInstaller*',
        'LaravelUpdater*',
        'debugbar*',
        'cashier.*'
    ];

    private array $exceptControllers = [
        'LoginController',
        'ForgotPasswordController',
        'ResetPasswordController',
        'RegisterController',
        'PayPalController'
    ];

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $route) {
            if ($this->match($route)) {
                // PermissionDoesNotExist
                try {
                    Permission::findOrCreate($route->getName(), 'web');
                    Role::findOrFail(2)->givePermissionTo($route->getName());
                } catch (Exception $e) {

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
            if (in_array(class_basename($route->getController()), $this->exceptControllers)) {
                return false;
            }
            foreach ($this->exceptNames as $except) {
                if (Str::is($except, $route->getName())) {
                    return false;
                }
            }
        }
        return true;
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $rightsAccessNamespace = 'App\RightsAccess';
        // Админу можно всё
        Gate::before(function ($user) {
            if ($user->role->sys_name == 'admin')
                return true;
        });

        Gate::define('dish-see', $rightsAccessNamespace . '\Nomenclature\DishRights@see');
        Gate::define('dish-add', $rightsAccessNamespace . '\Nomenclature\DishRights@add');
        Gate::define('dish-edit', $rightsAccessNamespace . '\Nomenclature\DishRights@edit');
        Gate::define('dish-delete', $rightsAccessNamespace . '\Nomenclature\DishRights@delete');

        Gate::define('ingredient-see', $rightsAccessNamespace . '\Nomenclature\IngredientRights@see');
        Gate::define('ingredient-add', $rightsAccessNamespace . '\Nomenclature\IngredientRights@add');
        Gate::define('ingredient-edit', $rightsAccessNamespace . '\Nomenclature\IngredientRights@edit');
        Gate::define('ingredient-delete', $rightsAccessNamespace . '\Nomenclature\IngredientRights@delete');

        Gate::define('adaptation-see', $rightsAccessNamespace . '\Nomenclature\AdaptationRights@see');
        Gate::define('adaptation-add', $rightsAccessNamespace . '\Nomenclature\AdaptationRights@add');
        Gate::define('adaptation-edit', $rightsAccessNamespace . '\Nomenclature\AdaptationRights@edit');
        Gate::define('adaptation-delete', $rightsAccessNamespace . '\Nomenclature\AdaptationRights@delete');

        Gate::define('product-see', $rightsAccessNamespace . '\Nomenclature\ProductRights@see');
        Gate::define('product-add', $rightsAccessNamespace . '\Nomenclature\ProductRights@add');
        Gate::define('product-edit', $rightsAccessNamespace . '\Nomenclature\ProductRights@edit');
        Gate::define('product-delete', $rightsAccessNamespace . '\Nomenclature\ProductRights@delete');

        Gate::define('client-see', $rightsAccessNamespace . '\Order\ClientRights@see');
        Gate::define('client-add', $rightsAccessNamespace . '\Order\ClientRights@add');
        Gate::define('client-edit', $rightsAccessNamespace . '\Order\ClientRights@edit');
        Gate::define('client-delete', $rightsAccessNamespace . '\Order\ClientRights@delete');

        Gate::define('order-see', $rightsAccessNamespace . '\Order\OrderRights@see');
        Gate::define('order-add', $rightsAccessNamespace . '\Order\OrderRights@add');
        Gate::define('order-edit', $rightsAccessNamespace . '\Order\OrderRights@edit');
        Gate::define('order-delete', $rightsAccessNamespace . '\Order\OrderRights@delete');
        Gate::define('order-confirm', $rightsAccessNamespace . '\Order\OrderRights@confirm');
        Gate::define('order-close', $rightsAccessNamespace . '\Order\OrderRights@close');

        //Просмотр информации шеф повара
        Gate::define('chef_info-see', function ($user) {
            if ($user->role->sys_name == 'chef')
                return true;
            return false;
        });

        //Просмотр информации начальника склада
        Gate::define('wh_head_info-see', function ($user) {
            if ($user->role->sys_name == 'wh_head')
                return true;
            return false;
        });

        //Просмотр информации менеджера
        Gate::define('manager_info-see', function ($user) {
            if ($user->role->sys_name == 'manager')
                return true;
            return false;
        });

        //Просмотр скрытой информации
        Gate::define('hidden_info-see', function () {
            return false;
        });

        //Просмотр информации снабженца
        Gate::define('supplier_info-see', function ($user) {
            if ($user->role->sys_name == 'supplier')
                return true;
            return false;
        });
    }
}

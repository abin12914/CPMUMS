<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //current user to all views
        View::composer('*', "App\Http\ViewComposers\AllViewComposer");
        //accounts to views
        View::composer('components.selects.accounts', "App\Http\ViewComposers\AccountComponentComposer");
        //employees to views
        View::composer('components.selects.employees', "App\Http\ViewComposers\EmployeeComponentComposer");
        //branches to views
        View::composer('components.selects.branches', "App\Http\ViewComposers\BranchComponentComposer");
        //products to views
        View::composer(['components.selects.products', 'components.selects.products_custom'], "App\Http\ViewComposers\ProductComponentComposer");
        //materials to views
        View::composer('components.selects.materials', "App\Http\ViewComposers\MaterialComponentComposer");
        //services to views
        View::composer('components.selects.services', "App\Http\ViewComposers\ServiceComponentComposer");
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

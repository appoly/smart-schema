<?php

namespace Appoly\SmartSchema;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;
use Appoly\SmartSchema\Console\Commands\GenerateCrud;
use Appoly\SmartSchema\Console\Commands\RegenerateSchema;

class FormsProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                RegenerateSchema::class,
                GenerateCrud::class,
            ]);
        }

        //Artisan::call('make:migration:schema', ['name' => 'create_test_table', '--schema' => 'username:string, email:string:unique']);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'smartschema');
    }
}

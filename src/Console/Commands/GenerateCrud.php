<?php

namespace Appoly\SmartSchema\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Appoly\SmartSchema\SchemaHelper;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate {resource_name_singular}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates views';

    protected $replacement;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $resource_name = $this->argument('resource_name_singular');
        $this->replacement = [
            '%pretty_name_singular%' => ucwords($resource_name),
            '%pretty_name_plural%' => ucwords(str_plural($resource_name)),
            '{{namespace}}' => Container::getInstance()->getNamespace(),
            '{{single_namespace}}' => substr(Container::getInstance()->getNamespace(), 0, -1),
            '%class%' => str_replace(' ', '', ucwords($resource_name)),
            '%class_plural%' => str_replace(' ', '', ucwords(str_plural($resource_name))),
            '%controller%' => str_replace(' ', '', str_plural(ucwords($resource_name))),
            '%variable_plural%' => camel_case(str_slug(str_plural($resource_name), '_')),
            '%variable_singular%' => str_singular(camel_case(str_slug($resource_name, '_'))),
            '%kebab_case_plural%' => str_slug(str_plural($resource_name)),
            '%table%' => str_replace(' ', '_', strtolower(str_plural($resource_name))),
        ];
        //dd($this->replacement);

        $this->createViews();
        $this->createController();

        //SchemaHelper::get($this->argument('model_name'))->save();
    }

    public function createViews()
    {
        if (! is_dir(resource_path('views/'.$this->replacement['%kebab_case_plural%']))) {
            mkdir(resource_path('views/'.$this->replacement['%kebab_case_plural%']));
        }

        //$fields = $this->getFields();
        $views = ['create', 'index', 'edit'];

        // Foreach through fields and generate the required HTML?

        // Create table headers
        // Then pass to the form?
        foreach ($views as $view) {
            $viewContents = str_replace(
                array_keys($this->replacement),
                array_values($this->replacement),
                file_get_contents(__DIR__.'/../../Crud/templates/views/'.$view.'.blade.template')
            );

            file_put_contents(resource_path('views/'.$this->replacement['%kebab_case_plural%'].'/'.$view.'.blade.php'), $viewContents);
        }

        return true;
    }

    public function createController()
    {
        $controllerContents = str_replace(
            array_keys($this->replacement),
            array_values($this->replacement),
            file_get_contents(__DIR__.'/../../Crud/templates/controllers/Controller.template')
        );

        return file_put_contents(app_path('Http/Controllers/'.$this->replacement['%controller%'].'Controller.php'), $controllerContents);
    }
}

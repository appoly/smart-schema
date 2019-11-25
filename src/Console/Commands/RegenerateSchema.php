<?php

namespace Appoly\SmartSchema\Console\Commands;

use Appoly\SmartSchema\SchemaHelper;
use Illuminate\Console\Command;

class RegenerateSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schema:generate {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates/regenerates tables';

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
        SchemaHelper::get($this->argument('name'))->save();
    }
}

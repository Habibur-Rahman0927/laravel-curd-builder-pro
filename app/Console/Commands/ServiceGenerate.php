<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\BaseCommand;

class ServiceGenerate extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:service-gen {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates service interface and implementation files for the specified service name.';

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if (empty($name)) {
            $this->error('The name argument cannot be empty.');
            return;
        }

        try {
            if ($this->generateServiceInterfaceFile($name) && $this->generateImplementServiceFile($name)) {
                $this->info('Service files generated successfully.');
            } else {
                $this->error('Some files were not generated. Please check the errors and try again.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}

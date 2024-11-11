<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\BaseCommand;

class ViewTemplateGenerate extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:view-gen {name : The name of the view}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates blade view files';

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
            
            if ($this->generateViewTemplateFile($name)) {
                $this->info('View files generated successfully.');
            } else {
                $this->error('Some files were not generated. Please check the errors and try again.');
            }
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}

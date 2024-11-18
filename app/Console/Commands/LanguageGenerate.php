<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\BaseCommand;

class LanguageGenerate extends BaseCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:language-gen {name : The name of the files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new languages files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $nameUcfirst = ucfirst($name);
        if (empty($name)) {
            $this->error('The name argument cannot be empty.');
            return;
        }

        try {
            if ($this->generateLanguage($nameUcfirst))
            {
                $this->info("Language files generated successfully: " . $nameUcfirst);
            } else {
                $this->error('Some files were not generated. Please check the errors and try again.');
            } 
        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
        }
    }
}

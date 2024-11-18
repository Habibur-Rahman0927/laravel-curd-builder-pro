<?php

namespace App\Console\Commands\Concerns;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

abstract class BaseCommand extends Command
{
    /**
     * Generate a controller file.
     *
     * @param string $name
     * @return bool
     */
    protected function generateController(string $name): bool
    {
        $filePath = app_path("Http/Controllers/Admin/{$name}Controller.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.controller.controller', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Controller file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create controller file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a controller file.
     *
     * @param string $name
     * @return bool
     */
    protected function generateControllerApi(string $name): bool
    {
        $filePath = app_path("Http/Controllers/Api/{$name}Controller.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }
            $bearerAuth = '{{ "bearerAuth":{} }}';
            $content = view('templates.controller.controller_api', compact('name', 'bearerAuth'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Controller Api file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create controller api file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate a request file.
     *
     * @param string $name
     * @param string $type
     * @return bool
     */
    protected function generateRequest(string $name, string $type): bool
    {
        $filePath = app_path("Http/Requests/{$type}{$name}Request.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.request.' . strtolower($type) . '_request', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Request file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create request file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generates the service interface file.
     *
     * @param string $name The name of the service
     * @return bool
     */
    protected function generateServiceInterfaceFile(string $name): bool
    {
        $directory = app_path("Services/{$name}");

        if (!$this->createDirectoryIfNotExists($directory)) {
            return false;
        }

        $filePath = app_path("Services/{$name}/I{$name}Service.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.service.service_interface', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Service interface file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create service interface file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generates the service implementation file.
     *
     * @param string $name The name of the service
     * @return bool
     */
    protected function generateImplementServiceFile(string $name): bool
    {
        $directory = app_path("Services/{$name}");

        if (!$this->createDirectoryIfNotExists($directory)) {
            return false;
        }

        $filePath = app_path("Services/{$name}/{$name}Service.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.service.service_implement', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Service implementation file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create service implementation file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generates the repository interface file.
     *
     * @param string $name The name of the repository
     * @return bool
     */
    protected function generateRepositoryInterfaceFile(string $name): bool
    {
        $directory = app_path("Repositories/{$name}");

        if (!$this->createDirectoryIfNotExists($directory)) {
            return false;
        }

        $filePath = app_path("Repositories/{$name}/I{$name}Repository.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.repository.repository_interface', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Repository interface file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create repository interface file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generates the repository implementation file.
     *
     * @param string $name The name of the repository
     * @return bool
     */
    protected function generateImplementRepositoryFile(string $name): bool
    {
        $directory = app_path("Repositories/{$name}");

        if (!$this->createDirectoryIfNotExists($directory)) {
            return false;
        }

        $filePath = app_path("Repositories/{$name}/{$name}Repository.php");

        try {
            if ($this->fileExists($filePath)) {
                return false;
            }

            $content = view('templates.repository.repository_implement', compact('name'))->render();
            File::put($filePath, "<?php \n\n\n" . $content);
            $this->info("Repository implementation file created: {$filePath}");

            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create repository implementation file: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if the file already exists.
     *
     * @param string $filePath
     * @return bool
     */
    protected function fileExists(string $filePath): bool
    {
        try {
            if (File::exists($filePath)) {
                $this->error("File already exists: {$filePath}");
                return true;
            }
            return false;
        } catch (\Exception $e) {
            $this->error('Failed to check if file exists: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Creates a directory if it doesn't exist.
     *
     * @param string $directory The directory path
     * @return bool
     */
    protected function createDirectoryIfNotExists(string $directory): bool
    {
        try {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0777, true, true);
                $this->info("Directory created: {$directory}");
            }
            return true;
        } catch (\Exception $e) {
            $this->error('Failed to create directory: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate view templates for the specified name.
     *
     * @param string $name
     * @return bool
     */
    protected function generateViewTemplateFile(string $name): bool
    {
        try {
            $lcfirst = lcfirst($name);
            $directory = resource_path("views/admin/{$lcfirst}");
            if ($this->createDirectoryIfNotExists($directory)) {
                $this->copyViewFiles($lcfirst, $directory);
                $this->copyJsFiles($lcfirst);
                $this->info("Templates generated successfully.");
                return true;
            }
            $this->error("Failed to create directory: {$directory}");
            return false;

        } catch (\Exception $e) {
            $this->error("Error generating templates: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Copy view files from the template directory to the target directory and generate them.
     *
     * @param string $name
     * @param string $directory
     * @return void
     * @throws \Exception
     */
    private function copyViewFiles(string $name, string $directory): void
    {
        try {
            $sourceDirectory = resource_path("views/templates/view");

            if (!File::exists($sourceDirectory)) {
                throw new \Exception("Source directory not found: {$sourceDirectory}");
            }

            $files = File::files($sourceDirectory);

            foreach ($files as $file) {
                $content = File::get($file);
                $modifiedContent = $this->modifyContent($content, $name);
                $destination = "{$directory}/" . pathinfo($file, PATHINFO_BASENAME);

                File::put($destination, $modifiedContent);
                $this->info("File '{$destination}' generated successfully.");
            }
        } catch (\Exception $e) {
            $this->error("Error copying view files: " . $e->getMessage());
            throw $e;  // Re-throw to handle in the calling method
        }
    }

    /**
     * Copy and modify JavaScript files.
     *
     * @param string $name
     * @return void
     * @throws \Exception
     */
    private function copyJsFiles(string $name): void
    {
        try {
            $jsSourceDirectory = resource_path("views/templates/view/js");

            if (!File::exists($jsSourceDirectory)) {
                throw new \Exception("JavaScript source directory not found: {$jsSourceDirectory}");
            }

            $jsDirectory = resource_path("assets/js");
            $files = File::files($jsSourceDirectory);

            foreach ($files as $file) {
                $content = File::get($file);
                $modifiedContent = $this->modifyContent($content, $name);
                $jsDestination = "{$jsDirectory}/{$name}.js";

                File::put($jsDestination, $modifiedContent);
                $this->info("JavaScript file '{$jsDestination}' generated successfully.");
            }
        } catch (\Exception $e) {
            $this->error("Error copying JavaScript files: " . $e->getMessage());
            throw $e;  // Re-throw to handle in the calling method
        }
    }

    /**
     * Replace placeholders in the content with the specified name.
     *
     * @param string $content
     * @param string $name
     * @return string
     */
    private function modifyContent(string $content, string $name): string
    {
        return str_replace('{{ name }}', $name, $content);
    }

    /**
     * Generate Language for the specified Module.
     *
     * @param string $name The name of the model.
     * @param array $fields An array of fields for the form and blade.
     * @return \Exception|bool
     */
    public function generateLanguage(string $name): bool|Exception
    {
        try {
            $langBasePath = base_path('lang');
            
            if (!is_dir($langBasePath)) {
                throw new \Exception("Source directory not found: {$langBasePath}");
            }

            $languageFolders = array_filter(glob($langBasePath . '/*'), 'is_dir');

            $languageContent = [
                'create_list_edit' => [
                    'list_page_title' => 'List ' . ucwords(str_replace('_', ' ', Str::snake($name))),
                    lcfirst($name) => $name,
                    'list_' . lcfirst($name) . '_list' => ucwords(str_replace('_', ' ', Str::snake($name))) . ' List',
                    'create_page_title' => 'Create ' . ucwords(str_replace('_', ' ', Str::snake($name))),
                    'edit_page_title' => 'Edit ' . ucwords(str_replace('_', ' ', Str::snake($name))),
                ],
                'field_label' => [
                    'name' => 'Name'
                ],
            ];

            foreach ($languageFolders as $folder) {
                $langFilePath = $folder . '/' . $name . '_module.php';

                if (!file_exists($langFilePath)) {
                    $content = "<?php\n\nreturn " . var_export($languageContent, true) . ";\n";
                    file_put_contents($langFilePath, $content);
                } else {
                    throw new \Exception("File already exist: {$langFilePath}");
                } 
            }
            return true;
        } catch (\Exception $e) {
            $this->error("Error creating Language files: " . $e->getMessage());
            throw $e;
        }
    }
}

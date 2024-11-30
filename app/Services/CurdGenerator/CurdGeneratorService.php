<?php 


namespace App\Services\CurdGenerator;

use App\Repositories\Permission\IPermissionRepository;
use App\Repositories\PermissionGroup\IPermissionGroupRepository;
use App\Repositories\Role\IRoleRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CurdGeneratorService extends BaseService implements ICurdGeneratorService
{
    public function __construct(private IPermissionRepository $permissionRepository, 
                                private IRoleRepository $roleRepository,
                                private IPermissionGroupRepository $permissiongroupRepository)
    {
        parent::__construct($permissionRepository);
    }

    /**
     * Generate a model with the specified name and options.
     *
     * @param string $modelName The name of the model.
     * @param bool $softDelete Indicates whether the model should use soft deletes.
     * @param array $fields An array of field definitions for the model.
     * @param array $relationships An array of relationships to define for the model.
     * @return array
     */
    public function generateModel(string $modelName, bool $softDelete, array $fields, array $relationships = null): array
    {
        $modelPath = app_path("Models/{$modelName}.php");

        try {
            if (File::exists($modelPath)) {
                return ['success' => false, 'error' => "Model {$modelName} already exists."];
            }

            $modelTemplate = "<?php\n\nnamespace App\Models;\n\nuse Illuminate\Database\Eloquent\Model;\n";

            if ($softDelete) {
                $modelTemplate .= "use Illuminate\Database\Eloquent\SoftDeletes;\n";
            }
            if ($relationships) {
                $relationshipTypes = array_unique(array_column($relationships, 'type'));

                foreach ($relationshipTypes as $type) {
                    switch ($type) {
                        case 'hasOne':
                            $modelTemplate .= "use Illuminate\Database\Eloquent\Relations\HasOne;\n";
                            break;
                        case 'hasMany':
                            $modelTemplate .= "use Illuminate\Database\Eloquent\Relations\HasMany;\n";
                            break;
                        case 'belongsTo':
                            $modelTemplate .= "use Illuminate\Database\Eloquent\Relations\BelongsTo;\n";
                            break;
                        case 'belongsToMany':
                            $modelTemplate .= "use Illuminate\Database\Eloquent\Relations\BelongsToMany;\n";
                            break;
                    }
                }
            }
            

            $modelTemplate .= "\nclass {$modelName} extends Model\n{\n";

            if ($softDelete) {
                $modelTemplate .= "\tuse SoftDeletes;\n\n";
            }
            $fillableFields = [];
            foreach ($fields as $field) {
                if (in_array($field['type'], ['foreignId'])) {
                    $fillableFields[] = explode(',', $field['name'])[0]; 
                } else if (in_array($field['type'], ['foreignIdFor'])) {
                    $fillableFields[] = Str::snake($field['name']) . '_id';
                } else {
                    $fillableFields[] = $field['name'];
                }
            }
            $modelTemplate .= "\t/**\n\t * The attributes that are mass assignable.\n\t *\n\t * @var array\n\t */\n";
            $modelTemplate .= "\tprotected \$fillable = [\n\t\t'" . implode("',\n\t\t'", $fillableFields) . "'\n\t];\n";

            if ($relationships) {
                foreach ($relationships as $relationship) {
                    $relatedModel = ucfirst($relationship['related_model']);
                    $foreignKey = $relationship['foreign_key'];
                    $relationshipType = $relationship['type'];
                    $upperCaseRelationType = ucfirst($relationship['type']);
    
                    $relationshipMethodName = ($relationshipType === 'belongsToMany' || $relationshipType === 'hasMany')
                        ? Str::camel(Str::plural($relatedModel))
                        : Str::camel($relatedModel);
    
                    if (preg_match("/function\s+{$relationshipMethodName}\s*\(/i", $modelTemplate)) {
                        continue;
                    }
    
                    $modelTemplate .= "\n\t/**\n\t * Define a {$upperCaseRelationType} relationship.\n\t *\n\t * @return {$upperCaseRelationType}\n\t */\n";
                    $modelTemplate .= "\tpublic function {$relationshipMethodName}(): {$upperCaseRelationType}\n\t{\n";
                    $modelTemplate .= "\t\treturn \$this->{$relationshipType}({$relatedModel}::class, '{$foreignKey}');\n\t}\n";
                }
            }

            $modelTemplate .= "}\n";

            File::put($modelPath, $modelTemplate);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate a migration file for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the migration.
     * @param bool $softDelete Indicates whether to include soft delete columns.
     * @return array
     */
    public function generateMigration(string $modelName, array $fields, bool $softDelete): array
    {
        $tableName = strtolower(Str::plural(Str::snake($modelName)));
        $migrationName = "create_{$tableName}_table";

        $migrationFiles = glob(database_path("migrations/*_{$migrationName}.php"));
        if (!empty($migrationFiles)) {
            return ['success' => false, 'error' => "Migration for table {$tableName} already exists."];
        }

        try {
            $migrationTemplate = "<?php\n\nuse Illuminate\Database\Migrations\Migration;\n";
            $migrationTemplate .= "use Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\Schema;\n\n";
            $migrationTemplate .= "return new class extends Migration\n{\n";

            $migrationTemplate .= "\t/**\n\t * Run the migrations.\n\t *\n\t * @return void\n\t */\n";
            $migrationTemplate .= "\tpublic function up()\n\t{\n";
            $migrationTemplate .= "\t\tSchema::create('{$tableName}', function (Blueprint \$table) {\n\t\t\t\$table->id();\n";

            $numericTypes = [
                'integer', 'tinyInteger', 'mediumInteger', 'bigInteger', 'smallInteger', 'unsignedBigInteger',
                'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger',
                'float', 'double', 'decimal', 'boolean'
            ];
            $foreignIdTypes = ['foreignId'];
            $foreignIdForTypes = ['foreignIdFor'];

            foreach ($fields as $field) {
                $fieldType = $field['type'];
                $fieldName = $field['name'];
                $length = isset($field['length']) ? ", {$field['length']}" : '';
                $nullable = isset($field['nullable']) ? '->nullable()' : '';
                $unique = isset($field['unique']) ? '->unique()' : '';
                $unsigned = isset($field['unsigned']) ? '->unsigned()' : '';

                if (in_array($fieldType, $foreignIdTypes)) {
                    $fieldName = explode(",", $field['name'])[0];
                    $constrainedTable = explode(",", $field['name'])[1];
                    $foreignId = "->constrained('{$constrainedTable}')->cascadeOnDelete()->cascadeOnUpdate()";
                } else if (in_array($fieldType, $foreignIdForTypes)) {
                    $fieldName = "\App\Models\\" . $fieldName . "::class";
                    $foreignId = '';
                    if (isset($field['index'])) {
                        $foreignId .='->index()';
                    }
                    $foreignId .= "->constrained()->cascadeOnDelete()->cascadeOnUpdate()";
                }else {
                    $foreignId = '';
                }

                if (isset($field['default'])) {
                    if (in_array($fieldType, $numericTypes)) {
                        $default = "->default({$field['default']})";
                    } else {
                        $default = "->default('{$field['default']}')";
                    }
                } else {
                    $default = '';
                }

                $comment = isset($field['comment']) ? "->comment('{$field['comment']}')" : '';

                if ($fieldType === 'foreignIdFor') {
                    $migrationTemplate .= "\t\t\t\$table->{$fieldType}({$fieldName}){$nullable}{$default}{$foreignId}{$comment};\n";
                } else {
                    $migrationTemplate .= "\t\t\t\$table->{$fieldType}('{$fieldName}'{$length}){$nullable}{$unique}{$unsigned}{$default}{$foreignId}{$comment};\n";
                }

                if (isset($field['index']) && !in_array($fieldType, $foreignIdForTypes)) {
                    $migrationTemplate .= "\t\t\t\$table->index('{$fieldName}');\n";
                }
            }

            $migrationTemplate .= "\t\t\t\$table->timestamps();\n";
            if ($softDelete) {
                $migrationTemplate .= "\t\t\t\$table->softDeletes();\n";
            }

            $migrationTemplate .= "\t\t});\n\t}\n\n";

            $migrationTemplate .= "\t/**\n\t * Reverse the migrations.\n\t *\n\t * @return void\n\t */\n";
            $migrationTemplate .= "\tpublic function down(): void\n\t{\n";
            $migrationTemplate .= "\t\tSchema::dropIfExists('{$tableName}');\n\t}\n";

            $migrationTemplate .= "};\n";

            $migrationPath = base_path("database/migrations/" . date('Y_m_d_His') . "_{$migrationName}.php");

            File::put($migrationPath, $migrationTemplate);

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate or bind the service and repository for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return void
     */
    public function generateOrBindServiceAndRepository(string $modelName): array
    {
        try {
            $modelName = ucfirst($modelName);

            $repositoryInterface = "use App\\Repositories\\$modelName\\I$modelName" . "Repository;";
            $repositoryClass = "use App\\Repositories\\$modelName\\$modelName" . "Repository;";
            $serviceInterface = "use App\\Services\\$modelName\\I$modelName" . "Service;";
            $serviceClass = "use App\\Services\\$modelName\\$modelName" . "Service;";

            $repositoryBinding = "I$modelName" . "Repository::class => $modelName" . "Repository::class,";
            $serviceBinding = "I$modelName" . "Service::class => $modelName" . "Service::class,";

            $filePath = app_path('Providers/ServiceRepositoryServiceProvider.php');
            $currentFileContent = file_get_contents($filePath);

            if (strpos($currentFileContent, $repositoryInterface) === false) {
                $lines = explode("\n", $currentFileContent);
                $importIndex = null;

                foreach ($lines as $index => $line) {
                    if (trim($line) === 'use Illuminate\Support\ServiceProvider;') {
                        $importIndex = $index;
                        break;
                    }
                }

                if ($importIndex !== null) {
                    array_splice($lines, $importIndex, 0, $repositoryInterface);
                    array_splice($lines, $importIndex + 1, 0, $repositoryClass);
                    array_splice($lines, $importIndex + 2, 0, $serviceInterface);
                    array_splice($lines, $importIndex + 3, 0, $serviceClass);
                    $updatedContent = implode("\n", $lines);
                    file_put_contents($filePath, $updatedContent);
                    $currentFileContent = $updatedContent;
                }
            }

            if (strpos($currentFileContent, $repositoryBinding) === false) {
                $lines = explode("\n", $currentFileContent);
                $repositoryIndex = null;

                foreach ($lines as $index => $line) {
                    if (trim($line) === '$repositories = [') {
                        $repositoryIndex = $index + 1;
                        break;
                    }
                }

                if ($repositoryIndex !== null) {
                    array_splice($lines, $repositoryIndex, 0, "            " . $repositoryBinding);
                    $updatedContent = implode("\n", $lines);
                    file_put_contents($filePath, $updatedContent);
                    $currentFileContent = $updatedContent;
                }
            }

            if (strpos($currentFileContent, $serviceBinding) === false) {
                $lines = explode("\n", $currentFileContent);
                $serviceIndex = null;

                foreach ($lines as $index => $line) {
                    if (trim($line) === '$services = [') {
                        $serviceIndex = $index + 1;
                        break;
                    }
                }

                if ($serviceIndex !== null) {
                    array_splice($lines, $serviceIndex, 0, "            " . $serviceBinding);
                    $updatedContent = implode("\n", $lines);
                    file_put_contents($filePath, $updatedContent);
                }
            }

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function generateController(string $modelName, $fieldNames): array
    {
        try {
            $className = ucfirst($modelName) . 'Controller';
            $serviceName = lcfirst($modelName) . 'Service';

            $filePath = app_path("Http/Controllers/Admin/{$className}.php");

            if (File::exists($filePath)) {
                return ['success' => false, 'error' => 'File Already Exist'];
            }

            // Initialize an array to store services to inject
            $serviceImports = [];
            $serviceImports[] = "use App\Services\\$modelName\\I{$modelName}Service;\n";

            $serviceInjections = [];
            $serviceInjections[] = "private I{$modelName}Service \${$serviceName},\n";

            $serviceInjectionNames = [];

            // Loop through field names and check if the field is of 'select' type and has a 'model_name'
            foreach ($fieldNames as $field => $fieldData) {
                if ($fieldData['input_type'] === 'select' && isset($fieldData['model_name']) && ($fieldData['create'] === 'on' || $fieldData['edit'] === 'on')) {
                    $serviceImports[] = "use App\Services\\{$fieldData['model_name']}\\I{$fieldData['model_name']}Service;\n"; 
                    $serviceInjections[] = "private I{$fieldData['model_name']}Service \$" . lcfirst($fieldData['model_name']) . "Service,\n";
                    $serviceInjectionNames[lcfirst($fieldData['model_name'])] = lcfirst($fieldData['model_name']) . "Service";
                }
            }
            $serviceImports = array_unique($serviceImports);
            $serviceInjections = array_unique($serviceInjections);


            $template = "<?php\n\n" .
    "namespace App\Http\Controllers\Admin;\n\n" .
    "use App\Http\Controllers\Controller;\n" ;
    foreach($serviceImports as $serviceImport) {
        $template .= $serviceImport;
    }
    $template .= "use Illuminate\Http\Request;\n" .
    "use Symfony\Component\HttpFoundation\Response as ResponseAlias;\n" .
    "use Illuminate\View\View;\n" .
    "use Illuminate\Http\RedirectResponse;\n" .
    "use App\Http\Requests\Create{$modelName}Request;\n" .
    "use App\Http\Requests\Update{$modelName}Request;\n" .
    "use Illuminate\Http\JsonResponse;\n" .
    "use Exception;\n\n" .
    "class $className extends Controller\n" .
    "{\n" .
    "    public function __construct(\n";
    foreach($serviceInjections as $serviceInjection) {
        $template .= "\t\t\t\t\t\t\t\t{$serviceInjection}";
    }
    $template .= "\t)\n" .
    "    {\n" .
    "    }\n\n" .
    "    public function index(): View\n" .
    "    {\n" .
    "        return view('admin." . lcfirst($modelName) . ".index');\n" .
    "    }\n\n" .
    "    public function getDatatables(Request \$request): JsonResponse\n" .
    "    {\n" .
    "        if (\$request->ajax()) {\n" .
    "            return \$this->{$serviceName}->get{$modelName}Data();\n" .
    "        }\n" .
    "        return response()->json([\n" .
    "            'success' => false,\n" .
    "            'message' => 'Invalid request.',\n" .
    "        ]);\n" .
    "    }\n\n" .
    "    public function create(): View\n" .
    "    {\n" ;

    foreach($serviceInjectionNames as $modelNameAsKey => $serviceInjectionName) {
        $template .= "\t\t\${$modelNameAsKey}s = \$this->{$serviceInjectionName}->findAll();\n";
    }

    $template .= "        return view('admin." . lcfirst($modelName) . ".create')->with([\n";
    foreach($serviceInjectionNames as $modelNameAsKey => $serviceInjectionName) {
        $template .= "\t\t\t'{$modelNameAsKey}s' => \${$modelNameAsKey}s,\n";
    }
    $template .= "\t\t]);\n" .
    "    }\n\n" .
    "    public function store(Create{$modelName}Request \$request): RedirectResponse\n" .
    "    {\n" .
    "        try {\n" .
    "            \$response = \$this->{$serviceName}->create(\$request->all());\n" .
    "            if (\$response) {\n" .
    "                return redirect()->back()->with('success', __('". strtolower($modelName)."_module.create_list_edit.". strtolower($modelName)."') . __('standard_curd_common_label.success'));\n" .
    "            }\n" .
    "        } catch (Exception \$e) {\n" .
    "            return redirect()->back()->with('error', __('standard_curd_common_label.error'));\n" .
    "        }\n\n" .
    "        return redirect()->back()->with('error', __('standard_curd_common_label.error'));\n" .
    "    }\n\n" .
    "    public function edit(string \$id): View\n" .
    "    {\n" .
    "        try {\n" .
    "            \$response = \$this->{$serviceName}->findById(\$id);\n" ;

    foreach($serviceInjectionNames as $modelNameAsKey => $serviceInjectionName) {
        $template .= "\t\t\t\${$modelNameAsKey}s = \$this->{$serviceInjectionName}->findAll();\n";
    }

    $template .= "            return view('admin." . lcfirst($modelName) . ".edit')->with([\n";
    $template .= "\t\t\t\t'data' => \$response,\n";
    foreach($serviceInjectionNames as $modelNameAsKey => $serviceInjectionName) {
        $template .= "\t\t\t\t'{$modelNameAsKey}s' => \${$modelNameAsKey}s,\n";
    }
    $template .= "\t\t\t]);\n" .
    "        } catch (Exception \$e) {\n" .
    "            return redirect()->back()->with('error', __('standard_curd_common_label.error'));\n" .
    "        }\n" .
    "    }\n\n" .
    "    public function update(Update{$modelName}Request \$request, string \$id): RedirectResponse\n" .
    "    {\n" .
    "        try {\n" .
    "            \$data = \$request->except(['_token', '_method']);\n" .
    "            \$this->{$serviceName}->update(['id' => \$id], \$data);\n" .
    "            return redirect()->back()->with('success', __('". strtolower($modelName)."_module.create_list_edit.". strtolower($modelName)."') . __('standard_curd_common_label.update_success'));\n" .
    "        } catch (Exception \$e) {\n" .
    "            return redirect()->back()->with('error', __('standard_curd_common_label.error'));\n" .
    "        }\n" .
    "    }\n\n" .
    "    public function destroy(string \$id): JsonResponse\n" .
    "    {\n" .
    "        try {\n" .
    "            \$data = \$this->{$serviceName}->deleteById(\$id);\n" .
    "            if (\$data) {\n" .
    "                return response()->json([\n" .
    "                    'message' => __('". strtolower($modelName)."_module.create_list_edit.". strtolower($modelName)."') . __('standard_curd_common_label.delete'),\n" .
    "                    'status_code' => ResponseAlias::HTTP_OK,\n" .
    "                    'data' => []\n" .
    "                ], ResponseAlias::HTTP_OK);\n" .
    "            }\n" .
    "            return response()->json([\n" .
    "                'message' => __('". strtolower($modelName)."_module.create_list_edit.". strtolower($modelName)."') . __('standard_curd_common_label.delete_is_not'),\n" .
    "                'status_code' => ResponseAlias::HTTP_BAD_REQUEST,\n" .
    "                'data' => []\n" .
    "            ], ResponseAlias::HTTP_BAD_REQUEST);\n" .
    "        } catch (Exception \$e) {\n" .
    "            return response()->json([\n" .
    "                'message' => __('standard_curd_common_label.error'),\n" .
    "                'status_code' => ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,\n" .
    "                'data' => []\n" .
    "            ], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n" .
    "        }\n" .
    "    }\n" .
    "}\n";


        File::put($filePath, $template);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate a create view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateCreateView(string $modelName, array $fields): array
    {  
        $lowerCaseModelName = strtolower($modelName);
        $viewPath = resource_path("views/admin/{$lowerCaseModelName}");

        if (!is_dir($viewPath)) {
            mkdir($viewPath, 0755, true); 
        }

        $createViewFilePath = $viewPath . "/create.blade.php";

        if (file_exists($createViewFilePath)) {
            return ['success' => false, 'error' => 'File Already Exist'];
        }

        try {
            $viewContent = "
@extends('layouts/layout')

@section('title', __('{$lowerCaseModelName}_module.create_list_edit.create_page_title'))

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class=\"content\">
        <div class=\"row\">
            <div class=\"col-md-12 page-header mb-2\">
                <div class=\"page-pretitle\">{{ __('{$lowerCaseModelName}_module.create_list_edit.{$lowerCaseModelName}') }}</div>
                <h1 class=\"page-title\">{{ __('{$lowerCaseModelName}_module.create_list_edit.create_page_title') }}</h1>
            </div>
        </div>

        <div class=\"row\">
            <div class=\"card shadow w-100\">
                <div class=\"card-body\">
                    <h5 class=\"card-title\"></h5>
                    @if (\$errors->any())
                        <div class=\"alert alert-danger\">
                            <ul>
                                @foreach (\$errors->all() as \$error)
                                    <li>{{ \$error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session(\"success\"))
                        <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                            {{ session('success') }}
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                            {{ session('error') }}
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                        </div>
                    @endif

                    <form method=\"POST\" action=\"{{ route('{$lowerCaseModelName}.store') }}\">
                        @csrf\n";
                        $checkboxInput = false;
        foreach ($fields as $fieldName => $attributes) {
            // Only create the input if the "create" attribute is "on"
            if (isset($attributes['create']) && $attributes['create'] === 'on') {
                $label = Str::snake($attributes['name']);
                $inputType = $attributes['input_type'] ?? 'text'; // Default to text
                $errorClass = "@error('{$fieldName}') is-invalid @enderror";
                $oldValue = "old('{$fieldName}')";
                // radio
                $values = isset($attributes['extra_values']) ? preg_split('/[\s,]+/', trim($attributes['extra_values'])) : [];
                // select
                $modelName = isset($attributes['model_name']) ? lcfirst($attributes['model_name']) .'s' : '';
                $modelNameAs = isset($attributes['model_name']) ? lcfirst($attributes['model_name']) : '';
                $optionsFieldName = isset($attributes['field_name']) ? $attributes['field_name'] : '';
                // Start input generation
                $viewContent .= "\t\t\t\t\t\t<div class=\"row mb-3\">\n";
                $viewContent .= "\t\t\t\t\t\t    <div class=\"col-md-12\">\n";
                $viewContent .= "\t\t\t\t\t\t        <label for=\"{$fieldName}\" class=\"form-label\">{{ __('{$lowerCaseModelName}_module.field_label.{$label}') }}</label>\n";

                switch ($inputType) {
                    case 'text':
                    case 'email':
                    case 'number':
                    case 'password':
                    case 'date':
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"{$inputType}\" name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" value=\"{{ {$oldValue} }}\" required>\n";
                        break;
    
                    case 'textarea':
                        $viewContent .= "\t\t\t\t\t\t        <textarea name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" required>{{ {$oldValue} }}</textarea>\n";
                        break;
                    
                    case 'radio':
                        foreach ($values as $value) {
                            $radioId = "{$fieldName}_{$value}";
                            $viewContent .= "\t\t\t\t\t\t        <div class=\"form-check ms-4\">\n";
                            $viewContent .= "\t\t\t\t\t\t            <input type=\"radio\" name=\"{$fieldName}\" class=\"form-check-input {$errorClass}\" id=\"{$radioId}\" value=\"{$value}\" {{ {$oldValue} === '{$value}' ? 'checked' : '' }}>\n";
                            $viewContent .= "\t\t\t\t\t\t            <label for=\"{$radioId}\" class=\"form-check-label\">{$value}</label>\n";
                            $viewContent .= "\t\t\t\t\t\t        </div>\n";
                        }
                        break;
                    case 'checkbox':
                        $checkboxInput = true;
                        foreach ($values as $value) {
                            $viewContent .= "\t\t\t\t\t\t        <div class=\"form-check\">\n";
                            $viewContent .= "\t\t\t\t\t\t            <input class=\"form-check-input {$errorClass}\" type=\"checkbox\" name=\"{$fieldName}[]\" id=\"{$fieldName}_{$value}\" value=\"{$value}\" {{ old('{$fieldName}') === \"{$value}\" ? 'checked' : '' }}>\n";
                            $viewContent .= "\t\t\t\t\t\t            <label class=\"form-check-label\" for=\"{$fieldName}_{$value}\">{{ __('{$value}') }}</label>\n";
                            $viewContent .= "\t\t\t\t\t\t        </div>\n";
                        }
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"hidden\" id=\"{$fieldName}_hidden\" name=\"{$fieldName}\" value=\"{{ {$oldValue} }}\">\n";
                        break;
                    case 'select':
                        $viewContent .= "\t\t\t\t                <select class=\"form-select {$errorClass}\" name=\"{$fieldName}\" id=\"{$fieldName}\" required>\n";
                        $viewContent .= "\t\t\t\t                     <option value='' selected> -- Select -- </option>\n";
                        $viewContent .= "\t\t\t\t                     @foreach (\${$modelName} as \${$modelNameAs})\n";
                        $viewContent .= "\t\t\t\t                     <option value=\"{{ \${$modelNameAs}->id }}\">{{ \${$modelNameAs}->{$optionsFieldName} }}</option>\n";
                        $viewContent .= "\t\t\t\t                     @endforeach\n";
                        $viewContent .= "\t\t\t\t                 </select>\n";
                        break;
                    default:
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"text\" name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" value=\"{{ {$oldValue} }}\" required>\n";
                }

                $viewContent .= "\t\t\t\t\t\t        @error('$fieldName')\n";
                $viewContent .= "\t\t\t\t\t\t            <div class=\"invalid-feedback\">\n";
                $viewContent .= "\t\t\t\t\t\t                {{ \$message }}\n";
                $viewContent .= "\t\t\t\t\t\t            </div>\n";
                $viewContent .= "\t\t\t\t\t\t        @enderror\n";
                $viewContent .= "\t\t\t\t\t\t    </div>\n";
                $viewContent .= "\t\t\t\t\t\t</div>\n";
            }
        }

        $viewContent .= "
                        <div class=\"row\">
                            <div class=\"col-md-12 text-end\"> 
                                <a href=\"{{ route('{$lowerCaseModelName}.index') }}\" class=\"btn btn-danger me-2\">{{ __('standard_curd_common_label.cancel') }}</a>
                                <button type=\"submit\" class=\"btn add-btn\">{{ __('standard_curd_common_label.submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection";
if ($checkboxInput) {
$viewContent .="
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateHiddenField(fieldName) {
        const checkboxes = document.querySelectorAll('input[name=\"' +fieldName + '[]\"]:checked');
        const selectedValues = Array.from(checkboxes).map(cb => cb.value);
        document.getElementById(fieldName + '_hidden').value = selectedValues.join(',');
    }

    document.querySelectorAll('input[type=\"checkbox\"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const fieldName = checkbox.name.split('[')[0]; // Extract field name from 'name=\"field[]\"'
            updateHiddenField(fieldName);
        });
    });

    document.querySelectorAll('input[type=\"checkbox\"]').forEach(function (checkbox) {
        const fieldName = checkbox.name.split('[')[0];
        updateHiddenField(fieldName);
    });
});
</script>
@endpush";
}


            File::put($createViewFilePath, $viewContent);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate an edit view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateEditView(string $modelName, array $fields): array
    {
        $lowerCaseModelName = strtolower($modelName);
        $viewPath = resource_path("views/admin/{$lowerCaseModelName}");

        if (!is_dir($viewPath)) {
            mkdir($viewPath, 0755, true); 
        }

        $editViewFilePath = $viewPath . "/edit.blade.php";

        if (file_exists($editViewFilePath)) {
            return ['success' => false, 'error' => 'File Already Exist'];
        }

        try {
            $viewContent = "
@extends('layouts/layout')

@section('title', __('{$lowerCaseModelName}_module.create_list_edit.edit_page_title'))

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class=\"content\">
        <div class=\"row\">
            <div class=\"col-md-12 page-header mb-2\">
                <div class=\"page-pretitle\">{{ __('{$lowerCaseModelName}_module.create_list_edit.{$lowerCaseModelName}') }}</div>
                <h1 class=\"page-title\">{{ __('{$lowerCaseModelName}_module.create_list_edit.edit_page_title') }}</h1>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"card shadow w-100\">
                <div class=\"card-body\">
                    <h5 class=\"card-title\"></h5>
                    @if (\$errors->any())
                        <div class=\"alert alert-danger\">
                            <ul>
                                @foreach (\$errors->all() as \$error)
                                    <li>{{ \$error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session(\"success\"))
                        <div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">
                            {{ session('success') }}
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
                            {{ session('error') }}
                            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                        </div>
                    @endif

                    <form method=\"POST\" action=\"{{ route('{$lowerCaseModelName}.update', \$data->id) }}\">
                        @csrf
                        @method('PUT')\n";
                        $checkboxInput = false;
        foreach ($fields as $fieldName => $attributes) {
            // Only create the input if the "create" attribute is "on"
            if (isset($attributes['create']) && $attributes['create'] === 'on') {
                $label = Str::snake($attributes['name']);
                $inputType = $attributes['input_type'] ?? 'text'; // Default to text
                $errorClass = "@error('{$fieldName}') is-invalid @enderror";
                $oldValue = "old('{$fieldName}', \$data->{$fieldName})";
                // radio
                $values = isset($attributes['extra_values']) ? preg_split('/[\s,]+/', trim($attributes['extra_values'])) : [];

                // select
                $modelName = isset($attributes['model_name']) ? lcfirst($attributes['model_name']) .'s' : '';
                $modelNameAs = isset($attributes['model_name']) ? lcfirst($attributes['model_name']) : '';
                $optionsFieldName = isset($attributes['field_name']) ? $attributes['field_name'] : '';

                // Start input generation
                $viewContent .= "\t\t\t\t\t\t<div class=\"row mb-3\">\n";
                $viewContent .= "\t\t\t\t\t\t    <div class=\"col-md-12\">\n";
                $viewContent .= "\t\t\t\t\t\t        <label for=\"{$fieldName}\" class=\"form-label\">{{ __('{$lowerCaseModelName}_module.field_label.{$label}') }}</label>\n";

                switch ($inputType) {
                    case 'text':
                    case 'email':
                    case 'number':
                    case 'password':
                    case 'date':
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"{$inputType}\" name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" value=\"{{ {$oldValue} }}\" required>\n";
                        break;
    
                    case 'textarea':
                        $viewContent .= "\t\t\t\t\t\t        <textarea name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" required>{{ {$oldValue} }}</textarea>\n";
                        break;
                    case 'radio':
                        foreach ($values as $value) {
                            $radioId = "{$fieldName}_{$value}";
                            $viewContent .= "\t\t\t\t\t\t        <div class=\"form-check ms-4\">\n";
                            $viewContent .= "\t\t\t\t\t\t            <input type=\"radio\" name=\"{$fieldName}\" class=\"form-check-input {$errorClass}\" id=\"{$radioId}\" value=\"{$value}\" {{ {$oldValue} === '{$value}' ? 'checked' : '' }}>\n";
                            $viewContent .= "\t\t\t\t\t\t            <label for=\"{$radioId}\" class=\"form-check-label\">{$value}</label>\n";
                            $viewContent .= "\t\t\t\t\t\t        </div>\n";
                        }
                        break;
                    case 'checkbox':
                        $checkboxInput = true;
                        foreach ($values as $value) {
                            $viewContent .= "\t\t\t\t\t\t        <div class=\"form-check\">\n";
                            $viewContent .= "\t\t\t\t\t\t            <input class=\"form-check-input {$errorClass}\" type=\"checkbox\" name=\"{$fieldName}[]\" id=\"{$fieldName}_{$value}\" value=\"{$value}\">\n";
                            $viewContent .= "\t\t\t\t\t\t            <label class=\"form-check-label\" for=\"{$fieldName}_{$value}\">{{ __('{$value}') }}</label>\n";
                            $viewContent .= "\t\t\t\t\t\t        </div>\n";
                        }
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"hidden\" id=\"{$fieldName}_hidden\" name=\"{$fieldName}\" value=\"{{ {$oldValue} }}\">\n";
                        break;
                    case 'select':
                        $viewContent .= "\t\t\t\t                <select class=\"form-select {$errorClass}\" name=\"{$fieldName}\" id=\"{$fieldName}\" required>\n";
                        $viewContent .= "\t\t\t\t                     <option value='' selected> -- Select -- </option>\n";
                        $viewContent .= "\t\t\t\t                     @foreach (\${$modelName} as \${$modelNameAs})\n";
                        $viewContent .= "\t\t\t\t                     <option value=\"{{ \${$modelNameAs}->id }}\" {{ {$oldValue} == \${$modelNameAs}->id ? 'selected' : '' }}>{{ \${$modelNameAs}->{$optionsFieldName} }}</option>\n";
                        $viewContent .= "\t\t\t\t                     @endforeach\n";
                        $viewContent .= "\t\t\t\t                 </select>\n";
                        break;

                    default:
                        $viewContent .= "\t\t\t\t\t\t        <input type=\"text\" name=\"{$fieldName}\" class=\"form-control {$errorClass}\" id=\"{$fieldName}\" value=\"{{ {$oldValue} }}\" required>\n";
                }

                $viewContent .= "\t\t\t\t\t\t        @error('$fieldName')\n";
                $viewContent .= "\t\t\t\t\t\t            <div class=\"invalid-feedback\">\n";
                $viewContent .= "\t\t\t\t\t\t                {{ \$message }}\n";
                $viewContent .= "\t\t\t\t\t\t            </div>\n";
                $viewContent .= "\t\t\t\t\t\t        @enderror\n";
                $viewContent .= "\t\t\t\t\t\t    </div>\n";
                $viewContent .= "\t\t\t\t\t\t</div>\n";
            }
        }

        $viewContent .= "
                        <div class=\"row\">
                            <div class=\"col-md-12 text-end\"> 
                                <a href=\"{{ route('{$lowerCaseModelName}.index') }}\" class=\"btn btn-danger me-2\">{{ __('standard_curd_common_label.back') }}</a>
                                <button type=\"submit\" class=\"btn add-btn\">{{ __('standard_curd_common_label.update') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection";
if ($checkboxInput) {
$viewContent .="
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function updateHiddenField(fieldName) {
        const checkboxes = document.querySelectorAll('input[name=\"' +fieldName + '[]\"]:checked');
        const selectedValues = Array.from(checkboxes).map(cb => cb.value);
        document.getElementById(fieldName + '_hidden').value = selectedValues.join(',');
    }

    document.querySelectorAll('input[type=\"checkbox\"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const fieldName = checkbox.name.split('[')[0]; // Extract field name from 'name=\"field[]\"'
            updateHiddenField(fieldName);
        });
    });

    document.querySelectorAll('input[type=\"checkbox\"]').forEach(function (checkbox) {
        const fieldName = checkbox.name.split('[')[0];
        updateHiddenField(fieldName);
    });
});
</script>
@endpush";
}

            File::put($editViewFilePath, $viewContent);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate an index view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateIndexView(string $modelName, array $fields): array
    {
        $lowerCaseModelName = strtolower($modelName);
        $viewPath = resource_path("views/admin/{$lowerCaseModelName}");

        if (!is_dir($viewPath)) {
            mkdir($viewPath, 0755, true); 
        }

        $indexViewFilePath = $viewPath . "/index.blade.php";

        if (file_exists($indexViewFilePath)) {
            return ['success' => false, 'error' => 'File Already Exist'];
        }

        try {
            $viewContent = "
@extends('layouts/layout')

@section('title', __('{$lowerCaseModelName}_module.create_list_edit.list_page_title'))

@section('page-style')
    @vite([])
@endsection

@section('page-script')
    @vite([
        'resources/assets/js/{$lowerCaseModelName}.js',
    ])
@endsection

@section('content')
    <div id=\"routeData\" data-url=\"{{ route('{$lowerCaseModelName}-list') }}\"></div>
    <div class=\"content\">
        <div class=\"row\">
            <div class=\"col-md-12 page-header mb-2\">
                <div class=\"page-pretitle\">{{ __('{$lowerCaseModelName}_module.create_list_edit.{$lowerCaseModelName}') }}</div>
                <h1 class=\"page-title\">{{ __('{$lowerCaseModelName}_module.create_list_edit.list_{$lowerCaseModelName}_list') }}</h1>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"card shadow w-100\">
                <div class=\"card-header\">
                    <div class=\"btn-group-wrapper\">
                        <div class=\"export-dropdown\">
                            <button type=\"button\" class=\"btn btn-primary dropdown-toggle export-btn\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
                                {{ __('standard_curd_common_label.export')}}
                            </button>
                            <ul class=\"dropdown-menu\">
                                <li><button type=\"button\" class=\"btn btn-secondary mb-1\" id=\"csvExport\">{{ __('standard_curd_common_label.csv') }}</button></li>
                                <li><button type=\"button\" class=\"btn btn-secondary mb-1\" id=\"excelExport\">{{ __('standard_curd_common_label.excel') }}</button></li>
                                <li><button type=\"button\" class=\"btn btn-secondary mb-1\" id=\"printExport\">{{ __('standard_curd_common_label.print') }}</button></li>
                            </ul>
                        </div>
                        <a href=\"{{ route('{$lowerCaseModelName}.create') }}\" class=\"btn btn-success add-btn\">{{ __('standard_curd_common_label.add_new') }}</a>
                    </div>
                </div>

                <div class=\"card-body\">
                    <div class=\"table-responsive\">
                         <table class=\"table table-bordered yajra-datatable\">
                            <thead>
                            <tr>
                                <th>{{ __('standard_curd_common_label.id') }}</th>\n";
                                foreach($fields as $fieldName => $attributes) {
                                    if (isset($attributes['list']) && $attributes['list'] === 'on') {
                                        $label = Str::snake($attributes['name']);
                                        $viewContent .= "\t\t\t\t\t\t\t\t\t<th>{{ __('{$lowerCaseModelName}_module.field_label.{$label}') }}</th>\n";
                                    }
                                }
                                $viewContent.= "
                                <th>{{ __('standard_curd_common_label.action') }}</th>
                            </tr>
                            <tr>
                                <th><input type=\"text\" placeholder=\"{{ __('standard_curd_common_label.search') }} {{ __('standard_curd_common_label.id') }}\" class=\"column-search form-control\" /></th>\n";
                                foreach($fields as $fieldName => $attributes) {
                                    if (isset($attributes['list']) && $attributes['list'] === 'on') {
                                        $label = Str::snake($attributes['name']);
                                        $viewContent .= "\t\t\t\t\t\t\t\t\t<th><input type=\"text\" placeholder=\"{{ __('standard_curd_common_label.search') }} {{ __('{$lowerCaseModelName}_module.field_label.{$label}') }}\" class=\"column-search form-control\" /></th>\n";
                                    }
                                }
                                $viewContent.= "
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
            ";

            File::put($indexViewFilePath, $viewContent);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate JavaScript for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the JavaScript.
     * @return array
     */
    public function generateJavaScript(string $modelName, array $fields): array
    {
        $lowerCaseModelName = strtolower($modelName);
        $jsPath = resource_path("assets/js");

        $jsFilePath = $jsPath . "/{$lowerCaseModelName}.js";

        if (file_exists($jsFilePath)) {
            return ['success' => false, 'error' => 'File Already Exist'];
        }

        try {
            $viewContent = "
$(function () {
    let url = $('#routeData').data('url');
            
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns: [
            {data: 'id', name: 'id'},\n";
                        foreach($fields as $fieldName => $attributes) {
                            if (isset($attributes['list']) && $attributes['list'] === 'on') {
                                $viewContent .= "\t\t\t{data: '{$fieldName}', name: '{$fieldName}'},\n";
                            }
                        }
                        $viewContent .= "
            {data: 'action', name: 'action', 
            render: function(data, type, row) {
                let editButton = '<a href=\"/admin/{$lowerCaseModelName}/' + row.id + '/edit\" class=\"edit btn btn-success btn-sm\">Edit</a>';
                let deleteButton = '<button class=\"delete btn btn-danger btn-sm\" data-id=\"' + row.id + '\">Delete</button>';
                return editButton + ' ' + deleteButton;
            },
            orderable: false, searchable: false},
        ],
        dom: '<\"row\"<\"col-md-6\"l><\"col-md-6\"f>>t<\"row\"<\"col-md-5\"i><\"col-md-7\"p>>',
        buttons: [
            { extend: 'csv', text: 'CSV', className: 'btn btn-secondary' },
            { extend: 'excel', text: 'Excel', className: 'btn btn-secondary' },
            { extend: 'print', text: 'Print', className: 'btn btn-secondary' }
        ],
        initComplete: function () {
            var exportButton = $('.export-btn');
            var buttons = $('.dt-buttons').detach();
            exportButton.after(buttons);
        }
    });
            
    $('.column-search').on('click', function(e) {
        e.stopPropagation();
    });
            
    $('.column-search').on('keyup change', function() {
        let columnIndex = $(this).parent().index();
        table.column(columnIndex).search(this.value).draw();
    });
            
    $('.dropdown-menu').on('click', 'button', function() {
        var action = $(this).attr('id');
        switch (action) {
            case 'csvExport':
                table.button('.buttons-csv').trigger();
                break;
            case 'excelExport':
                table.button('.buttons-excel').trigger();
                break;
            case 'printExport':
                table.button('.buttons-print').trigger();
                break;
        }
    });
            
    $(document).on('click', '.delete', function () {
        var id = $(this).data('id');
        var row = $(this).closest('tr');
            
        Swal.fire({
            title: 'Are you sure?',
            text: \"You won't be able to revert this!\",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: \"POST\",
                    url: \"/admin/{$lowerCaseModelName}/\" + id,
                    data: {
                        \"_method\": \"DELETE\",
                        \"_token\": $('meta[name=\"csrf-token\"]').attr('content'),
                    },
                    success: function (response) {
                        if (response.status_code === 200) {
                            Swal.fire(
                                'Deleted!',
                                'The {$lowerCaseModelName} has been deleted.',
                                'success'
                            );
                            table.row(row).remove().draw();
                        } else {
                             Swal.fire(
                                'Error!',
                                response.message || '{$modelName} was not deleted.',
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the {$lowerCaseModelName}.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
            ";

            File::put($jsFilePath, $viewContent);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Add a menu item for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return array
     */
    public function addMenuItem(string $modelName): array
    {
        try {
            $menuFilePath = base_path('resources/assets/menu/menu.json');
            $lowerCaseModelName = strtolower($modelName);

            if (File::exists($menuFilePath)) {
                $menuData = json_decode(File::get($menuFilePath), true);
            } else {
                $menuData = ['menu' => []];
            }

            if ($menuData === null || !is_array($menuData)) {
                $menuData = ['menu' => []];
            }

            $newMenuItem = [
                "name" => "sidebar.{$lowerCaseModelName}",
                "icon" => "fas fa-list",
                "slug" => $lowerCaseModelName,
                "permission" => [$lowerCaseModelName . '.index', $lowerCaseModelName . '.create'],
                "submenu" => [
                    [
                        "url" => $lowerCaseModelName . '.index',
                        "name" => "sidebar.list",
                        "icon" => "fas fa-circle small-icon"
                    ],
                    [
                        "url" => $lowerCaseModelName . '.create',
                        "name" => "sidebar.create",
                        "icon" => "fas fa-circle small-icon"
                    ]
                ]
            ];

            $menuData['menu'][] = $newMenuItem;

            Artisan::call('route:clear');
            File::put($menuFilePath, json_encode($menuData, JSON_PRETTY_PRINT));
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Create permissions for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return array
     */
    public function createPermission(string $modelName): array
    {
        try {
            $lowerCaseModelName = strtolower($modelName);
            DB::beginTransaction();
            $permissions = [
                ['name' => $lowerCaseModelName . '.index', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '-list', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '.create', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '.store', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '.edit', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '.update', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
                ['name' => $lowerCaseModelName . '.destroy', 'guard_name' => 'web', 'group_name' => $lowerCaseModelName, 'created_at' => now(), 'updated_at' => now()],
            ];
            $this->permissiongroupRepository->create(['name' => $lowerCaseModelName]);
            
            $permissionIds = [];

            foreach ($permissions as $permissionData) {
                $permission = $this->permissionRepository->Create($permissionData);
                $permissionIds[] = $permission->id;
            }
            $role = $this->roleRepository->findFirstByConditions(['name' => 'Super Admin']);
            $role->givePermissionTo($permissionIds);

            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate a request file for creating or updating a model instance.
     *
     * @param string $modelName The name of the model.
     * @param array $validations An array of validation rules.
     * @param string $type The type of request ('Create' or 'Update').
     * @return array
     */
    public function generateRequestFile(string $modelName, array $validations = null, string $type = 'Create'): array
    {
        $className = $type . Str::studly($modelName) . 'Request';
        $requestFilePath = app_path("Http/Requests/{$className}.php");

        if (file_exists($requestFilePath)) {
            return ['success' => false, 'error' => 'Request file already exists.'];
        }

        try {
            $validationRules = '';
            if ($validations) {
                foreach ($validations as $fieldName => $rules) {
                    $rulesArray = [];
                    foreach ($rules as $ruleKey => $ruleValue) {
                        if (strpos($ruleKey, ':') !== false) {
                            $parts = explode(':', $ruleKey);
                            $newRule = $parts[0];
                            if (count($parts) > 1) {
                                $values = explode(':', $ruleValue);
                                $newRule .= ':' . array_pop($values);
                            }
                            $rulesArray[] = $newRule;
                        } else {
                            $rulesArray[] = $ruleKey;
                        }
                    }
                    $validationRules .= "'$fieldName' => '" . implode('|', $rulesArray) . "',\n\t\t\t";
                }
            }

            $requestFileContent = <<<EOT
    <?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class {$className} extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         */
        public function authorize(): bool
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<string>|string>
         */
        public function rules(): array
        {
            return [
                {$validationRules}
            ];
        }
    }
    EOT;

            File::put($requestFilePath, $requestFileContent);
            return ['success' => true, 'message' => "{$className} created successfully!"];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate an API controller for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields for the API controller.
     * @return array
     */
    public function generateApiController(string $modelName, array $fields): array
    {
        try {
            $lowerCaseModelName = strtolower($modelName);
            $controllerPath = app_path("Http/Controllers/Api/{$modelName}Controller.php");

            // Check if the controller already exists
            if (file_exists($controllerPath)) {
                return ['success' => false, 'error' => 'Controller file already exists'];
            }

            // Generate Swagger properties from the fields
            $integerTypes = [
                'bigInteger', 'mediumInteger', 'smallInteger', 'tinyInteger', 
                'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 
                'unsignedSmallInteger', 'unsignedTinyInteger', 'integer'
            ];
            
            $stringTypes = [
                'binary', 'boolean', 'char', 'dateTime', 'date', 'decimal', 'double', 
                'float', 'ipAddress', 'json', 'longText', 'macAddress', 'mediumText', 
                'string', 'text', 'time', 'tinyText', 'uuid', 'year'
            ];
            
            $fieldsList = '';
            foreach ($fields as $field) {
                $fieldName = $field['name'];
                $inputType = $field['type'];
            
                if (in_array($inputType, $integerTypes)) {
                    $swaggerType = 'integer';
                } elseif (in_array($inputType, $stringTypes)) {
                    $swaggerType = 'string';
                } else {
                    $swaggerType = 'string';
                }
                $fieldsList .= "\t *             @OA\Property(property=\"{$fieldName}\", type=\"{$swaggerType}\", example=\"example_value\"),\n";
            }

            // Start building the controller content
            $controllerContent = "<?php\n\n";
            $controllerContent .= "namespace App\Http\Controllers\Api;\n\n";
            $controllerContent .= "use App\Http\Controllers\Controller;\n";
            $controllerContent .= "use App\Services\\{$modelName}\\{$modelName}Service;\n";
            $controllerContent .= "use App\Http\Requests\\Create{$modelName}Request;\n";
            $controllerContent .= "use App\Http\Requests\\Update{$modelName}Request;\n";
            $controllerContent .= "use Symfony\Component\HttpFoundation\Response as ResponseAlias;\n";
            $controllerContent .= "use Illuminate\Http\JsonResponse;\n";
            $controllerContent .= "use Exception;\n";
            $controllerContent .= "use Illuminate\Support\Facades\DB;\n\n";

            // Add Swagger Tag
            $controllerContent .= "/**\n";
            $controllerContent .= " * @OA\Tag(\n";
            $controllerContent .= " *     name=\"{$modelName}\",\n";
            $controllerContent .= " *     description=\"{$modelName} management operations\"\n";
            $controllerContent .= " * )\n";
            $controllerContent .= " */\n";
            
            // Define the Controller class
            $controllerContent .= "class {$modelName}Controller extends Controller\n";
            $controllerContent .= "{\n";
            $controllerContent .= "    public function __construct(private {$modelName}Service \${$lowerCaseModelName}Service)\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "    }\n\n";
            
            // Index method
            $controllerContent .= "    /**\n";
            $controllerContent .= "     * @OA\Get(\n";
            $controllerContent .= "     *     path=\"/api/admin/api-{$lowerCaseModelName}\",\n";
            $controllerContent .= "     *     tags={\"{$modelName}\"},\n";
            $controllerContent .= "     *     security={{ \"bearerAuth\":{} }},\n";
            $controllerContent .= "     *     summary=\"Get all {$lowerCaseModelName}s\",\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=200,\n";
            $controllerContent .= "     *         description=\"A list of {$lowerCaseModelName}s\",\n";
            $controllerContent .= "     *     )\n";
            $controllerContent .= "     * )\n";
            $controllerContent .= "     */\n";
            $controllerContent .= "    public function index(): JsonResponse\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        try {\n";
            $controllerContent .= "            \$data = \$this->{$lowerCaseModelName}Service->findAllWithPagination([], ['*'], 10);\n";
            $controllerContent .= "            return \$this->success(\$data, 'Data retrieved successfully');\n";
            $controllerContent .= "        } catch (Exception \$e) {\n";
            $controllerContent .= "            return \$this->error('Could not retrieve {$lowerCaseModelName}s.', [], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n";
            $controllerContent .= "        }\n";
            $controllerContent .= "    }\n\n";

            // Store method
            $controllerContent .= "    /**\n";
            $controllerContent .= "     * @OA\Post(\n";
            $controllerContent .= "     *     path=\"/api/admin/api-{$lowerCaseModelName}\",\n";
            $controllerContent .= "     *     tags={\"{$modelName}\"},\n";
            $controllerContent .= "     *     security={{ \"bearerAuth\":{} }},\n";
            $controllerContent .= "     *     summary=\"Create a new {$lowerCaseModelName}\",\n";
            $controllerContent .= "     *     @OA\RequestBody(\n";
            $controllerContent .= "     *         required=true,\n";
            $controllerContent .= "     *         @OA\JsonContent(\n";
            $controllerContent .= "     *             type=\"object\",\n";
            $controllerContent .= $fieldsList;
            $controllerContent .= "     *         )\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=201,\n";
            $controllerContent .= "     *         description=\"{$modelName} created successfully\",\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=400,\n";
            $controllerContent .= "     *         description=\"Invalid input\"\n";
            $controllerContent .= "     *     )\n";
            $controllerContent .= "     * )\n";
            $controllerContent .= "     */\n";
            $controllerContent .= "    public function store(Create{$modelName}Request \$request): JsonResponse\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        DB::beginTransaction();\n";
            $controllerContent .= "        try {\n";
            $controllerContent .= "            \$data = \$this->{$lowerCaseModelName}Service->create(\$request->all());\n";
            $controllerContent .= "            DB::commit();\n";
            $controllerContent .= "            return response()->json(['success' => true, 'data' => \$data], ResponseAlias::HTTP_CREATED);\n";
            $controllerContent .= "        } catch (Exception \$e) {\n";
            $controllerContent .= "            DB::rollBack();\n";
            $controllerContent .= "            return response()->json(['success' => false, 'message' => 'Could not create {$lowerCaseModelName}.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n";
            $controllerContent .= "        }\n";
            $controllerContent .= "    }\n\n";

            // Show method
            $controllerContent .= "    /**\n";
            $controllerContent .= "     * @OA\Get(\n";
            $controllerContent .= "     *     path=\"/api/admin/api-{$lowerCaseModelName}/{id}\",\n";
            $controllerContent .= "     *     tags={\"{$modelName}\"},\n";
            $controllerContent .= "     *     security={{ \"bearerAuth\":{} }},\n";
            $controllerContent .= "     *     summary=\"Get a {$lowerCaseModelName} by ID\",\n";
            $controllerContent .= "     *     @OA\Parameter(\n";
            $controllerContent .= "     *         name=\"id\",\n";
            $controllerContent .= "     *         in=\"path\",\n";
            $controllerContent .= "     *         required=true,\n";
            $controllerContent .= "     *         @OA\Schema(type=\"integer\")\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=200,\n";
            $controllerContent .= "     *         description=\"A single {$lowerCaseModelName} object\",\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=404,\n";
            $controllerContent .= "     *         description=\"{$modelName} not found\"\n";
            $controllerContent .= "     *     )\n";
            $controllerContent .= "     * )\n";
            $controllerContent .= "     */\n";
            $controllerContent .= "    public function show(int \$id): JsonResponse\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        try {\n";
            $controllerContent .= "            \$data = \$this->{$lowerCaseModelName}Service->findById(\$id);\n";
            $controllerContent .= "            if (!\$data) {\n";
            $controllerContent .= "                return response()->json(['success' => false, 'message' => '{$modelName} not found'], ResponseAlias::HTTP_NOT_FOUND);\n";
            $controllerContent .= "            }\n";
            $controllerContent .= "            return response()->json(['success' => true, 'data' => \$data], ResponseAlias::HTTP_OK);\n";
            $controllerContent .= "        } catch (Exception \$e) {\n";
            $controllerContent .= "            return response()->json(['success' => false, 'message' => 'Could not retrieve {$lowerCaseModelName}.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n";
            $controllerContent .= "        }\n";
            $controllerContent .= "    }\n\n";

            // Update method
            $controllerContent .= "    /**\n";
            $controllerContent .= "     * @OA\Put(\n";
            $controllerContent .= "     *     path=\"/api/admin/api-{$lowerCaseModelName}/{id}\",\n";
            $controllerContent .= "     *     tags={\"{$modelName}\"},\n";
            $controllerContent .= "     *     security={{ \"bearerAuth\":{} }},\n";
            $controllerContent .= "     *     summary=\"Update an existing {$lowerCaseModelName}\",\n";
            $controllerContent .= "     *     @OA\Parameter(\n";
            $controllerContent .= "     *         name=\"id\",\n";
            $controllerContent .= "     *         in=\"path\",\n";
            $controllerContent .= "     *         required=true,\n";
            $controllerContent .= "     *         @OA\Schema(type=\"integer\")\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\RequestBody(\n";
            $controllerContent .= "     *         required=true,\n";
            $controllerContent .= "     *         @OA\JsonContent(\n";
            $controllerContent .= "     *             type=\"object\",\n";
            $controllerContent .= $fieldsList;
            $controllerContent .= "     *         )\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=200,\n";
            $controllerContent .= "     *         description=\"{$modelName} updated successfully\",\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=404,\n";
            $controllerContent .= "     *         description=\"{$modelName} not found\"\n";
            $controllerContent .= "     *     )\n";
            $controllerContent .= "     * )\n";
            $controllerContent .= "     */\n";
            $controllerContent .= "    public function update(Update{$modelName}Request \$request, int \$id): JsonResponse\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        DB::beginTransaction();\n";
            $controllerContent .= "        try {\n";
            $controllerContent .= "            \$data = \$this->{$lowerCaseModelName}Service->update(['id' => \$id], \$request->all());\n";
            $controllerContent .= "            DB::commit();\n";
            $controllerContent .= "            return response()->json(['success' => true, 'data' => \$data], ResponseAlias::HTTP_OK);\n";
            $controllerContent .= "        } catch (Exception \$e) {\n";
            $controllerContent .= "            DB::rollBack();\n";
            $controllerContent .= "            return response()->json(['success' => false, 'message' => 'Could not update {$lowerCaseModelName}.'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n";
            $controllerContent .= "        }\n";
            $controllerContent .= "    }\n\n";

            // Destroy method
            $controllerContent .= "    /**\n";
            $controllerContent .= "     * @OA\Delete(\n";
            $controllerContent .= "     *     path=\"/api/admin/api-{$lowerCaseModelName}/{id}\",\n";
            $controllerContent .= "     *     tags={\"{$modelName}\"},\n";
            $controllerContent .= "     *     security={{ \"bearerAuth\":{} }},\n";
            $controllerContent .= "     *     summary=\"Delete a {$lowerCaseModelName} by ID\",\n";
            $controllerContent .= "     *     @OA\Parameter(\n";
            $controllerContent .= "     *         name=\"id\",\n";
            $controllerContent .= "     *         in=\"path\",\n";
            $controllerContent .= "     *         required=true,\n";
            $controllerContent .= "     *         description=\"ID of the {$lowerCaseModelName} to delete\",\n";
            $controllerContent .= "     *         @OA\Schema(type=\"integer\")\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=204,\n";
            $controllerContent .= "     *         description=\"{$modelName} deleted successfully\",\n";
            $controllerContent .= "     *     ),\n";
            $controllerContent .= "     *     @OA\Response(\n";
            $controllerContent .= "     *         response=404,\n";
            $controllerContent .= "     *         description=\"{$modelName} not found\"\n";
            $controllerContent .= "     *     )\n";
            $controllerContent .= "     * )\n";
            $controllerContent .= "     */\n";
            $controllerContent .= "    public function destroy(int \$id): JsonResponse\n";
            $controllerContent .= "    {\n";
            $controllerContent .= "        DB::beginTransaction();\n";
            $controllerContent .= "        try {\n";
            $controllerContent .= "            \$data = \$this->{$lowerCaseModelName}Service->findById(\$id);\n\n";
            $controllerContent .= "             if (!\$data) {\n";
            $controllerContent .= "                 return \$this->error('Test not found.', [], ResponseAlias::HTTP_NOT_FOUND);\n";
            $controllerContent .= "             }\n\n";
            $controllerContent .= "            \$this->{$lowerCaseModelName}Service->deleteById(\$id);\n\n";
            $controllerContent .= "            DB::commit();\n";
            $controllerContent .= "            return \$this->success([], '{$modelName} deleted successfully!', ResponseAlias::HTTP_NO_CONTENT);\n";
            $controllerContent .= "        } catch (Exception \$e) {\n";
            $controllerContent .= "            DB::rollBack();\n";
            $controllerContent .= "            return \$this->error([], 'Could not delete {$lowerCaseModelName}.', ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);\n";
            $controllerContent .= "        }\n";
            $controllerContent .= "    }\n";
            $controllerContent .= "}\n";

            // Save the controller content to a file
            file_put_contents($controllerPath, $controllerContent);

            return ['success' => true, 'message' => 'Controller created successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate routes for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param bool $isApi Indicates whether the routes are for an API.
     * @return array
     */
    public function generateRoutes(string $modelName, bool $isApi = false): array
    {
        try {
            $routeFilePath = $isApi ? base_path('routes/admin_api.php') : base_path('routes/admin.php');
            $controllerNamespace = $isApi ? "App\Http\Controllers\Api\\" : "App\Http\Controllers\Admin\\";
            $controllerImport = "use " . $controllerNamespace . $modelName . "Controller;";

            $routeContent = $isApi
                ? "\n\nRoute::apiResource('api-" . strtolower($modelName) . "', " . $modelName . "Controller::class);"
                : "\n\nRoute::resource('" . strtolower($modelName) . "', " . $modelName . "Controller::class);"
                    . "\nRoute::get('" . strtolower($modelName) . "-list', [" . $modelName . "Controller::class, 'getDatatables'])->name('" . strtolower($modelName) . "-list');";

            $currentRouteFileContent = file_get_contents($routeFilePath);

            // Check if controller import exists
            if (strpos($currentRouteFileContent, $controllerImport) === false) {
                $lines = explode("\n", $currentRouteFileContent);
                $importIndex = null;

                foreach ($lines as $index => $line) {
                    if (trim($line) === 'use Illuminate\Support\Facades\Route;') {
                        $importIndex = $index;
                        break;
                    }
                }

                // Insert import statement after "use Route"
                if ($importIndex !== null) {
                    array_splice($lines, $importIndex + 1, 0, $controllerImport);
                    $updatedContent = implode("\n", $lines);
                    file_put_contents($routeFilePath, $updatedContent);
                } else {
                    // Append import if "use Route" not found
                    file_put_contents($routeFilePath, "\n" . $controllerImport, FILE_APPEND);
                }
            }

            // Append route content
            file_put_contents($routeFilePath, $routeContent, FILE_APPEND);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Generate Language for the specified Module.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields for the form and blade.
     * @return array
     */
    public function generateLanguage(string $modelName, array $fields): array
    {
        try {
            $langBasePath = base_path('lang');
            
            if (!is_dir($langBasePath)) {
                return ['success' => false, 'error' => 'Language directory does not exist.'];
            }

            $languageFolders = array_filter(glob($langBasePath . '/*'), 'is_dir');

            $languageContent = [
                'create_list_edit' => [
                    'list_page_title' => 'List ' . ucwords(str_replace('_', ' ', Str::snake($modelName))),
                    strtolower($modelName) => ucwords(str_replace('_', ' ', Str::snake($modelName))),
                    'list_' . strtolower($modelName) . '_list' => ucwords(str_replace('_', ' ', Str::snake($modelName))) . ' List',
                    'create_page_title' => 'Create ' . ucwords(str_replace('_', ' ', Str::snake($modelName))),
                    'edit_page_title' => 'Edit ' . ucwords(str_replace('_', ' ', Str::snake($modelName))),
                ],
                'field_label' => [],
            ];

            foreach ($fields as $field) {
                $fieldName = $field['name'] ?? '';
                $key = Str::snake($fieldName);
                $label = ucwords(str_replace('_', ' ', $fieldName));
                $languageContent['field_label'][$key] = $label;
            }

            foreach ($languageFolders as $folder) {
                $langFilePath = $folder . '/' . strtolower($modelName) . '_module.php';

                if (!file_exists($langFilePath)) {
                    $content = "<?php\n\nreturn " . var_export($languageContent, true) . ";\n";
                    file_put_contents($langFilePath, $content);
                }
    
                $sidebarFilePath = $folder . '/sidebar.php';
                if (file_exists($sidebarFilePath)) {
                    $sidebarContent = include $sidebarFilePath;
                    if (!is_array($sidebarContent)) {
                        $sidebarContent = [];
                    }

                    $sidebarContent[strtolower($modelName)] = ucwords(str_replace('_', ' ', $modelName));
                    
                    $updatedContent = "<?php\n\nreturn " . var_export($sidebarContent, true) . ";\n";
                    file_put_contents($sidebarFilePath, $updatedContent);
                }
            }

            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }


}

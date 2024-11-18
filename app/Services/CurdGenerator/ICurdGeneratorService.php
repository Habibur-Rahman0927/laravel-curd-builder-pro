<?php 


namespace App\Services\CurdGenerator;
use App\Services\IBaseService;

interface ICurdGeneratorService extends IBaseService
{
    /**
     * Generate a model with the specified name and options.
     *
     * @param string $modelName The name of the model.
     * @param bool $softDelete Indicates whether the model should use soft deletes.
     * @param array $fields An array of field definitions for the model.
     * @param array $relationships An array of relationships to define for the model.
     * @return array
     */
    public function generateModel(string $modelName, bool $softDelete, array $fields, array $relationships = null): array;

    /**
     * Generate a migration file for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the migration.
     * @param bool $softDelete Indicates whether to include soft delete columns.
     * @return array
     */
    public function generateMigration(string $modelName, array $fields, bool $softDelete): array;

    /**
     * Generate or bind the service and repository for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return array
     */
    public function generateOrBindServiceAndRepository(string $modelName): array;

    /**
     * Generate a create view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateCreateView(string $modelName, array $fields): array;

    /**
     * Generate an edit view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateEditView(string $modelName, array $fields): array;

    /**
     * Generate an index view for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the view.
     * @return array
     */
    public function generateIndexView(string $modelName, array $fields): array;

    /**
     * Generate JavaScript for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields to include in the JavaScript.
     * @return array
     */
    public function generateJavaScript(string $modelName, array $fields): array;

    /**
     * Add a menu item for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return array
     */
    public function addMenuItem(string $modelName): array;

    /**
     * Create permissions for the specified model.
     *
     * @param string $modelName The name of the model.
     * @return array
     */
    public function createPermission(string $modelName): array;

    /**
     * Generate a request file for creating or updating a model instance.
     *
     * @param string $modelName The name of the model.
     * @param array $validations An array of validation rules.
     * @param string $type The type of request ('Create' or 'Update').
     * @return array
     */
    public function generateRequestFile(string $modelName, array $validations = null, string $type = 'Create'): array;

    /**
     * Generate an API controller for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields for the API controller.
     * @return array
     */
    public function generateApiController(string $modelName, array $fields): array;

    /**
     * Generate routes for the specified model.
     *
     * @param string $modelName The name of the model.
     * @param bool $isApi Indicates whether the routes are for an API.
     * @return array
     */
    public function generateRoutes(string $modelName, bool $isApi = false): array;

    /**
     * Generate Language for the specified Module.
     *
     * @param string $modelName The name of the model.
     * @param array $fields An array of fields for the form and blade.
     * @return array
     */
    public function generateLanguage(string $modelName, array $fields): array;
}

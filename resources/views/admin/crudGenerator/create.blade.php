@extends('layouts/layout')

@section('title', __('generate_curd_module.create_page_title'))

@section('page-style')
    @vite([])
    <style>
        modal-content {
            background-color: #1e1e1e;
            color: #f8f8f2;
        }
    
        pre {
            background-color: #282a36;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 5px;
            overflow: auto;
            white-space: pre-wrap;
            font-family: 'Courier New', Courier, monospace;
        }
    
        .accordion-button {
            font-weight: bold;
        }
    
        .accordion-body {
            background-color: #f8f9fa;
        }
    
        .bg-light {
            background-color: #f0f8ff;
        }
        .accordion-background{
            background: linear-gradient(90deg, #3b5265, #3d5166) !important;
        }
    
        .alert {
            border-radius: 5px;
        }
    
        .table-responsive {
            overflow-y: auto; 
        }
    
        .table {
            min-width: 800px;
        }
        .applicable-placeholder {
            font-style: italic;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            text-align: center;
            width: 100%;
        }

    
    </style>
@endsection

@section('page-script')
    @vite([])
@endsection

@section('content')
    <div class="content">
            <div class="row">
                <div class="col-md-12 page-header mb-2">
                    <div class="page-pretitle">{{ __('generate_curd_module.curd') }}</div>
                    <h1 class="page-title">{{ __('generate_curd_module.create_page_title') }}</h1>
                </div>
            </div>
            <div class="row">
                    
                <form action="{{ route('crud.generator.store') }}" method="POST" id="curd-generator-form">
                    @csrf
                <div class="card shadow">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                            <!-- Table Layout for Model Configuration -->
                            <div class="table-responsive mb-4">
                                <h5 class="fw-bold mb-2">Model Configuration</h5>
                                <table class="table table-bordered table-hover text-center">
                                    <tbody>
                                        <tr>
                                            <td class="align-middle">
                                                <label for="model-name" class="form-label">Model Name</label>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="model-name" name="model_name" placeholder="Enter model name" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">
                                                <label for="model-name" class="form-label">CURD And API</label>
                                            </td>
                                            <td>
                                                <select name="use_case_type" id="use_case_type" class="form-control">
                                                    <option value="api_curd">API And CURD Generator</option>
                                                    <option value="curd">CURD Generator</option>
                                                    <option value="api">API Generator</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle">
                                                <label for="softdelete-checkbox" class="form-label">Soft Deletes</label>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <input type="checkbox" id="softdelete-checkbox" name="softdelete" value="1">
                                                    <label class="form-check-label p-2" for="softdelete-checkbox">
                                                        Enable soft deletes for this model, allowing the model records to be "deleted" without removing them from the database.
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                            <!-- Table Layout for Fields -->
                            <div class="table-responsive mb-3 p-2 border rounded shadow-sm bg-light">
                                <h5 class="fw-bold mb-2">Migration Configuration</h5>
                                <table class="table table-bordered table-hover text-center align-middle" id="fields-table">
                                    <thead>
                                        <tr>
                                            <th>Type of Method</th>
                                            <th>Field Name</th>
                                            <th>Length</th>
                                            <th>Nullable</th>
                                            <th>Unique</th>
                                            <th>Index</th>
                                            <th>Unsigned</th>
                                            <th>Default</th>
                                            <th>Comment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="field-container">
                                        <tr class="field-row">
                                            <td>
                                                <select name="fields[0][type]" class="form-select" required>
                                                    @foreach ([ 'string' => 'string()',
                                                                'integer' => 'integer()',
                                                                'decimal' => 'decimal()',
                                                                'uuid' => 'uuid()',
                                                                'ulid' => 'ulid()',
                                                                'bigInteger' => 'bigInteger()',
                                                                'boolean' => 'boolean()',
                                                                'dateTime' => 'dateTime()',
                                                                'date' => 'date()',
                                                                'json' => 'json()',
                                                                'longText' => 'longText()',
                                                                'text' => 'text()',
                                                                'time' => 'time()',
                                                                'binary' => 'binary()',
                                                                'char' => 'char()',
                                                                'double' => 'double()',
                                                                'float' => 'float()',
                                                                'ipAddress' => 'ipAddress()',
                                                                'macAddress' => 'macAddress()',
                                                                'mediumInteger' => 'mediumInteger()',
                                                                'mediumText' => 'mediumText()',
                                                                'smallInteger' => 'smallInteger()',
                                                                'tinyInteger' => 'tinyInteger()',
                                                                'tinyText' => 'tinyText()',
                                                                'unsignedBigInteger' => 'unsignedBigInteger()',
                                                                'unsignedInteger' => 'unsignedInteger()',
                                                                'unsignedMediumInteger' => 'unsignedMediumInteger()',
                                                                'unsignedSmallInteger' => 'unsignedSmallInteger()',
                                                                'unsignedTinyInteger' => 'unsignedTinyInteger()',
                                                                'year' => 'year()',
                                                                'foreignId' => 'foreignId()',
                                                                'foreignIdFor' => 'foreignIdFor()', ] as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control field-name-input" name="fields[0][name]" placeholder="Field name" required></td>
                                            <td><input type="number" class="form-control" name="fields[0][length]" placeholder="Length"></td>
                                            <td><input type="checkbox" name="fields[0][nullable]" value="nullable"></td>
                                            <td><input type="checkbox" name="fields[0][unique]" value="unique"></td>
                                            <td><input type="checkbox" name="fields[0][index]" value="index"></td>
                                            <td><input type="checkbox" name="fields[0][unsigned]" value="unsigned"></td>
                                            <td><input type="text" class="form-control" name="fields[0][default]" placeholder="Default value"></td>
                                            <td><input type="text" class="form-control" name="fields[0][comment]" placeholder="Comment"></td>
                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-field" title="Remove Field">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <button type="button" class="btn add-btn" id="add-field">➕ {{ __('generate_curd_module.add_field') }}</button>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                            <!-- Table Layout for Relationships -->
                            <div class="table-responsive mb-3 p-4 border rounded shadow-sm bg-light">
                                <h5 class="fw-bold mb-3">🔗 Model Relationships</h5>
                                <table class="table table-bordered table-hover text-center align-middle" id="relationships-table" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Relationship Type</th>
                                            <th>Related Model</th>
                                            <th>Foreign Key</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="relationship-container">
                                        <tr class="relationship-row">
                                            <td>
                                                <select name="relationships[0][type]" class="form-select form-select-sm" required>
                                                    @foreach (['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'] as $relation)
                                                        <option value="{{ $relation }}">{{ ucfirst($relation) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="relationships[0][related_model]" class="form-select form-select-sm related-model" required>
                                                    @foreach ($modelNames as $modelName)
                                                        <option value="{{ $modelName }}">{{ $modelName }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="relationships[0][foreign_key]" placeholder="Foreign Key" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-relationship" title="Remove Relationship">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
        
                            <div>
                                <button type="button" class="btn add-btn" id="add-relationship">➕ {{ __('generate_curd_module.add_relationship') }}</button>
                                <button type="button" class="btn btn-warning" id="preview-button">{{ __('generate_curd_module.preview') }}</button>
                            </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                            <div class="accordion mt-4 mb-3" id="accordionCrudSection">
                                <div class="accordion-item border-0">
                                    <h1 class="accordion-header" id="headingOne">
                                        <button class="accordion-button bg-primary text-white accordion-background" type="button" id="guideline-modal-button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            View Configuration
                                        </button>
                                    </h1>
                                    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionCrudSection">
                                        <div class="accordion-body">
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div class="bg-light p-3 rounded mb-3">
                                                        <h6><strong>📝 Field Selection Guidelines</strong></h6>
                                                        <p>
                                                            Configure fields for:
                                                        </p>
                                                        <ul>
                                                            <li><strong>Create:</strong> 
                                                                <span class="text-muted">If selected, this field will appear in the form when creating a new record.</span>
                                                            </li>
                                                            <li><strong>Edit:</strong> 
                                                                <span class="text-muted">If selected, this field will be available when editing existing records.</span>
                                                            </li>
                                                            <li><strong>List:</strong> 
                                                                <span class="text-muted">If selected, this field will appear in the list view (table display) of the records.</span>
                                                            </li>
                                                            <li><strong>Field:</strong> 
                                                                <span class="text-muted">Your field is <code>first_name</code> but it will appear in the form as <strong>First Name</strong>.</span>
                                                            </li>                                                    
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="alert alert-warning">
                                                            <h6><strong>⚠️ Important:</strong></h6>
                                                            <p>Choose only necessary fields to avoid clutter.</p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="alert alert-info">
                                                            <h6><strong>🔍 Example:</strong></h6>
                                                            <p>
                                                                For a field like <code>name</code>, select options to include it in creation, editing, and listing.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                            
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="field-selection-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Field Name</th>
                                                            <th>Select All</th>
                                                            <th>Create</th>
                                                            <th>Edit</th>
                                                            <th>List</th>
                                                            <th>Input Type</th>
                                                            <th>Model/Other Options</th>
                                                            <th>Validation</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Rows will be added here dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="accordion mt-4 mb-3 d-none" id="accordionApiSection">
                                <div class="accordion-item border-0">
                                    <h1 class="accordion-header" id="headingApi">
                                        <button class="accordion-button bg-primary text-white accordion-background" type="button" id="guideline-modal-button-api" data-bs-toggle="collapse" data-bs-target="#collapseApi" aria-expanded="true" aria-controls="collapseApi">
                                            API Validation Configuration
                                        </button>
                                    </h1>
                                    <div id="collapseApi" class="accordion-collapse collapse" aria-labelledby="headingApi" data-bs-parent="#accordionApiSection">
                                        <div class="accordion-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="field-selection-api-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Field Name</th>
                                                            <th>Validation</th>
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
        
                            <!-- Submit Button -->
                            <div class="form-group text-end">
                                <button type="reset" class="btn btn-danger text-white">{{ __('standard_curd_common_label.reset') }}</button> 
                                <button type="submit" class="btn add-btn">{{ __('generate_curd_module.generate') }}</button>
                            </div>
                    </div>
                    
                        <div class="modal fade" id="preview-modal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="previewModalLabel">{{ __('generate_curd_module.preview') }} {{ __('generate_curd_module.create_page_title') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <pre id="preview-content"></pre> <!-- Display preview content here -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('standard_curd_common_label.close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </div>

@endsection
@push('scripts')
<script>
    let fieldIndex = 1;
    let relationshipIndex = 1;

    document.getElementById('model-name').addEventListener('input', function(event) {
        let input = event.target;
        input.value = input.value
            .replace(/\s+/g, '')
            .replace(/(^\w|[A-Z]|\b\w)/g, (match, index) => 
                index === 0 ? match.toLowerCase() : match.toUpperCase()
            )
            .replace(/[^a-zA-Z0-9]/g, '');
    });

    // Model names available for relationships
    const modelNames = @json($modelNames); // Use Laravel's json helper to pass PHP data to JS

    // Add new field row
    document.getElementById('add-field').addEventListener('click', function() {
        const container = document.getElementById('field-container');
        const row = document.createElement('tr');
        row.classList.add('field-row');
        row.innerHTML = `
                <td>
                    <select name="fields[${fieldIndex}][type]" class="form-select" required>
                        @foreach ([ 'string' => 'string()',
                                    'decimal' => 'decimal()',
                                    'integer' => 'integer()',
                                    'uuid' => 'uuid()',
                                    'ulid' => 'ulid()',
                                    'bigInteger' => 'bigInteger()',
                                    'boolean' => 'boolean()',
                                    'dateTime' => 'dateTime()',
                                    'date' => 'date()',
                                    'json' => 'json()',
                                    'longText' => 'longText()',
                                    'text' => 'text()',
                                    'time' => 'time()',
                                    'binary' => 'binary()',
                                    'char' => 'char()',
                                    'double' => 'double()',
                                    'float' => 'float()',
                                    'ipAddress' => 'ipAddress()',
                                    'macAddress' => 'macAddress()',
                                    'mediumInteger' => 'mediumInteger()',
                                    'mediumText' => 'mediumText()',
                                    'smallInteger' => 'smallInteger()',
                                    'tinyInteger' => 'tinyInteger()',
                                    'tinyText' => 'tinyText()',
                                    'unsignedBigInteger' => 'unsignedBigInteger()',
                                    'unsignedInteger' => 'unsignedInteger()',
                                    'unsignedMediumInteger' => 'unsignedMediumInteger()',
                                    'unsignedSmallInteger' => 'unsignedSmallInteger()',
                                    'unsignedTinyInteger' => 'unsignedTinyInteger()',
                                    'year' => 'year()',
                                    'foreignId' => 'foreignId()',
                                    'foreignIdFor' => 'foreignIdFor()', ] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" class="form-control field-name-input" name="fields[${fieldIndex}][name]" placeholder="Field name" required></td>
                <td><input type="number" class="form-control" name="fields[${fieldIndex}][length]" placeholder="Length"></td>
                <td><input type="checkbox" name="fields[${fieldIndex}][nullable]" value="nullable"></td>
                <td><input type="checkbox" name="fields[${fieldIndex}][unique]" value="unique"></td>
                <td><input type="checkbox" name="fields[${fieldIndex}][index]" value="index"></td>
                <td><input type="checkbox" name="fields[${fieldIndex}][unsigned]" value="unsigned"></td>
                <td><input type="text" class="form-control" name="fields[${fieldIndex}][default]" placeholder="Default value"></td>
                <td><input type="text" class="form-control" name="fields[${fieldIndex}][comment]" placeholder="Comment"></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-field" title="Remove Field">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
        `;
        container.appendChild(row);
        row.querySelector('.remove-field').addEventListener('click', function() {
            row.remove();
        });

        const selectElement = container.lastElementChild.querySelector('select[name*="[type]"]');
        selectElement.addEventListener('change', function() {
            toggleDataTypes(selectElement);
            curdViewForm();
            apiValidation();
        });

        container.lastElementChild.querySelectorAll('select[name*="[type]"]').forEach(selectElement => {
            toggleDataTypes(selectElement);
            curdViewForm();
            apiValidation();
        });
        
        const newFieldNameInput = container.lastElementChild.querySelector('.field-name-input');
        newFieldNameInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/\s+/g, '_');
            curdViewForm();
            apiValidation();
        });
        fieldIndex++;
    });

    function toggleDataTypes(selectElement) {
        const fieldRowElement = selectElement.closest('.field-row');

        if (fieldRowElement) {
            const nullableCheckbox = fieldRowElement.querySelector('input[name*="[nullable]"]');
            const uniqueCheckbox = fieldRowElement.querySelector('input[name*="[unique]"]');
            const indexCheckbox = fieldRowElement.querySelector('input[name*="[index]"]');
            const unsignedCheckbox = fieldRowElement.querySelector('input[name*="[unsigned]"]');
            const fieldNameInput = fieldRowElement.querySelector('input[name*="[name]"]');
            const lengthInput = fieldRowElement.querySelector('input[name*="[length]"]');
            const defaultInput = fieldRowElement.querySelector('input[name*="[default]"]');
            const commentInput = fieldRowElement.querySelector('input[name*="[comment]"]');

            const allowedUnsignedTypes = ['integer', 'tinyInteger', 'mediumInteger', 'bigInteger', 'smallInteger', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'float', 'double', 'decimal'];
            const noLengthRequiredTypes = ['integer', 'tinyInteger', 'mediumInteger', 'bigInteger', 'smallInteger', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'binary', 'boolean', 'text', 'date', 'dateTime', 'time', 'json', 'uuid', 'ulid', 'foreignId', 'foreignIdFor', 'mediumText', 'longText', 'year'];
            const noDefualtRequiredTypes = ['text', 'mediumText', 'longText', 'tinyText', 'binary', 'json'];
            const defualtNumberTypeInput = ['integer', 'bigInteger', 'mediumInteger', 'mediumInteger', 'smallInteger', 'tinyInteger', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'foreignId', 'foreignIdFor'];
            const noUniqueRequiredTypes = ['boolean', 'binary', 'text', 'mediumText', 'longText', 'tinyText', 'json', 'foreignId', 'foreignIdFor'];
            const noIndexRequiredTypes = ['boolean', 'binary', 'text', 'mediumText', 'longText', 'tinyText', 'json'];
            const floatTypes = ['float', 'double', 'decimal'];
            const noNullableTypes = [];

            // Reset all inputs before applying new settings
            if (nullableCheckbox) {
                nullableCheckbox.checked = false;
                nullableCheckbox.disabled = false;
            }
            if (unsignedCheckbox) {
                unsignedCheckbox.checked = false;
                unsignedCheckbox.disabled = false;
            }
            if (uniqueCheckbox) {
                uniqueCheckbox.checked = false;
                uniqueCheckbox.disabled = false;
            }
            if (indexCheckbox) {
                indexCheckbox.checked = false;
                indexCheckbox.disabled = false;
            }
            if (lengthInput) {
                lengthInput.value = '';
                lengthInput.disabled = false;
            }
            if (defaultInput) {
                defaultInput.value = '';
                defaultInput.type = 'text';
                defaultInput.placeholder = 'Default value';
            }
            if (commentInput) {
                commentInput.value = '';
                commentInput.type = 'text';
                commentInput.placeholder = 'Comment';
            }

            if (nullableCheckbox) {
                nullableCheckbox.disabled = noNullableTypes.includes(selectElement.value);
            }

            if (uniqueCheckbox) {
                uniqueCheckbox.disabled = noUniqueRequiredTypes.includes(selectElement.value);
            }

            if (indexCheckbox) {
                indexCheckbox.disabled = noIndexRequiredTypes.includes(selectElement.value);
            }

            if (unsignedCheckbox) {
                unsignedCheckbox.disabled = !allowedUnsignedTypes.includes(selectElement.value);
                if (!unsignedCheckbox.disabled) {
                    unsignedCheckbox.checked = false;
                }
            }

            if (fieldNameInput) {
                if (selectElement.value === 'foreignId') {
                    fieldNameInput.style.display = 'block';
                    fieldNameInput.readOnly = false;
                    fieldNameInput.value = '';
                    fieldNameInput.placeholder = 'Demo: user_id,users';
                    let modelSelect = fieldRowElement.querySelector('select[name="modelSelect"]');
                    if (modelSelect) {
                        modelSelect.remove();
                    }
                } else if (selectElement.value === 'foreignIdFor') {
                    fieldNameInput.style.display = 'none';
                    fieldNameInput.type = 'text';
                    fieldNameInput.readOnly = true;
                    let modelSelect = fieldRowElement.querySelector('select[name="modelSelect"]');
                    if (!modelSelect) {
                        modelSelect = document.createElement('select');
                        modelSelect.name = 'modelSelect';
                        modelSelect.classList.add('form-control');
                        modelSelect.style.display = 'inline-block';
                        modelSelect.style.width = '100%';
                        modelSelect.style.marginTop = '5px';

                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = ' -- Select a model -- ';
                        defaultOption.disabled = true;
                        defaultOption.selected = true;
                        modelSelect.appendChild(defaultOption);

                        modelNames.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model;
                            option.textContent = model;
                            modelSelect.appendChild(option);
                        });

                        fieldNameInput.insertAdjacentElement('afterend', modelSelect);

                        modelSelect.addEventListener('change', function () {
                            fieldNameInput.value = modelSelect.value;
                        });
                    }
                } else {
                    fieldNameInput.style.display = 'block';
                    fieldNameInput.placeholder = 'Field name';
                    fieldNameInput.readOnly = false;
                    fieldNameInput.value = '';
                    let modelSelect = fieldRowElement.querySelector('select[name="modelSelect"]');
                    if (modelSelect) {
                        modelSelect.remove();
                    }
                }
            }

            if (lengthInput) {
                lengthInput.disabled = noLengthRequiredTypes.includes(selectElement.value);
                if (lengthInput.disabled) {
                    lengthInput.value = '';
                }
                if (floatTypes.includes(selectElement.value)) {
                    lengthInput.style.display = 'none';
                    lengthInput.type = 'text';
                    lengthInput.readOnly = true;
                    let precisionInput = fieldRowElement.querySelector('input[name="precision"]');
                    if (!precisionInput) {
                        precisionInput = document.createElement('input');
                        precisionInput.type = 'number';
                        precisionInput.name = 'precision';
                        precisionInput.placeholder = 'Precision (1-24)';
                        precisionInput.min = 1;
                        precisionInput.max = 24;
                        precisionInput.classList.add('form-control');
                        precisionInput.style.width = '48%';
                        precisionInput.style.display = 'inline-block';
                        precisionInput.style.marginRight = '5px';
                        lengthInput.insertAdjacentElement('afterend', precisionInput);
                    }

                    let scaleInput = fieldRowElement.querySelector('input[name="scale"]');
                    if (!scaleInput) {
                        scaleInput = document.createElement('input');
                        scaleInput.type = 'number';
                        scaleInput.name = 'scale';
                        scaleInput.placeholder = 'Scale (0-10)';
                        scaleInput.min = 0;
                        scaleInput.max = 10;
                        scaleInput.classList.add('form-control');
                        scaleInput.style.width = '49%';
                        scaleInput.style.display = 'inline-block';
                        precisionInput.insertAdjacentElement('afterend', scaleInput);
                    }
                    const updateLengthInput = function () {
                        const precisionValue = precisionInput.value;
                        const scaleValue = scaleInput.value;

                        if (precisionValue && scaleValue) {
                            lengthInput.value = `${precisionValue}, ${scaleValue}`;
                        } else {
                            lengthInput.value = '';
                        }
                    };

                    precisionInput.addEventListener('input', updateLengthInput);
                    scaleInput.addEventListener('input', updateLengthInput);
                    precisionInput.value = '';
                    scaleInput.value = '';
                } else {
                    let precisionInput = fieldRowElement.querySelector('input[name="precision"]');
                    let scaleInput = fieldRowElement.querySelector('input[name="scale"]');
                    if (precisionInput) {
                        precisionInput.remove();
                    }
                    if (scaleInput) {
                        scaleInput.remove();
                    }
                    lengthInput.style.display = 'block';
                    lengthInput.type = 'number';
                    lengthInput.readOnly = false;
                    lengthInput.placeholder = 'Length';
                    lengthInput.value = '';
                    lengthInput.min = 0;
                }
            }

            if (defaultInput) {
                if (selectElement.value === 'boolean') {
                    defaultInput.type = 'number';
                    defaultInput.placeholder = 'Enter 1 (True) or 0 (False)';
                    defaultInput.value = '';
                    defaultInput.min = 0;
                    defaultInput.max = 1;
                } else if (defualtNumberTypeInput.includes(selectElement.value)) {
                    defaultInput.type = 'number';
                    defaultInput.placeholder = 'Default value';
                    defaultInput.value = '';
                    defaultInput.min = 0;
                } else if (selectElement.value === 'date') {
                    defaultInput.type = 'date';
                    defaultInput.placeholder = 'Select a date';
                    defaultInput.value = '';
                    defaultInput.min = '';
                } else if (selectElement.value === 'dateTime') {
                    defaultInput.type = 'datetime-local';
                    defaultInput.placeholder = 'Select a date time';
                    defaultInput.value = '';
                    defaultInput.min = '';
                } else if (selectElement.value === 'time') {
                    defaultInput.type = 'time';
                    defaultInput.placeholder = 'Select a time';
                    defaultInput.value = '';
                    defaultInput.min = '';
                } else if (selectElement.value === 'year') {
                    defaultInput.type = 'number';
                    defaultInput.placeholder = 'Please Enter 4 digit year';
                    defaultInput.min = 1900;
                    defaultInput.max = 2100;
                    defaultInput.value = '';
                } else if (floatTypes.includes(selectElement.value)) {
                    defaultInput.type = 'number';
                    defaultInput.placeholder = 'Default value';
                    defaultInput.value = '';
                    defaultInput.min = 0;
                } else {
                    defaultInput.type = 'text';
                    defaultInput.placeholder = 'Default value';
                    defaultInput.value = '';
                    defaultInput.min = '';
                    defaultInput.max = '';
                }

                if (noDefualtRequiredTypes.includes(selectElement.value)) {
                    defaultInput.disabled = true;
                    defaultInput.value = '';
                } else {
                    defaultInput.disabled = false;
                }
            }
        }
    }

    document.querySelectorAll('.field-name-input').forEach(function(input) {
        input.addEventListener('input', function() {
            this.value = this.value.toLowerCase().replace(/\s+/g, '_');
        });
    });


    document.querySelectorAll('select[name*="[type]"]').forEach(selectElement => {
        selectElement.addEventListener('change', function() {
            toggleDataTypes(selectElement);
            curdViewForm();
            apiValidation();
        });
        toggleDataTypes(selectElement);
        curdViewForm();
        apiValidation();
    });

    // Add new relationship row
    document.getElementById('add-relationship').addEventListener('click', function() {
        const container = document.getElementById('relationship-container');
        const row = document.createElement('tr');
        row.classList.add('relationship-row');
        row.innerHTML = `
            <td>
                <select name="relationships[${relationshipIndex}][type]" class="form-select form-select-sm" required>
                    @foreach (['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'] as $relation)
                        <option value="{{ $relation }}">{{ ucfirst($relation) }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="relationships[${relationshipIndex}][related_model]" class="form-select form-select-sm related-model" required>
                    @foreach ($modelNames as $modelName)
                        <option value="{{ $modelName }}">{{ $modelName }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" name="relationships[${relationshipIndex}][foreign_key]" placeholder="Foreign Key" required>
            </td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm remove-relationship" title="Remove Relationship">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        row.querySelector('.remove-relationship').addEventListener('click', function() {
            row.remove();
        });
        container.appendChild(row);
        relationshipIndex++;
    });

    // Event delegation for removing relationship rows
    document.querySelector('.remove-relationship').addEventListener('click', function() {
        this.closest('.relationship-row').remove();
    });

    // Event delegation for removing field rows
    document.querySelector('.remove-field').addEventListener('click', function(e) {
        this.closest('.field-row').remove();
    });

    document.getElementById('preview-button').addEventListener('click', function () {
        const modelName = document.getElementById('model-name').value;
        const softDeleteEnabled = document.getElementById('softdelete-checkbox').checked;
        const fields = [];
        const relationships = [];

        // Get fields data
        const fieldRows = document.querySelectorAll('#field-container .field-row');
        fieldRows.forEach(function (row) {
            const fieldName = row.querySelector('input[name^="fields"][name$="[name]"]').value;
            const fieldType = row.querySelector('select[name^="fields"][name$="[type]"]').value;
            const fieldLength = row.querySelector('input[name^="fields"][name$="[length]"]').value;
            const fieldNullable = row.querySelector('input[name^="fields"][name$="[nullable]"]').checked;
            const fieldUnique = row.querySelector('input[name^="fields"][name$="[unique]"]').checked;
            const fieldIndex = row.querySelector('input[name^="fields"][name$="[index]"]').checked;
            const fieldUnsigned = row.querySelector('input[name^="fields"][name$="[unsigned]"]').checked;
            const fieldDefault = row.querySelector('input[name^="fields"][name$="[default]"]').value;
            const fieldComment = row.querySelector('input[name^="fields"][name$="[comment]"]').value;

            fields.push({
                name: fieldName,
                type: fieldType,
                length: fieldLength,
                nullable: fieldNullable,
                unique: fieldUnique,
                index: fieldIndex,
                unsigned: fieldUnsigned,
                default: fieldDefault,
                comment: fieldComment
            });
        });

        const relationshipRows = document.querySelectorAll('#relationship-container .relationship-row');
        relationshipRows.forEach(function (row) {
            const relationshipType = row.querySelector('select[name^="relationships"][name$="[type]"]').value;
            const relatedModel = row.querySelector('select[name^="relationships"][name$="[related_model]"]').value;
            const foreignKey = row.querySelector('input[name^="relationships"][name$="[foreign_key]"]').value;

            relationships.push({ relationshipType, relatedModel, foreignKey });
        });

        let migrationContent = `<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    public function up(): void\n    {\n        Schema::create('${modelName.toLowerCase()}${modelName ? 's' : ''}', function (Blueprint $table) {\n            $table->id();\n`;

            fields.forEach(field => {
                let fieldLine = '';
                if (field.name) {
                    let fieldName = field.name;
                    let tableName = '';
                    if (['foreignId'].includes(field.type)) {
                        fieldName = field.name.split(',')[0];
                        tableName = field.name.split(',')[1];
                    }
                    if (['foreignIdFor'].includes(field.type)) {
                        fieldLine += `$table->${field.type}(\\App\\Models\\${fieldName}::class)`;
                    } else {
                        fieldLine += `            $table->${field.type}('${fieldName}'${field.length ? `, ${field.length}` : ''})`;
                    }

                    if (['integer', 'tinyInteger', 'mediumInteger', 'bigInteger', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'float', 'double', 'decimal'].includes(field.type) && field.unsigned) {
                        fieldLine += '->unsigned()';
                    }

                    if (field.nullable) fieldLine += '->nullable()';
                    if (field.default) {
                        if (field.type === 'boolean' || ['integer', 'tinyInteger', 'mediumInteger', 'bigInteger', 'smallInteger', 'unsignedBigInteger', 'unsignedInteger', 'unsignedMediumInteger', 'unsignedSmallInteger', 'unsignedTinyInteger', 'float', 'double', 'decimal', 'foreignId', 'foreignIdFor'].includes(field.type)) {
                            fieldLine += `->default(${field.default})`;
                        } else {
                            fieldLine += `->default('${field.default}')`;
                        }
                    }
                    if (['foreignId'].includes(field.type)) {
                        fieldLine += `->constrained('${tableName}')->cascadeOnDelete()->cascadeOnUpdate()`;
                    }
                    if (['foreignIdFor'].includes(field.type)) {
                        fieldLine += `->constrained()->cascadeOnDelete()->cascadeOnUpdate()`;
                    }
                    if (field.unique) fieldLine += '->unique()';
                    if (field.index) fieldLine += '->index()';
                    if (field.comment) fieldLine += `->comment('${field.comment}')`;
                }
                
                migrationContent += `${fieldLine}${field.name ? ';' : ''}\n`;
            });
            if (softDeleteEnabled) {
                migrationContent += `            $table->softDeletes();\n`;
            }

        migrationContent += `            $table->timestamps();\n        });\n    }\n\n    public function down(): void\n    {\n        Schema::dropIfExists('${modelName.toLowerCase()}${modelName ? 's' : ''}');\n    }\n};\n`;

        let modelContent = `<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\n`;
        let importStatements = new Set();
        if (softDeleteEnabled) {
            importStatements.add('use Illuminate\\Database\\Eloquent\\SoftDeletes;');
        }
        relationships.forEach(relationship => {
            if (relationship.relationshipType === 'belongsTo') {
                importStatements.add('use Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;');
            } else if (relationship.relationshipType === 'hasMany') {
                importStatements.add('use Illuminate\\Database\\Eloquent\\Relations\\HasMany;');
            } else if (relationship.relationshipType === 'hasOne') {
                importStatements.add('use Illuminate\\Database\\Eloquent\\Relations\\HasOne;');
            } else if (relationship.relationshipType === 'belongsToMany') {
                importStatements.add('use Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany;');
            }
        });

        importStatements.forEach(importStatement => {
            modelContent += importStatement + '\n';
        });

        modelContent += `\nclass ${modelName} extends Model\n{\n    use HasFactory${softDeleteEnabled ? ', SoftDeletes' : ''};\n\n    protected $fillable = [\n`;

        const fillableFields = fields.map(field => {
            let fieldName = field.name;

            if (['foreignIdFor'].includes(field.type)) {
                fieldName = fieldName.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase() + '_id';
            }

            if (['foreignId'].includes(field.type)) {
                fieldName = field.name.split(',')[0];
            }

            return `'${fieldName}'`;
        }).join(',\n\t');
        modelContent += `        ${fillableFields}\n    ];\n\n`;

        relationships.forEach(relationship => {
            const relatedModelLowerCase = relationship.relatedModel.toLowerCase();
            
            if (relationship.relationshipType === 'belongsTo') {
                modelContent += `    public function ${relatedModelLowerCase}(): BelongsTo\n    {\n        return $this->belongsTo(${relationship.relatedModel}::class, '${relationship.foreignKey}');\n    }\n\n`;
            } else if (relationship.relationshipType === 'hasMany') {
                modelContent += `    public function ${relatedModelLowerCase}s(): HasMany\n    {\n        return $this->hasMany(${relationship.relatedModel}::class, '${relationship.foreignKey}');\n    }\n\n`;
            } else if (relationship.relationshipType === 'hasOne') {
                modelContent += `    public function ${relatedModelLowerCase}(): HasOne\n    {\n        return $this->hasOne(${relationship.relatedModel}::class, '${relationship.foreignKey}');\n    }\n\n`;
            } else if (relationship.relationshipType === 'belongsToMany') {
                modelContent += `    public function ${relatedModelLowerCase}s(): BelongsToMany\n    {\n        return $this->belongsToMany(${relationship.relatedModel}::class);\n    }\n\n`;
            } 
        });

        modelContent += `};\n`;

        const previewContent = `Migration:\n\n${migrationContent}\nModel:\n\n${modelContent}`;

        document.getElementById('preview-content').textContent = previewContent;
        $('#preview-modal').modal('show'); // Keeping this jQuery call as the modal might still be using Bootstrap's jQuery methods
    });

    document.getElementById('guideline-modal-button').addEventListener('click', function () {
        curdViewForm();
    });

    function curdViewForm() {
        const fieldContainer = document.querySelectorAll('#field-container .field-row');
        const tableBody = document.querySelector('#field-selection-table tbody');

        tableBody.innerHTML = '';

        fieldContainer.forEach(function (row) {
            const fieldType = row.querySelector('select[name^="fields"][name$="[type]"]').value;
            let fieldName = row.querySelector('input[name^="fields"][name$="[name]"]').value;
            if (['foreignId'].includes(fieldType)) {
                fieldName = fieldName.split(',')[0];
            }
            if (['foreignIdFor'].includes(fieldType)) {
                fieldName = fieldName.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase() + '_id';
            }
            if (fieldName) {
                const tr = document.createElement('tr');
                
                const tdFieldName = document.createElement('td');
                const fieldContainer = document.createElement('div');
                fieldContainer.style.display = 'flex';
                fieldContainer.style.alignItems = 'center';
                fieldContainer.style.gap = '8px';

                const editCheckbox = document.createElement('input');
                editCheckbox.type = 'checkbox';
                editCheckbox.addEventListener('change', function() {
                    fieldNameInput.readOnly = !editCheckbox.checked;
                });
                fieldContainer.appendChild(editCheckbox);

                const fieldNameInput = document.createElement('input');
                fieldNameInput.type = 'text';
                fieldNameInput.value = fieldName;
                fieldNameInput.name = `fieldNames[${fieldName}][name]`;
                fieldNameInput.classList.add('form-control');
                fieldNameInput.readOnly = true;
                fieldNameInput.classList.add('field-name-input');
                fieldContainer.appendChild(fieldNameInput);

                tdFieldName.appendChild(fieldContainer);
                tr.appendChild(tdFieldName);


                // Create the "Select All" checkbox
                const tdSelectAll = document.createElement('td');
                const selectAllCheckbox = document.createElement('input');
                selectAllCheckbox.type = 'checkbox';
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = tr.querySelectorAll('input[type="checkbox"].action-checkbox');
                    checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
                    manageInputTypeState(checkboxes, inputTypeSelect, checkboxValidation);
                    checkboxValidation.dispatchEvent(new Event('change'));
                });
                tdSelectAll.appendChild(selectAllCheckbox);
                tr.appendChild(tdSelectAll);

                // Create checkboxes for Create, Edit, List
                ['create', 'edit', 'list'].forEach(action => {
                    const td = document.createElement('td');
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = `fieldNames[${fieldName}][${action}]`;
                    checkbox.classList.add('action-checkbox');
                    td.appendChild(checkbox);
                    tr.appendChild(td);

                    // Add event listener to enable/disable input type and validation based on checkbox states
                    checkbox.addEventListener('change', function() {
                        const checkboxes = tr.querySelectorAll('input[type="checkbox"].action-checkbox');
                        manageInputTypeState(checkboxes, inputTypeSelect, checkboxValidation);
                        checkboxValidation.dispatchEvent(new Event('change'));
                    });
                });

                // Create the HTML Input Type select dropdown
                const tdInputType = document.createElement('td');
                const inputTypeSelect = document.createElement('select');
                inputTypeSelect.classList.add('form-control');
                inputTypeSelect.name = `fieldNames[${fieldName}][input_type]`;

                // List of input types
                const inputTypes = ['text', 'email', 'number', 'date', 'password', 'select', 'checkbox', 'radio', 'textarea'];
                inputTypes.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type.charAt(0).toUpperCase() + type.slice(1);
                    inputTypeSelect.appendChild(option);
                });

                // Initially disable input type
                inputTypeSelect.disabled = true;
                tdInputType.appendChild(inputTypeSelect);
                tr.appendChild(tdInputType);

                const tdModelAndOtherOptions = document.createElement('td');

                // Create the select dropdown for models
                const modelSelect = document.createElement('select');
                modelSelect.classList.add('form-control');
                modelSelect.name = `fieldNames[${fieldName}][model_name]`;
                modelSelect.style.display = 'none';
                modelSelect.disabled = true;

                modelNames.forEach(model => {
                    const option = document.createElement('option');
                    option.value = model;
                    option.textContent = model;
                    modelSelect.appendChild(option);
                });
                tdModelAndOtherOptions.appendChild(modelSelect);

                // Create a placeholder span for "Not Applicable"
                const placeholder = document.createElement('span');
                placeholder.textContent = 'Not Applicable';
                placeholder.style.display = 'block';
                placeholder.classList.add('applicable-placeholder');
                tdModelAndOtherOptions.appendChild(placeholder);

                const extraInput = document.createElement('input');
                extraInput.type = 'text';
                extraInput.name = `fieldNames[${fieldName}][extra_values]`;
                extraInput.placeholder = 'Comma-separated values : option1,option2';
                extraInput.classList.add('form-control');
                extraInput.style.display = 'none';
                extraInput.disabled = true;
                tdModelAndOtherOptions.appendChild(extraInput);

                // Add logic to toggle between dropdown and placeholder
                inputTypeSelect.addEventListener('change', function () {
                    if (inputTypeSelect.value === 'select') {
                        modelSelect.style.display = 'block';
                        modelSelect.disabled = false;
                        placeholder.style.display = 'none';
                        extraInput.style.display = 'none';
                        extraInput.disabled = true;
                    } else if (inputTypeSelect.value === 'radio' || inputTypeSelect.value === 'checkbox') {
                        modelSelect.style.display = 'none';
                        modelSelect.disabled = true;
                        placeholder.style.display = 'none';
                        extraInput.style.display = 'block';
                        extraInput.disabled = false;
                    } else {
                        modelSelect.style.display = 'none';
                        modelSelect.disabled = true;
                        placeholder.style.display = 'block';
                        extraInput.style.display = 'none';
                        extraInput.disabled = false;
                    }
                });

                
                tr.appendChild(tdModelAndOtherOptions);


                // Validation container setup
                const tdValidation = document.createElement('td');
                const validationContainer = document.createElement('div');
                validationContainer.style.display = 'flex';
                validationContainer.style.alignItems = 'center';
                validationContainer.style.gap = '8px';

                // Create the Validation checkbox
                const checkboxValidation = document.createElement('input');
                checkboxValidation.type = 'checkbox';
                checkboxValidation.name = `fieldNames[${fieldName}][validation]`;
                checkboxValidation.classList.add('action-checkbox');
                checkboxValidation.disabled = true;
                validationContainer.appendChild(checkboxValidation);

                // Create the Validation icon with tooltip
                const validationIcon = document.createElement('span');
                validationIcon.classList.add('validation-icon');
                validationIcon.style.cursor = 'pointer';
                validationIcon.style.opacity = '0.5';
                validationIcon.innerHTML = '⚙️';
                validationIcon.title = 'Configure validation rules'; // Tooltip

                validationIcon.style.pointerEvents = 'none'; // Disable clicking
                checkboxValidation.addEventListener('change', function() {
                    validationIcon.style.opacity = checkboxValidation.checked ? '1' : '0.5';
                    validationIcon.style.pointerEvents = checkboxValidation.checked ? 'auto' : 'none';
                });

                validationIcon.addEventListener('click', function(event) {
                    if (!validationIcon.style.pointerEvents) return;
                    showValidationModal(event, fieldName);
                });
                validationContainer.appendChild(validationIcon);
                tdValidation.appendChild(validationContainer);
                tr.appendChild(tdValidation);

                tableBody.appendChild(tr);
            }
        });
    }

    function manageInputTypeState(checkboxes, inputTypeSelect, checkboxValidation) {
        const isCreateOrEditChecked = checkboxes[0].checked || checkboxes[1].checked;
        const isListChecked = checkboxes[2].checked;

        if (isCreateOrEditChecked) {
            inputTypeSelect.disabled = false;
            checkboxValidation.disabled = false;
        } else {
            inputTypeSelect.disabled = true;
            checkboxValidation.disabled = true;
        }

        if (isListChecked && !isCreateOrEditChecked) {
            inputTypeSelect.disabled = true;
            checkboxValidation.disabled = true;
        }
    }

    const fieldValidations = {};
    function showValidationModal(event, fieldName) {
        event.preventDefault();
        const modal = document.createElement('div');
        modal.classList.add('modal', 'fade');
        modal.id = 'validationModal';
        modal.tabIndex = '-1';
        modal.setAttribute('aria-labelledby', 'validationModalLabel');
        modal.setAttribute('aria-hidden', 'true');

        const modalDialog = document.createElement('div');
        modalDialog.classList.add('modal-dialog', 'modal-lg');

        const modalContent = document.createElement('div');
        modalContent.classList.add('modal-content');

        const modalHeader = document.createElement('div');
        modalHeader.classList.add('modal-header');

        const modalTitle = document.createElement('h5');
        modalTitle.classList.add('modal-title');
        modalTitle.id = 'validationModalLabel';
        modalTitle.innerText = 'Field Validation Configuration';

        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.classList.add('btn-close');
        closeButton.setAttribute('data-bs-dismiss', 'modal');
        closeButton.setAttribute('aria-label', 'Close');

        modalHeader.appendChild(modalTitle);
        modalHeader.appendChild(closeButton);

        const modalBody = document.createElement('div');
        modalBody.classList.add('modal-body');

        const modalBodyHeader = document.createElement('h6');
        modalBodyHeader.innerHTML = '<strong>⚙️ Validation Rules</strong>';

        const tableContainer = document.createElement('div');
        tableContainer.classList.add('table-responsive');
        tableContainer.style.maxHeight = '660px';
        tableContainer.style.overflowY = 'auto';

        const table = document.createElement('table');
        table.classList.add('table', 'table-striped', 'table-bordered');
        table.style.minWidth = '600px';

        const tableHeader = document.createElement('thead');
        const headerRow = document.createElement('tr');
        headerRow.innerHTML = '<th>Validation Rule</th><th>Description</th>';
        tableHeader.appendChild(headerRow);

        const tableBody = document.createElement('tbody');

        // Populate table with validation rules
        const validationRules = [
            { rule: 'nullable', description: "Allows the field to be empty"},
            { rule: "required", description: "This field is required." },
            { rule: "string", description: "This field must be a string." },
            { rule: "integer", description: "This field must be an integer." },
            { rule: "numeric", description: "This field must be a numeric value." },
            { rule: "email", description: "This field must be a valid email address." },
            { rule: "date", description: "This field must be a valid date." },
            { rule: "min:value", description: "This field must have a minimum length or value." },
            { rule: "max:value", description: "This field must have a maximum length or value." },
            { rule: "between:min,max", description: "This field must be between a minimum and maximum value." },
            { rule: "digits:value", description: "This field must have exactly the specified number of digits." },
            { rule: "digits_between:min,max", description: "This field must have a digit count between the specified range." },
        ];

        validationRules.forEach(({ rule, description }) => {
        const row = document.createElement('tr');

        const hasValue = rule.includes(':');

        let isChecked = false;
        let fieldValue = '';
        if (fieldValidations[fieldName]) {
            fieldValidations[fieldName].forEach(existingRule => {
                const [ruleName, ruleValue, actualValue] = existingRule.split(':');

                if (ruleName === rule.split(':')[0]) {
                    isChecked = true;
                    fieldValue = actualValue || '';
                }
            });
        }

        row.innerHTML = `
            <td>
                <div class="form-check">
                    <input type="checkbox" name="validations[${fieldName}][${rule}]" value="${rule}" id="validation-${rule.replace(/:/g, '-')}" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="validation-${rule.replace(/:/g, '-')}" class="form-check-label">${rule}</label>
                </div>
                ${hasValue ? `<input type="text" class="form-control form-control-sm mt-2" placeholder="${rule.split(':')[1]}" id="value-${rule.replace(/:/g, '-')}" style="width: 100px;" value="${fieldValue}" ${isChecked ? '' : 'disabled'} />` : ''}
            </td>
            <td>${description}</td>
        `;

        const checkbox = row.querySelector(`input[type="checkbox"]`);
        const valueInput = row.querySelector(`input[type="text"]`);

        if (checkbox && valueInput) {
            checkbox.addEventListener('change', () => {
                valueInput.disabled = !checkbox.checked;
                if (!checkbox.checked) {
                    valueInput.value = '';
                }
            });
        }

        tableBody.appendChild(row);
    });


        table.appendChild(tableHeader);
        table.appendChild(tableBody);
        tableContainer.appendChild(table);
        modalBody.appendChild(modalBodyHeader);
        modalBody.appendChild(tableContainer);

        const modalFooter = document.createElement('div');
        modalFooter.classList.add('modal-footer');

        const footerCloseButton = document.createElement('button');
        footerCloseButton.type = 'button';
        footerCloseButton.classList.add('btn', 'btn-danger');
        footerCloseButton.setAttribute('data-bs-dismiss', 'modal');
        footerCloseButton.innerText = 'Close';

        const saveButton = document.createElement('button');
        saveButton.type = 'button';
        saveButton.classList.add('btn', 'btn-primary');
        saveButton.id = 'save-validation';
        saveButton.innerText = 'Save changes';

        modalFooter.appendChild(footerCloseButton);
        modalFooter.appendChild(saveButton);

        modalContent.appendChild(modalHeader);
        modalContent.appendChild(modalBody);
        modalContent.appendChild(modalFooter);
        modalDialog.appendChild(modalContent);
        modal.appendChild(modalDialog);

        const curdGeneratorForm = document.getElementById('curd-generator-form');
        curdGeneratorForm.appendChild(modal);

        saveButton.addEventListener('click', function() {
            const selectedRules = [];
            validationRules.forEach(({ rule }) => {
                const checkbox = document.getElementById(`validation-${rule.replace(/:/g, '-')}`);
                if (checkbox.checked) {
                    const valueInput = document.getElementById(`value-${rule.replace(/:/g, '-')}`);
                    const value = valueInput ? valueInput.value : undefined;
                    selectedRules.push(value ? `${rule}:${value}` : rule);


                    const inputId = `validation-input-${fieldName}-${rule.replace(/:/g, '-')}`;
                    let hiddenInput = document.getElementById(inputId);
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.id = inputId;
                        hiddenInput.name = `validations[${fieldName}][${rule}]`;
                        curdGeneratorForm.appendChild(hiddenInput);
                    }
                    hiddenInput.value = value ? `${rule}:${value}` : rule;
                }
            });
            fieldValidations[fieldName] = selectedRules; 
            console.log(validationRules)
            console.log(fieldValidations)
            bootstrapModal.hide();
        });
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        modal.addEventListener('hidden.bs.modal', () => {
            curdGeneratorForm.removeChild(modal);
        });
    }

    document.getElementById('use_case_type').addEventListener('change', function () {
        const accordionCrud = document.getElementById('accordionCrudSection');
        const accordionApi = document.getElementById('accordionApiSection');

        if (this.value === 'api') {
            accordionCrud.classList.add('d-none');
            accordionApi.classList.remove('d-none');
        } else {
            accordionCrud.classList.remove('d-none');
            accordionApi.classList.add('d-none');
        }
    });

    function apiValidation() {
        const fieldContainer = document.querySelectorAll('#field-container .field-row');
        const tableBody = document.querySelector('#field-selection-api-table tbody');

        tableBody.innerHTML = '';

        fieldContainer.forEach(function (row) {
            const fieldName = row.querySelector('input[name^="fields"][name$="[name]"]').value;

            if (fieldName) {
                const tr = document.createElement('tr');
                
                const tdFieldName = document.createElement('td');
                tdFieldName.textContent = fieldName
                tr.appendChild(tdFieldName);

                // Validation container setup
                const tdValidation = document.createElement('td');
                const validationContainer = document.createElement('div');
                validationContainer.style.display = 'flex';
                validationContainer.style.alignItems = 'center';
                validationContainer.style.gap = '8px';

                // Create the Validation checkbox
                const checkboxValidation = document.createElement('input');
                checkboxValidation.type = 'checkbox';
                checkboxValidation.name = `fieldNames[${fieldName}][validation]`;
                checkboxValidation.classList.add('action-checkbox');
                validationContainer.appendChild(checkboxValidation);

                // Create the Validation icon with tooltip
                const validationIcon = document.createElement('span');
                validationIcon.classList.add('validation-icon');
                validationIcon.style.cursor = 'pointer';
                validationIcon.style.opacity = '0.5';
                validationIcon.innerHTML = '⚙️';
                validationIcon.title = 'Configure validation rules'; // Tooltip

                validationIcon.style.pointerEvents = 'none'; // Disable clicking
                checkboxValidation.addEventListener('change', function() {
                    validationIcon.style.opacity = checkboxValidation.checked ? '1' : '0.5';
                    validationIcon.style.pointerEvents = checkboxValidation.checked ? 'auto' : 'none';
                });

                validationIcon.addEventListener('click', function(event) {
                    if (!validationIcon.style.pointerEvents) return;
                    showValidationModal(event, fieldName);
                });
                validationContainer.appendChild(validationIcon);
                tdValidation.appendChild(validationContainer);
                tr.appendChild(tdValidation);

                tableBody.appendChild(tr);
            }
        });
    }

    document.getElementById('guideline-modal-button-api').addEventListener('click', function () {
        apiValidation();
    });

</script>
@endpush
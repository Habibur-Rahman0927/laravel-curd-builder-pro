<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrudGeneratorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model_name' => 'required|string|max:255',
            'use_case_type' => 'required',
            'softdelete' => 'nullable|boolean',
            'fields' => 'required|array',
            'fields.*.type' => 'required|string|in:bigInteger,binary,boolean,char,dateTime,date,decimal,double,float,integer,ipAddress,json,longText,macAddress,mediumInteger,mediumText,smallInteger,string,text,time,tinyInteger,tinyText,unsignedBigInteger,unsignedInteger,unsignedMediumInteger,unsignedSmallInteger,unsignedTinyInteger,uuid,year,foreignId',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.nullable' => 'nullable|string|in:nullable',
            'fields.*.unique' => 'nullable|string|in:unique',
            'fields.*.index' => 'nullable|string|in:index',
            'fields.*.unsigned' => 'nullable|string|in:unsigned',
            'fields.*.comment' => 'nullable|string|max:255',
            'relationships' => 'nullable|array',
            'relationships.*.type' => 'required|string|in:hasOne,hasMany,belongsTo,belongsToMany',
            'relationships.*.related_model' => 'required|string|max:255',
            'relationships.*.foreign_key' => 'required|string|max:255',
            'fieldNames' => [
                'required_if:use_case_type,api_curd,curd',
                'array',
                function ($attribute, $value, $fail) {
                    if (in_array($this->use_case_type, ['api_curd', 'curd'])) {
                        $hasCreateEditList = false;
            
                        foreach ($value as $field) {
                            if (!empty($field['create']) || !empty($field['edit']) || !empty($field['list'])) {
                                $hasCreateEditList = true;
                                break;
                            }
                        }
            
                        if (!$hasCreateEditList) {
                            $fail('For standard CRUD, at least one field must be set to "create", "edit", or "list" to proceed.');
                        }
                    }
                },
            ],
            
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'fields.required' => 'Migration fields are required.',
            'fields.*.required' => 'Each field entry is required.',
            'fields.*.type.required' => 'The field type is required.',
            'fields.*.name.required' => 'The field name is required.',
            'relationships.*.type.required' => 'The relationship type is required.',
            'relationships.*.related_model.required' => 'The related model is required.',
            'relationships.*.foreign_key.required' => 'The foreign key is required.',
        ];
    }
}

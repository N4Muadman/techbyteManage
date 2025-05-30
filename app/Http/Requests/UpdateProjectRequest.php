<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'project_edit.name' => 'required|max:255',
            'project_edit.type' => 'required',
            'project_edit.start_date' => 'required|date',
            'project_edit.end_date' => 'required|date',
            'project_edit.total_cost' => 'nullable',
            'project_edit.leader_id' => 'required|exists:users,id',
            'project_edit.description' => 'nullable',
            'project_edit.archive_link' => 'nullable',
            'project_edit.document_link' => 'nullable',
            'project_edit.project_members' => 'required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProjectRequest extends FormRequest
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
            'project.name' => 'required|max:255',
            'project.type' => 'required',
            'project.start_date' => 'required|date',
            'project.end_date' => 'required|date',
            'project.total_cost' => 'nullable',
            'project.leader_id' => 'required|exists:users,id',
            'project.description' => 'nullable',
            'project.archive_link' => 'nullable',
            'project.document_link' => 'nullable',
            'project.project_members' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'project.name.required' => 'Tên dự án không được để trống.',
            'project.name.max' => 'Tên dự án không được vượt quá 255 ký tự.',
            'project.type.required' => 'Loại dự án là bắt buộc.',
            'project.start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'project.start_date.date' => 'Ngày bắt đầu phải là định dạng ngày hợp lệ.',
            'project.end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'project.end_date.date' => 'Ngày kết thúc phải là định dạng ngày hợp lệ.',
            'project.total_cost.required' => 'Tổng chi phí là bắt buộc.',
            'project.leader_id.required' => 'Phải chọn trưởng nhóm dự án.',
            'project.leader_id.exists' => 'Trưởng nhóm được chọn không hợp lệ.',
            'project.project_members.required' => 'Phải chọn thành viên dự án.',
        ];
    }
}

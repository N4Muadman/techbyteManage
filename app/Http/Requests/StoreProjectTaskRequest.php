<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectTaskRequest extends FormRequest
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
            'project_tasks.*.title' => 'required|max:255',
            'project_tasks.*.description' => 'nullable',
            'project_tasks.*.due_date' => 'required|date',
            'project_tasks.*.task_members' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'project_tasks.*.title.required' => 'Tiêu đề công việc không được để trống.',
            'project_tasks.*.title.max' => 'Tiêu đề công việc không được vượt quá 255 ký tự.',
            'project_tasks.*.due_date.required' => 'Ngày hết hạn không được để trống.',
            'project_tasks.*.due_date.date' => 'Ngày hết hạn phải là định dạng ngày hợp lệ.',
            'project_tasks.*.task_members.required' => 'Bạn phải chọn ít nhất một thành viên dự án.',
        ];
    }
}

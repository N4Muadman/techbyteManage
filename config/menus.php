<?php

return [
    [
        'title' => 'Quản lý nhân sự',
        'items' => [
            ['name' => 'Quản lý nhân viên', 'route' => 'employee.index.admin', 'permission_ids' => [1,2,3,4,5], 'page_config' => 'employee_management'],
            ['name' => 'Quản lý tiền lương', 'route' => 'salary.index.admin', 'permission_ids' => [3,4], 'page_config' => 'salary_management'],
            ['name' => 'Quản lý chi nhánh', 'route' => 'branch.index.admin', 'permission_ids' => [1,2,3,4], 'page_config' => 'branch_management'],
            ['name' => 'Quản lý tuyển dụng', 'route' => 'recruitment.index.admin', 'permission_ids' => [1,2,3,4], 'page_config' => 'recruitment_management'],
        ]
    ],
    [
        'title' => 'Quản lý hành chính',
        'items' => [
            ['name' => 'Quản lý nghỉ phép', 'route' => 'leave.index.admin', 'permission_ids' => [1,3,4,6], 'page_config' => 'leave_management'],
            ['name' => 'Quản lý ca làm', 'route' => 'work_shift.index.admin', 'permission_ids' => [1,2,3,4], 'page_config' => 'work_shitf_management'],
            ['name' => 'Đánh giá và khen thưởng', 'route' => 'evaluation.index.admin', 'permission_ids' => [1,2,3], 'page_config' => 'evaluation_rewards'],
        ]
    ],
    [
        'title' => 'Khách hàng từ đâu',
        'items' => [
            ['name' => 'Khách hàng mới', 'route' => 'newCustomer', 'permission_ids' => [1,2,3,4,7], 'page_config' => 'customer_new'],
            ['name' => 'Thống kê', 'route' => 'customerStatistics', 'permission_ids' => [3], 'page_config' => 'statistics_customer'],
        ]
    ],
    [
        'title' => 'Khách hàng doanh nghiệp',
        'items' => [
            ['name' => 'Danh sách doanh nghiệp', 'route' => 'businessCustomer', 'permission_ids' => [2,3,4,8], 'page_config' => 'list_business'],
            ['name' => 'Thống kê doanh nghiệp', 'route' => 'businessStatistics', 'permission_ids' => [3], 'page_config' => 'statistics_business'],
        ]
    ],
    [
        'title' => 'Quản lý dự án',
        'items' => [
            ['name' => 'Quản lý dự án', 'route' => 'projects.index', 'permission_ids' => [1,2,3,4,6], 'page_config' => 'project_management']
        ]
    ],
    [
        'title' => 'Quản lý chấm công',
        'items' => [
            ['name' => 'Quản lý chấm công', 'route' => 'attendance.index.admin', 'permission_ids' => [1,3], 'page_config' => 'attendance_management']
        ]
    ],
    [
        'title' => 'Hỗ trợ / khiếu nại',
        'items' => [
            ['name' => 'Hỗ trợ / khiếu nại', 'route' => 'complaint.index.admin', 'permission_ids' => [1,2,3,4,6], 'page_config' => 'support_complaint']
        ]
    ],
];

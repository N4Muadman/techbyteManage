<?php

namespace Database\Seeders;

use App\Models\role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RolePagePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_page_permissions')->truncate();
        DB::table('page_permissions')->truncate();
        DB::table('pages')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role')->truncate();

        DB::transaction(function () {
            $permissions = [
                [
                    'id' => '1',
                    'name' => 'Add'
                ],
                [
                    'id' => '2',
                    'name' => 'Edit'
                ],
                [
                    'id' => '3',
                    'name' => 'Show'
                ],
                [
                    'id' => '4',
                    'name' => 'Delete'
                ],
                [
                    'id' => '5',
                    'name' => 'Permission'
                ],
                [
                    'id' => '6',
                    'name' => 'Approve'
                ],
                [
                    'id' => '7',
                    'name' => 'Close deal'
                ],
                [
                    'id' => '8',
                    'name' => 'Feedback'
                ],
            ];

            $pages = [
                [
                    'id' => '1',
                    'name' => 'Quản lý nhân viên',
                    'description' => '',
                    'permission' => [1, 2, 3, 4, 5]
                ],
                [
                    'id' => '2',
                    'name' => 'Quản lý tiền lương',
                    'description' => '',
                    'permission' => [2, 3]
                ],
                [
                    'id' => '3',
                    'name' => 'Quản lý chi nhánh',
                    'description' => '',
                    'permission' => [1, 2, 3, 4]
                ],
                [
                    'id' => '4',
                    'name' => 'Quản lý tuyển dụng',
                    'description' => '',
                    'permission' => [1, 2, 3, 4]
                ],
                [
                    'id' => '5',
                    'name' => 'Quản lý nghỉ phép',
                    'description' => '',
                    'permission' => [1, 3, 4, 6]
                ],
                [
                    'id' => '6',
                    'name' => 'Quản lý ca làm',
                    'description' => '',
                    'permission' => [1, 2, 3, 4]
                ],
                [
                    'id' => '7',
                    'name' => 'Đánh giá và khen thưởng',
                    'description' => '',
                    'permission' => [1, 2, 3]
                ],
                [
                    'id' => '8',
                    'name' => 'Khách mới',
                    'description' => '',
                    'permission' => [1, 2, 3, 4, 7]
                ],
                [
                    'id' => '9',
                    'name' => 'Thống kê',
                    'description' => '',
                    'permission' => [3]
                ],
                [
                    'id' => '10',
                    'name' => 'Danh sách doanh nghiệp',
                    'description' => '',
                    'permission' => [2, 3, 4, 8]
                ],
                [
                    'id' => '11',
                    'name' => 'Thống kê doanh nghiệp',
                    'description' => '',
                    'permission' => [3]
                ],
                [
                    'id' => '12',
                    'name' => 'Quản lý dự án',
                    'description' => '',
                    'permission' => [1, 2, 3, 4]
                ],
                [
                    'id' => '13',
                    'name' => 'Quản lý chấm công',
                    'description' => '',
                    'permission' => [1, 3, 4]
                ],
                [
                    'id' => '14',
                    'name' => 'Hỗ trợ / khiếu nại',
                    'description' => '',
                    'permission' => [1, 3, 6]
                ],
            ];

            DB::table('permissions')->insert($permissions);

            foreach ($pages as $page) {
                DB::table('pages')->insert([
                    'id' => $page['id'],
                    'name' => $page['name'],
                    'description' => $page['description']
                ]);

                foreach ($page['permission'] as $permission) {
                    DB::table('page_permissions')->insert([
                        'page_id' => $page['id'],
                        'permission_id' => $permission
                    ]);
                }
            }

            DB::table('role')->insert(config('role_default'));

            $roles = role::where('id', '!=', 1)->get();
            $page_permission_ids = DB::table('page_permissions')->pluck('id')->toArray();

            $data_page_permissions = array_map(function ($id) {
                return [
                    'page_permission_id' => $id
                ];
            }, $page_permission_ids);

            foreach ($roles as $role) {
                $role->RolePagePermission()->createMany($data_page_permissions);
            }

            Cache::flush();
        });
    }
}

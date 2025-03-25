@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Quyền của từng vai trò</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('permission.index') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 col-sm-3 mb-2">
                <select class="form-select" name="role_id" id="">
                    @foreach ($roles as $it)
                        @if ($it->id == request('role_id'))
                            <option selected value="{{ $it->id }}">{{ $it->name }}</option>
                        @else
                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-sm-3 mb-2">
                <button type="submit" class="btn btn-success  me-3">Chọn</button>
            </div>
        </div>
    </form>


    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th style="width: 15%">Tên trang</th>
                                    <th>Quyền truy cập</th>
                                    <th>Hiển thị danh mục</th>
                                    <th>Thêm</th>
                                    <th>Sửa</th>
                                    <th>Xem</th>
                                    <th>Xóa</th>
                                    <th>Phân quyền</th>
                                    <th>Phê duyệt</th>
                                    <th>Chốt deal</th>
                                    <th>Phản hồi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($role_page_permissions->unique(fn($item) => $item->pagePermission->page_id) as $role_page)
                                    <tr>
                                        <td>{{ optional($role_page->pagePermission->page)->name }}</td>

                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 1) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Hiển thị danh mục --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 2) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Thêm --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 3) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Sửa --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 4) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Xem --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 5) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Xóa --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 6) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Phân quyền --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 7) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Phê duyệt --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 8) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Chốt deal --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 9) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>

                                        {{-- Phản hồi --}}
                                        @php $permission = $role_page_permissions->firstWhere(fn($item) => $item->pagePermission->page_id === $role_page->pagePermission->page_id && $item->pagePermission->permission->id == 10) @endphp
                                        <td>
                                            @if ($permission)
                                                <input type="checkbox" class="checkbox-permission"
                                                    {{ $permission->status == 1 ? 'checked' : '' }}
                                                    value="{{ $permission->id }}">
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.checkbox-permission').forEach(element => {
            element.addEventListener('change', async function(event) {
                const id = element.value;
                const originalChecked = element.checked;
                if (id) {
                    try {
                        const response = await fetch('{{ route('permission.change.status', ':id') }}'
                            .replace(':id', id), {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                            });

                        const data = await response.json();

                        if (!response.ok) {
                            alert('Có lỗi xảy ra');
                            console.log('Có lỗi xảy ra ' + data.message);

                            element.checked = !originalChecked;
                        }

                    } catch (error) {
                        alert('Có lỗi xảy ra');
                        console.log('Có lỗi xảy ra ' + error);
                        element.checked = !originalChecked;
                    }
                }
            })
        });
    </script>
@endsection

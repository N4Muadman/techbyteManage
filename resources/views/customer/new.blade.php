@extends('layout')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Khách hàng từ đâu</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Khách mới</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách khách hàng</h2>
                        @if (Auth::user()->hasPermissionOnPage('1', '8'))
                            <button data-bs-toggle="modal" data-bs-target="#addCustomer"
                                class="btn btn-light-primary d-flex align-items-center gap-2"><i class="ti ti-plus"></i> Add
                                new
                                item</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('newCustomer') }}" method="get">
        <div class="row mt-3">
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên" value="{{ request('name') }}"
                    name="name" id="">
            </div>
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo số điện thoại"
                    value="{{ request('phone_number') }}" name="phone_number" id="">
            </div>
            <div class="col-6 mb-2 col-sm-2">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo nhân viên"
                    value="{{ request('employee') }}" name="employee" id="">
            </div>
            <div class="col-12 d-flex justify-content-between  d-sm-block col-sm-3">
                <button type="submit" class="btn btn-success  me-3">Tìm kiếm</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col-12">
            <div class="card table-card">
                <div class="card-body pt-3">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>Họ tên</th>
                                    <th>Giới tính</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                    <th>Ngày tìm tới mình</th>
                                    <th>Đối tượng</th>
                                    <th>MXH</th>
                                    @if (Auth::user()->role_id != 5)
                                        <th>Nhân viên tư vấn</th>
                                    @endif
                                    <th>Chức năng</th>

                                    @if (Auth::user()->hasPermissionOnPage('7', '8'))
                                        <th>Chốt deal</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $it)
                                    <tr>
                                        <td>{{ $it->full_name }}</td>
                                        <td>{{ $it->gender }}</td>
                                        <td>{{ $it->phone_number }}</td>
                                        <td>{{ $it->email }}</td>
                                        <td>{{ $it->date_find_to_me }}</td>
                                        <td>{{ $it->object }}</td>
                                        <td>{{ $it->social_network }}</td>
                                        @if (Auth::user()->role_id != 4)
                                            <th>{{ $it->employee->full_name }}</th>
                                        @endif
                                        <td>
                                            @if (Auth::user()->hasPermissionOnPage('3', '8'))
                                                <a href="#" class="avtar avtar-show avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-eye"></i></a>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('2', '8'))
                                                <a href="#" class="avtar avtar-edit avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-user-edit"></i></a>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('4', '8'))
                                                <a href="#" class="avtar avtar-delete avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-trash"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->hasPermissionOnPage('7', '8'))
                                                <a href="#" class="avtar avtar-lg avtar-deal btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-handshake"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <p class="text-center">không có khách hàng nào</p>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="ps-5 pe-5">
                            {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCustomer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Chi tiết khách hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createCustomer') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-lable">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Nhập họ tên" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">Giới tính</label>
                            <select class="form-select" name="gender">
                                <option value="Nam">Nam</option>"
                                <option value="Nữ">Nữ</option>"
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">SDT</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="Số điện thoại"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Nhập email" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">Ngày tìm tới mình</label>
                            <input type="date" name="date_find_to_me" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">Đối tượng</label>
                            <input type="text" name="object" class="form-control"
                                placeholder="VD: Sinh viên, doanh nghiệp, chủ đầu tư,..." required>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-lable">Mạng xã hội</label>
                            <select class="form-select" name="social_network" required>
                                <option value="Facebook">Facebook</option>
                                <option value="Tiktok">Tiktok</option>
                                <option value="Youtube">Youtube</option>
                                <option value="Web">Web</option>
                                <option value="Được giới thiệu">Được giới thiệu</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="show-customer"></div>
    <div id="edit-customer"></div>
    <div id="delete-customer"></div>
    <div id="deal-customer"></div>

    <script>
        let customer = {};
        async function getCustomer(id) {
            try {
                const response = await fetch('{{ route('showCustomer', ':id') }}'.replace(':id', id), {
                    method: 'GET',
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (!response.ok) {
                    alert('có lỗi xảy ra');
                    return;
                }
                const data = await response.json();
                customer = data.customer;

            } catch (error) {
                console.log(error);
            }
        }

        document.querySelectorAll('.avtar-show').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                await getCustomer(id);
                console.log(customer);

                document.getElementById('show-customer').innerHTML = `<div class="modal fade" id="show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Chi tiết khách hàng</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Họ tên: <strong>${customer.full_name}</strong></p>
                                                                            <p>Giới tính: <strong>${customer.gender}</strong></p>
                                                                            <p>SĐT: <strong>${customer.phone_number}</strong></p>
                                                                            <p>EMAIL: <strong>${customer.email}</strong></p>
                                                                            <p>NGÀY TÌM TỚI MÌNH: <strong>${customer.date_find_to_me}</strong></p>
                                                                            <p>ĐỐI TƯỢNG: <strong>${customer.object}</strong></p>
                                                                            <p>MXH: <strong>${customer.social_network}</strong></p>
                                                                            <p>NHÂN VIÊN TƯ VẤN: <strong>${customer.employee.full_name}</strong></p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`;
                const successModal = new bootstrap.Modal(document.getElementById('show'));
                successModal.show();
            });
        });

        document.querySelectorAll('.avtar-edit').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                await getCustomer(id);
                const socialNetwork = [{
                        name: 'Facebook',
                        value: 'Facebook'
                    },
                    {
                        name: 'Tiktok',
                        value: 'Tiktok'
                    },
                    {
                        name: 'Youtube',
                        value: 'Youtube'
                    },
                    {
                        name: 'Web',
                        value: 'Web'
                    },
                    {
                        name: 'Được giới thiệu',
                        value: 'Được giới thiệu'
                    },
                ];
                document.getElementById('edit-customer').innerHTML = `<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Chi tiết khách hàng</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('updateCustomer', ':id') }}" method="post">
                                                                                @csrf
                                                                                @method('put')
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Họ và tên</label>
                                                                                    <input type="text" name="full_name" class="form-control" value="${customer.full_name}" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Giới tính</label>
                                                                                    <select class="form-select" name="gender">
                                                                                        <option value="Nam" ${customer.gender == 'nam'? 'selected': ''}>Nam</option>"
                                                                                        <option value="Nữ" ${customer.gender == 'Nữ'? 'selected': ''}>Nữ</option>"
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">SDT</label>
                                                                                    <input type="text" name="phone_number" class="form-control" value="${customer.phone_number}" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Email</label>
                                                                                    <input type="text" name="email" class="form-control" value="${customer.email}" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Ngày tìm tới mình</label>
                                                                                    <input type="date" name="date_find_to_me" class="form-control" value="${customer.date_find_to_me}" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Đối tượng</label>
                                                                                    <input type="text" name="object" class="form-control" value="${customer.object}" required>
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <label for="" class="form-lable">Mạng xã hội</label>
                                                                                    <select class="form-select" name="social_network" required>
                                                                                        ${socialNetwork.map(item =>
                                                                                            `<option value="${item.value}" ${item.value == customer.social_network ? 'selected' : ''}>${item.name}</option>`
                                                                                        ).join('')}
                                                                                    </select>
                                                                                </div>
                                                                                <button type="submit" class="btn btn-secondary">Chỉnh sửa</button>
                                                                            </form>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`.replace(':id', customer.id);
                const successModal = new bootstrap.Modal(document.getElementById('edit'));
                successModal.show();
            });
        });

        document.querySelectorAll('.avtar-delete').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                await getCustomer(id);
                document.getElementById('delete-customer').innerHTML = `<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Xác nhận xóa</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Bạn có chắc chắn muốn xóa khách hàng: <strong>${customer.full_name}</strong> không?</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                                <form action="{{ route('deleteCustomer', ':id') }}" method="post">
                                                                                    @csrf
                                                                                    @method('delete')
                                                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`.replace(':id', customer.id);
                const successModal = new bootstrap.Modal(document.getElementById('delete'));
                successModal.show();
            });
        });

        document.querySelectorAll('.avtar-deal').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                await getCustomer(id);
                document.getElementById('deal-customer').innerHTML = `<div class="modal fade" id="deal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Xác nhận chốt deal</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                 <form action="{{ route('dealCustomer', ':id') }}" method="post">
                                                                                    @csrf
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Ngày kí hợp đồng</label>
                                                                                        <input type="date" name="date" class="form-control" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Mã hợp đồng</label>
                                                                                        <input type="text" name="contract_code" class="form-control" placeholder="Nhập mã hợp đồng" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Giá trị hợp đồng</label>
                                                                                        <input type="text" name="contract_value" class="form-control money" id="contract-value" placeholder="Nhập giá trị hợp đồng" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Tiền tạm ứng</label>
                                                                                        <input type="text" name="advance_money" class="form-control money" id="advance-money" placeholder="Nhập tiền tạm ứng" required>
                                                                                        <p class="text-danger" id="error-advance"></p>
                                                                                    </div>
                                                                                    <button id="btnClose" type="submit" class="btn btn-secondary">Chốt</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`.replace(':id', customer.id);
                const successModal = new bootstrap.Modal(document.getElementById('deal'));
                successModal.show();

                function formatCurrencyVND(amount) {
                    return new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    }).format(amount);
                }
                const contractValueInput = document.getElementById('contract-value');
                const advanceMoneyInput = document.getElementById('advance-money');
                const submitButton = document.getElementById('btnClose');

                if (contractValueInput && advanceMoneyInput) {
                    [contractValueInput, advanceMoneyInput].forEach(input => {
                        input.addEventListener('blur', function() {
                            let rawValue = input.value.replace(/\D/g, '');

                            input.value = formatCurrencyVND(rawValue);

                            const contractValue = Number(contractValueInput.value
                                .replace(/\D/g, ''));
                            const advanceMoney = Number(advanceMoneyInput.value.replace(
                                /\D/g, ''));

                            if (advanceMoney > contractValue) {
                                document.getElementById('error-advance').innerText =
                                    'Số tiền tạm ứng không được lớn hơn giá trị hợp đồng';
                                submitButton.disabled = true; // Vô hiệu hóa nút Chốt
                            } else {
                                document.getElementById('error-advance').innerText =
                                ''; // Xóa thông báo lỗi nếu điều kiện không thỏa
                                submitButton.disabled = false; // Kích hoạt lại nút Chốt
                            }
                        });
                    });
                }
            });
        });
    </script>
@endsection

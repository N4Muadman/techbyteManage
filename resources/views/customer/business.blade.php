@extends('layout')
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="admin">Trang chủ</a>
                        </li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Khách hàng doanh nghiệp</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-2">Danh sách khách hàng</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('businessCustomer') }}" method="get">
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
                                    @if (Auth::user()->role_id != 3)
                                        <th>Nhân viên tư vấn</th>
                                    @endif
                                    <th>Chức năng</th>
                                    @if (Auth::user()->hasPermissionOnPage('8', '10'))
                                        <th>Phản hồi</th>
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
                                        @if (Auth::user()->role_id != 5)
                                            <th>{{ $it->employee->full_name }}</th>
                                        @endif
                                        <td>
                                            <a href="#" class="avtar avtar-add-contract avtar-xs btn-link-secondary"
                                                data-id="{{ $it->id }}" data-name="{{ $it->full_name }}"><i class="fas fa-plus" title="Thêm hợp đồng mới"></i></a>
                                            @if (Auth::user()->hasPermissionOnPage('3', '10'))
                                                <a href="#" class="avtar avtar-show avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-eye"></i></a>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('2', '10'))
                                                <a href="#" class="avtar avtar-edit avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-user-edit"></i></a>
                                            @endif
                                            @if (Auth::user()->hasPermissionOnPage('4', '10'))
                                                <a href="#" class="avtar avtar-delete avtar-xs btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i class="fas fa-trash"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if (Auth::user()->hasPermissionOnPage('8', '10'))
                                                <a href="#" class="avtar avtar-lg avtar-feedback btn-link-secondary"
                                                    data-id="{{ $it->id }}"><i
                                                        class="fab fa-facebook-messenger"></i></a>
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
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="add-contract"></div>
    <div id="show-customer"></div>
    <div id="edit-customer"></div>
    <div id="delete-customer"></div>
    <div id="feedback-customer">
        <div class="modal fade" id="feedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Phản hồi của khách hàng: <span
                                id="customer-name"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <form action="{{ route('uploadFeedback') }}" method="post"
                                class="col-12 d-flex justify-content-between" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <input type="text" name="customer_id" hidden id="customer_id_feedback">
                                    <label for="" class="form-label me-3">Chọn ảnh: </label>
                                    <input type="file" name="feedbacks[]" class="form-control"
                                        style="display: inline; width: auto;" multiple>
                                </div>
                                <button type="submit" class="btn btn-secondary me-3">Gửi phản hồi</button>
                            </form>
                        </div>
                        <div class="row" id="list-feedbacks">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-contract"></div>

    <script>
        function formatCurrencyVND(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(amount);
        }
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

        document.querySelectorAll('.avtar-add-contract').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                const name = element.dataset.name;
                document.getElementById('add-contract').innerHTML = `<div class="modal fade" id="deal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Thêm mới hợp đồng</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p class="f-18">Cho khách hàng: <strong>${name}</strong></p>
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
                                                                                    <button id="btnClose" type="submit" class="btn btn-secondary">Thêm</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`.replace(':id', id);

                const successModal = new bootstrap.Modal(document.getElementById('deal'));
                successModal.show();
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
        document.querySelectorAll('.avtar-show').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                await getCustomer(id);

                document.getElementById('show-customer').innerHTML = `<div class="modal fade" id="show" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-xl">
                                                                        <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLabel">Chi tiết khách hàng</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>HỌ TÊN: <strong>${customer.full_name}</strong></p>
                                                                            <p>GIỚI TÍNH: <strong>${customer.gender}</strong></p>
                                                                            <p>SĐT: <strong>${customer.phone_number}</strong></p>
                                                                            <p>EMAIL: <strong>${customer.email}</strong></p>
                                                                            <p>NGÀY TÌM TỚI MÌNH: <strong>${customer.date_find_to_me}</strong></p>
                                                                            <p>ĐỐI TƯỢNG: <strong>${customer.object}</strong></p>
                                                                            <p>MXH: <strong>${customer.social_network}</strong></p>
                                                                            <p>NHÂN VIÊN TƯ VẤN: <strong>${customer.employee.full_name}</strong></p>
                                                                            <h4 class="mb-4">Danh sách hợp đồng</h4>
                                                                            <table class="table table-hover text-center" id="pc-dt-simple">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Mã hợp đồng</th>
                                                                                        <th>Giá trị hợp đồng</th>
                                                                                        <th>Số tiền tạm ứng</th>
                                                                                        <th>Số tiền còn thiếu</th>
                                                                                        <th>Phần trăm hoàn thành</th>
                                                                                        <th>Ngày ký hợp đồng</th>
                                                                                        <th>Chức năng</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    ${customer.contract.map(item => `
                                                                                            <tr>
                                                                                                <td>${item.contract_code}</td>
                                                                                                <td>${formatCurrencyVND(item.contract_value)}</td>
                                                                                                <td>${formatCurrencyVND(item.advance_money)}</td>
                                                                                                <td>${formatCurrencyVND(item.contract_value - item.advance_money)}</td>
                                                                                                <td>${((item.advance_money / item.contract_value) * 100).toFixed(2)} %</td>
                                                                                                <td>${formatDate(item.date)}</td>
                                                                                                <td>
                                                                                                    <a href="#" class="avtar contract-edit avtar-xs btn-link-secondary" data-id="${item.id}"><i class="fas fa-edit"></i></a>
                                                                                                </td>
                                                                                            </tr>`).join('')}
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                        </div>
                                                                        </div>
                                                                    </div>
                                                                   </div>`;
                const successModal = new bootstrap.Modal(document.getElementById('show'));
                successModal.show();

                document.querySelectorAll('.contract-edit').forEach(element => {
                    element.addEventListener('click', async function() {
                        const id = element.dataset.id;

                        if (id) {
                            try {
                                const response = await fetch(
                                    '{{ route('getContract', ':id') }}'
                                    .replace(':id', id), {
                                        method: 'get',
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
                                const contract = data.contract;

                                document.getElementById('edit-contract').innerHTML = `
                                                                <div class="modal fade" id="update-contract" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Chi tiết hợp đồng</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="{{ route('updateContract', ':id') }}" method="post">
                                                                                    @csrf
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Ngày kí hợp đồng</label>
                                                                                        <input type="date" name="date" class="form-control" value="${contract.date}" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Mã hợp đồng</label>
                                                                                        <input type="text" name="contract_code" class="form-control" value="${contract.contract_code}" placeholder="Nhập mã hợp đồng" required>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Giá trị hợp đồng</label>
                                                                                        <input type="text" name="contract_value" class="form-control money" value="${formatCurrencyVND(contract.contract_value)}" disabled>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Tiền đã tạm ứng trước đó</label>
                                                                                        <input type="text" class="form-control money" value="${formatCurrencyVND(contract.advance_money)}" disabled>
                                                                                    </div>

                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Số tiền còn thiếu</label>
                                                                                        <input type="text" class="form-control money" id="arrears" value="${formatCurrencyVND(contract.contract_value - contract.advance_money)}" disabled>
                                                                                    </div>

                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Tiền ứng còn lại</label>
                                                                                        <input type="text" name="advance_money" class="form-control money" id="advance-money" placeholder="Nhập tiền còn lại">
                                                                                        <p class="text-danger" id="error-advance"></p>
                                                                                    </div>

                                                                                    <div class="mb-3">
                                                                                        <label for="" class="form-lable">Ghi chú</label>
                                                                                        <input type="text" name="note" class="form-control" placeholder="Nhập ghi chú" value=${contract.note}>
                                                                                    </div>
                                                                                    <button type="submit" id="btnClose" class="btn btn-success">Chỉnh sửa</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>`.replace(':id', contract.id);

                            const successModal = new bootstrap.Modal(document.getElementById('update-contract'));
                            successModal.show();

                            const arrearsInput = document.getElementById('arrears');
                            const advanceMoneyInput = document.getElementById('advance-money');
                            const submitButton = document.getElementById('btnClose');

                            if (arrearsInput && advanceMoneyInput) {
                                    advanceMoneyInput.addEventListener('blur', function() {
                                        let rawValue = advanceMoneyInput.value.replace(/\D/g, '');

                                        advanceMoneyInput.value = formatCurrencyVND(rawValue);

                                        const arrears = Number(arrearsInput.value.replace(/\D/g, ''));
                                        const advanceMoney = Number(advanceMoneyInput.value.replace(/\D/g, ''));

                                        if (advanceMoney > arrears) {
                                            document.getElementById('error-advance').innerText = 'Số tiền tạm ứng còn lại không được lớn hơn số tiền còn thiếu';
                                            submitButton.disabled = true;
                                        } else {
                                            document.getElementById('error-advance').innerText = '';
                                            submitButton.disabled = false;
                                        }
                                    });
                            }

                            } catch (error) {
                                console.log(error);
                            }
                        }
                    })
                });
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

        document.querySelectorAll('.avtar-feedback').forEach(element => {
            element.addEventListener('click', async function(event) {
                const id = element.dataset.id;
                document.getElementById('customer_id_feedback').value = id;
                const response = await fetch('{{ route('getFeedback', ':id') }}'.replace(':id', id), {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF_TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    alert('Có lỗi xảy ra');
                    return;
                }

                document.getElementById('customer-name').innerHTML = data.feedbacks.length > 0 ? data
                    .feedbacks[0].customer.full_name : '';
                let listFeedbacks = '';
                if (data.feedbacks.length > 0) {
                    data.feedbacks.forEach(it => {
                        listFeedbacks += `<div class="col-12 col-md-6 mb-3">
                                            <img src="${it.img}" alt="" width="95%">
                                        </div>`
                    });
                } else {
                    listFeedbacks = '<p class="text-center">Không có phản hồi nào từ khách hàng</p>';
                }

                document.getElementById('list-feedbacks').innerHTML = listFeedbacks;

                const successModal = new bootstrap.Modal(document.getElementById('feedback'));
                successModal.show();
            });
        });

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('vi-VN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }
    </script>
@endsection

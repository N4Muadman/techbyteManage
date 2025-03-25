<!-- Modal Chi tiết -->
<div class="modal fade" id="detailEmployeeModal" tabindex="-1" aria-labelledby="detailEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="width: 80%; margin: auto;">
            <div class="modal-header">
                <h3 class="modal-title" id="detailEmployeeModalLabel">Chi tiết nhân viên</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <h4>Thông tin nhân viên</h4>
                        <p><strong>Tên đăng nhập:</strong> {{$employee->user->username}}</p>
                        <p><strong>Họ tên:</strong> {{$employee->full_name}}</p>
                        <p><strong>Ngày sinh:</strong> {{$employee->date_of_birth}}</p>
                        <p><strong>Giới tính:</strong> {{$employee->gender}}</p>
                        <p><strong>Số điện thoại:</strong> {{$employee->phone_number}}</p>
                        <p><strong>Email:</strong> {{$employee->email}}</p>
                        <p><strong>Địa chỉ:</strong> {{$employee->address}}</p>
                        <p><strong>Chức vụ:</strong> {{$employee->position}}</p>
                        <p><strong>Ngày bắt đầu làm:</strong> {{$employee->email}}</p>
                        <p><strong>Chức vụ:</strong> {{$employee->address}}</p>
                        <p><strong>Chi nhánh:</strong> {{$employee->branch->branch_name}}</p>
                    </div>
                    <div class="col-6">
                        <h4>Lương nhân viên</h4>
                        <p><strong>Lương cơ bản:</strong> {{number_format($employee->base_salary) .' đ' }}</p>
                        @if ($salary)
                            <p><strong>Phụ cấp:</strong> {{number_format($salary->allowance) .' đ' }}</p>
                            <p><strong>Thưởng:</strong> {{number_format($salary->bonus) .' đ' }}</p>
                            <p><strong>Khấu trừ:</strong> {{number_format($salary->deductions) .' đ' }}</p>
                            <p><strong>Ngày tạo lương:</strong> {{$salary->salary_date }}</p>
                            <p><strong>Mô tả:</strong> {{$salary->description }}</p>
                        @else
                            Lương thưởng của nhân viên này chưa có trong tháng này
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function (){
        $('#detailEmployeeModal').modal('show');
    })
</script>

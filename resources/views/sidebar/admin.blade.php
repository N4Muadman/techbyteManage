<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-mtext">Quản lý nhân sự</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item">
            <a class="pc-link" href="{{ route('employee.index.admin') }}"> Quản lý nhân viên</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('salary.index.admin') }}"> Quản lý tiền
                lương</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('branch.index.admin') }}">Quản lý chi
                nhánh</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('recruitment.index.admin') }}"> Quản lý
                tuyển
                dụng</a>
        </li>
    </ul>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-mtext">Quản lý hành chính</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item">
            <a class="pc-link" href="{{ route('leave.index.admin') }}"> Quản lý nghỉ phép</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('workschedule.index.admin') }}">Quản lý lịch làm
                việc</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('work_shift.index.admin') }}">Quản lý ca làm</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('evaluation.index.admin') }}">Đánh giá và khen
                thưởng</a>
        </li>
    </ul>
</li>
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-mtext">Khách hàng từ đâu</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>
    <ul class="pc-submenu">
        <li class="pc-item">
            <a class="pc-link" href="{{ route('newCustomer') }}"> Khách mới</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('customerStatistics') }}"> Thống kê</a>
        </li>
    </ul>
</li>
<li class="pc-item pc-hasmenu">
    <a class="pc-link" href="{{ route('businessCustomer') }}">Khách hàng doanh nghiệp</a>
</li>

<li class="pc-item pc-hasmenu">
    <a class="pc-link" href="{{ route('attendance.index.admin') }}">Quản lý chấm
        công</a>
</li>

<li class="pc-item pc-hasmenu">
    <a  class="pc-link" href="{{ route('hrtransfer.index.admin') }}">Điều động
        nhân sự</a>
</li>
<li class="pc-item pc-hasmenu">
    <a  class="pc-link" href="{{ route('complaint.index.admin') }}">Hỗ trợ / khiếu nại</a>
</li>

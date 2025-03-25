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

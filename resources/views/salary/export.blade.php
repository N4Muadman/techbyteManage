<table>
    <thead>
        <tr>
            <th>Họ tên</th>
            <th>Chi nhánh</th>
            <th>Chức vụ</th>
            <th>Lương căn bản</th>
            <th>Phụ cấp</th>
            <th>Thưởng</th>
            <th>Khấu trừ</th>
            <th>Thời gian tính lương</th>
            <th>Tổng lương</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salaries as $it)
            @php
                $total_salary = $it->base_salary + $it->allowance + $it->bonus - $it->deduction;
            @endphp
            <tr>
                <td>{{ $it->employee?->full_name }}</td>
                <td>{{ $it->employee?->branch?->branch_name }}</td>
                <td>{{ $it->employee?->position }}</td>
                <td>{{ number_format($it->base_salary) . ' đ' }}</td>
                <td>{{ number_format($it->allowance) . ' đ' }}</td>
                <td>{{ number_format($it->bonus) . ' đ' }}</td>
                <td>{{ number_format($it->deduction) . ' đ' }}</td>
                <td>{{ $it->salary_date }}</td>
                <td>{{ number_format($total_salary) . ' đ' }}</td>
            </tr>
        @endforeach

    </tbody>
</table>

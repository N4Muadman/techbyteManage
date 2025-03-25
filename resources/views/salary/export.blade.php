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
            <th>Tháng</th>
            <th>Tổng giờ làm</th>
            <th>Tổng lương theo giờ</th>
            <th>Tổng lương theo tháng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $it)
            @php
                $salaryOfEmployee = [];
                $totalSalary = 0;
                $totalSalaryOfmonth = 0;
                $totalHours = $it->total_working_hours;
                // Tách phần giờ
                $hours = floor($totalHours);
                // Tính phần phút
                $minutes = ($totalHours - $hours) * 60;
                $minutes = round($minutes);
                foreach($salary as $s){
                    if($it->employee_id == $s->employee_id && $it->month == $s->month){
                        $totalSalary = $it->total_working_hours * $s->base_salary;
                        $salaryOfEmployee = $s;
                        $totalSalaryOfmonth = $totalSalary + $s->allowance + $s->bonus - $s->deductions;
                        break;
                    }
                }
                $branch = '';
                $position = '';
                foreach($branches as $b){
                    if($it->employee_id == $b->id ){
                        $branch = $b->branch_name;
                        $position = $b->position;
                        break;
                    }
                }
            @endphp
            <tr>
                <td>{{ $it->full_name }}</td>
                <td>{{ $branch }}</td>
                <td>{{ $position }}</td>
                @if($salaryOfEmployee != null)
                    <td>{{ number_format($salaryOfEmployee->base_salary)  }} đ</td>
                    <td>{{ number_format($salaryOfEmployee->allowance) }} đ</td>
                    <td>{{ number_format($salaryOfEmployee->bonus) }} đ</td>
                    <td>{{ number_format($salaryOfEmployee->deductions) }} đ</td>
                @else
                    <td>0 đ</td>
                    <td>0 đ</td>
                    <td>0 đ</td>
                    <td>0 đ</td>
                @endif
                <td>{{ $it->month }}</td>
                <td>{{ $hours .'h '.$minutes .'phút' }}</td>
                <td>{{ number_format($totalSalary, 0, '.', ',') }} đ</td>
                <td>{{ number_format($totalSalaryOfmonth, 0, '.', ',') }} đ</td>
            </tr>
        @endforeach
    </tbody>
</table>

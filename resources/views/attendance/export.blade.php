<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Họ tên</th>
            <th>Chức vụ</th>
            <th>Chi nhánh</th>
            <th>Ngày</th>
            <th>Giờ vào</th>
            <th>Giờ ra</th>
            <th>Tổng số giờ</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        @php
            function formatHour($totalHours)
            {
                $hours = floor($totalHours);
                // Tính phần phút
                $minutes = ($totalHours - $hours) * 60;
                $minutes = round($minutes);
                return $hours . ' giờ ' . $minutes . ' phút';
            }
            function calculatehour($attendanceTime, $scheduleTime, $checkIn = false){
                $attendanceTime = \Carbon\Carbon::parse($attendanceTime);
                $scheduleTime = \Carbon\Carbon::parse($scheduleTime);
                $diffInMinutes = abs($attendanceTime->diffInMinutes($scheduleTime)) / 60;

                // Xác định xem nhân viên check-in sớm hay muộn
                if($checkIn){
                    if ($attendanceTime->greaterThan($scheduleTime)) {
                        return "<span>Muộn " . formatHour($diffInMinutes) . "</span>";
                    } else {
                        return "<span>Sớm " . formatHour($diffInMinutes) . "</span>";
                    }
                }else{
                    if ($attendanceTime->greaterThan($scheduleTime)) {
                        return "<span>Muộn " . formatHour($diffInMinutes) . "</span>";
                    } else {
                        return "<span>Sớm " . formatHour($diffInMinutes) . "</span>";
                    }
                }

            }

        @endphp
        @foreach ($attendance as $it)
            <tr>
                <td>{{ $it->id }}</td>
                <td>{{ $it->employee->full_name }}</td>
                <td>{{ $it->employee->position }}</td>
                <td>{{ $it->employee->branch->branch_name }}</td>
                <td>{{ $it->date }}</td>
                <td>{{ $it->check_in }}<br> {!! calculatehour($it->check_in,$it->work_schedule->work_shift->start_time, true) !!}</td>
                <td>{{ $it->check_out }}<br> {!! calculatehour($it->check_out,$it->work_schedule->work_shift->end_time, false) !!}</td>
                <td>{{ formatHour($it->working_hours) }}</td>
                @if ($it->status == 1)
                    <td>Đã được phê duyệt</td>
                @else
                    <td>Chưa được phê duyệt</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>


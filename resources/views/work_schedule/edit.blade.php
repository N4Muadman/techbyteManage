<div class="modal fade" id="editWork_scheduleModal" tabindex="-1" aria-labelledby="editWork_scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editWork_scheduleModalLabel">Chỉnh sửa lịch làm việc của nhân viên: {{ $work_schedule->employee->full_name }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('workschedule.update', $work_schedule->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="hoten" class="form-label">Ngày</label>
                        <input type="date" class="form-control" name="work_date"
                            value="{{ $work_schedule->date }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="hoten" class="form-label">Ca làm</label>
                        <select name="work_shift_id" id="" class="form-select">
                            @foreach ($work_shifts as $ws)
                                @if ($ws->id == $work_schedule->work_shift_id)
                                    <option selected value="{{ $ws->id }}">{{ $ws->name }}</option>
                                @else
                                    <option value="{{ $ws->id }}">{{ $ws->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Chỉnh sửa</button>
                </form>
            </div>
        </div>
    </div>
</div>

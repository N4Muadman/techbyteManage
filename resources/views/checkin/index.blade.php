@extends('layout')
@section('content')
    <div class="d-flex align-items-center justify-content-center" style="flex-direction: column; height: 100%">
        <form action="{{ route('checkin') }}" class="col-12" method="post">
            @csrf
            <div class="col-12 col-sm-12 d-flex justify-content-center align-items-center">
                <label class="form-label me-3">Chọn ca làm</label>
                <div class="me-3 col-4">
                    <select class="form-select" name="work_schedule" id="" required>
                        <option value="" selected>Chọn ca làm</option>
                        @foreach ($work_schedule as $it)
                            <option value="{{ $it->id }}">Ca: {{ $it->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success me-4" type="submit">Checkin</button>
            </div>
        </form>
        <div class="mt-5">
            <h3 class="text-success">Vui lòng chọn ca làm để checkin</h3>
        </div>
    </div>
@endsection

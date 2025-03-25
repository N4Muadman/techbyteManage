<?php

namespace App\View\Components;

use App\Models\attendance;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class CheckStatusCheckin extends Component
{
    public $employee_id;
    /**
     * Create a new component instance.
     */
    public function __construct($id)
    {
        $this->employee_id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $checkCheckin = attendance::where('employee_id', $this->employee_id)->whereNull('check_out')->orderBy('id', 'desc')->first();

        $status = $checkCheckin ? true : false;

        return view('components.check-status-checkin', compact('status'));
    }
}

<?php

namespace App\View\Components;

use App\Models\notification as Modelsnotification;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Notification extends Component
{
    public $user;
    /**
     * Create a new component instance.
     */
    public function __construct($user)
    {
        $this->user  = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $notification = Modelsnotification::query();

        if($this->user->role_id == 4){
            $notification = $notification->where('employee_id', $this->user->employee_id)->OrderBy('created_at', 'desc');
        }
        if($this->user->role_id == 3){
            $notification = $notification->where('branch_id', $this->user->employee->branch_id)->OrderBy('created_at', 'desc');
        }
        if($this->user->role_id == 2){
            $notification = $notification->where('role_id', $this->user->role_id)->OrderBy('created_at', 'desc');
        }
        if($this->user->role_id == 1){
            $notification = $notification->where('role_id', $this->user->role_id)->OrderBy('created_at', 'desc');
        }
        // dd($notification);
        return view('components.notification', compact('notification'));
    }
}

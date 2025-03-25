<?php

namespace App\View\Components;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Menu extends Component
{
    /**
     * Create a new component instance.
     */

    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $menus = Page::orderBy('arrange')->get();
        return view('components.menu', compact('menus'));
    }
}

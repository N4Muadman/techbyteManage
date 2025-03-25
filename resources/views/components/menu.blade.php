@if (Auth::user()->role_id == 1)
<li class="pc-item pc-hasmenu">
    <a href="#!" class="pc-link">
        <span class="pc-mtext">Quản lý phân quyền</span>
        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
    </a>

    <ul class="pc-submenu">
        <li class="pc-item">
            <a class="pc-link" href="{{ route('roles.index') }}"> Quản lý vai trò</a>
        </li>
        <li class="pc-item">
            <a class="pc-link" href="{{ route('permission.index') }}"> Quyền của từng vai trò</a>
        </li>
    </ul>
</li>
@endif
@foreach ($menus->where('level', 1) as $menu)
    @if (Auth::user()->hasPermissionOnPage('2', $menu->id))
        <li class="pc-item pc-hasmenu">
            <a href="{{ $menu->slug }}" class="pc-link">
                <span class="pc-mtext">{{ $menu->name }}</span>
                {!! $menus->where('parent', $menu->id)->count() > 0
                    ? '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>'
                    : '' !!}
            </a>
            <ul class="pc-submenu">
                @foreach ($menus->where('parent', $menu->id) as $submenu)
                    @if (Auth::user()->hasPermissionOnPage('2', $submenu->id))
                        <li class="pc-item">
                            <a class="pc-link" href="{{ $submenu->slug }}"> {{ $submenu->name }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endforeach

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

@php
    $menus = config('menus');
@endphp

@foreach ($menus as $menu)
    @php
        $hasAccessToAny = collect($menu['items'])->contains(function ($item) {
            return Auth::user()->canAccessPage($item['permission_ids'], config('pages.' .$item['page_config']));
        });
    @endphp

    @if ($hasAccessToAny)
        <li class="pc-item pc-hasmenu">
            <a href="{{ count($menu['items']) < 2 ? route($menu['items'][0]['route']) : '#!' }}" class="pc-link">
                <span class="pc-mtext">{{ $menu['title'] }}</span>
                {!! count($menu['items']) >= 2 ? '<span class="pc-arrow"><i data-feather="chevron-right"></i></span>' : '' !!}
            </a>
            @if (count($menu['items']) >= 2)
                <ul class="pc-submenu">
                    @foreach ($menu['items'] as $submenu)
                        @if (Auth::user()->canAccessPage($submenu['permission_ids'], config('pages.' .$submenu['page_config'])))
                            <li class="pc-item">
                                <a class="pc-link" href="{{ route($submenu['route']) }}"> {{ $submenu['name'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </li>
    @endif
@endforeach

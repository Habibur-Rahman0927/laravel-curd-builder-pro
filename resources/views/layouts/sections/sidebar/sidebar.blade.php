{{-- <nav id="sidebar" class="active">
    <div class="sidebar-header">
        <img src="{{ asset('assets/img/bootstraper-logo.png') }}" alt="bootraper logo" class="app-logo">
    </div>
    <ul class="nav flex-column">
        
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin-dashboard') }}"><i class="fas fa-tachometer-alt"></i><span
                    class="nav-text">Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#user"
                aria-expanded="false">
                <i class="fas fa-users"></i><span class="nav-text">User</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="user">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="{{ route('user.index') }}"> <i class="fas fa-circle small-icon"></i>List</a></li>
                    <li><a class="nav-link" href="{{ route('user.create') }}"> <i class="fas fa-circle small-icon"></i>Create</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#role"
                aria-expanded="false">
                <i class="fas fa-lock"></i><span class="nav-text">Role</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="role">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="{{ route('role.index') }}"> <i class="fas fa-circle small-icon"></i>List</a></li>
                    <li><a class="nav-link" href="{{ route('role.create') }}"> <i class="fas fa-circle small-icon"></i>Create</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#permissiongroup"
                aria-expanded="false">
                <i class="fas fa-folder"></i><span class="nav-text">Permission Group</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="permissiongroup">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="{{ route('permissiongroup.index') }}"> <i class="fas fa-circle small-icon"></i>List</a></li>
                    <li><a class="nav-link" href="{{ route('permissiongroup.create') }}"> <i class="fas fa-circle small-icon"></i>Create</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#permission"
                aria-expanded="false">
                <i class="fas fa-key"></i><span class="nav-text">Permission</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="permission">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="{{ route('permission.index') }}"> <i class="fas fa-circle small-icon"></i>List</a></li>
                    <li><a class="nav-link" href="{{ route('permission.create') }}"> <i class="fas fa-circle small-icon"></i>Create</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#roleHasPermission"
                aria-expanded="false">
                <i class="fas fa-gear"></i><span class="nav-text">Role Has Permission</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="roleHasPermission">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="{{ route('rolehaspermission.index') }}"> <i class="fas fa-circle small-icon"></i>List</a></li>
                    <li><a class="nav-link" href="{{ route('rolehaspermission.create') }}"> <i class="fas fa-circle small-icon"></i>Create</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('crud.generator.create') }}"><i class="fas fa-code"></i><span
                    class="nav-text">Generate CURD</span></a>
        </li>
    </ul>
</nav> --}}


<nav id="sidebar" class="active">
    <div class="sidebar-header">
        <img src="{{ asset('assets/img/bootstraper-logo.png') }}" alt="bootstrapper logo" class="app-logo">
    </div>
    <ul class="nav flex-column">
        @foreach ($menu['menu'] as $item)
            <li class="nav-item">
                @if (isset($item['nav-heading']))
                    {!! $item['nav-heading'] !!}
                @elseif (isset($item['submenu']))
                    @if (Auth::user()->hasAnyPermission($item['permission']))
                        @php
                            $isActive = in_array(Route::currentRouteName(), array_column($item['submenu'], 'url'));
                        @endphp
                        <a class="nav-link collapsed {{ $isActive ? 'active' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#{{ $item['slug'] }}" aria-expanded="false">
                            <i class="{{ $item['icon'] }}"></i>
                            <span class="nav-text">{{ $item['name'] }}</span>
                            <i class="fas fa-caret-right indicator ms-auto"></i>
                        </a>
                        <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $item['slug'] }}">
                            <ul class="list-unstyled ms-4">
                                @foreach ($item['submenu'] as $subitem)
                                    @if (Auth::user()->can($subitem['url']))
                                        <li>
                                            <a class="nav-link {{ Route::currentRouteName() === $subitem['url'] ? 'active-sub' : '' }}" href="{{ route($subitem['url']) }}">
                                                <i class="{{ $subitem['icon'] ?? 'fas fa-circle small-icon' }}"></i>
                                                <span>{{ $subitem['name'] }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @else
                    @if (Auth::user()->hasAnyPermission($item['permission']))
                        @if (Auth::user()->can($item['url']))
                            <a class="nav-link {{ Route::currentRouteName() === $item['url'] ? 'active' : 'collapsed' }}" href="{{ route($item['url']) }}">
                                <i class="{{ $item['icon'] }}"></i>
                                <span class="nav-text">{{ $item['name'] }}</span>
                            </a>
                        @endif
                    @endif
                @endif
            </li>
        @endforeach
    </ul>
</nav>

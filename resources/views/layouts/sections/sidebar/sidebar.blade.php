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

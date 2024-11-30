<nav class="navbar navbar-expand-lg nav-bg-color">
    <button type="button" id="sidebarCollapse" class="btn btn-light">
        <i class="fas fa-bars"></i><span></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav ms-auto">
            {{-- lang --}}
            <li class="nav-item dropdown">
                <div class="nav-dropdown">
                    <a href="#" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        {{-- @dd(app()->getLocale()) --}}
                        <i class="fas fa-globe"></i> <span>{{ \App\Models\Language::where('code', app()->getLocale())->value('name') ?? 'Language' }}</span> <i class="fas fa-caret-down" style="font-size: .8em;"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nav-link-menu" aria-labelledby="nav1">
                        <ul class="nav-list">
                            @foreach (\App\Models\Language::all() as $language)
                                <li>
                                    <a href="{{ route('lang.switch', $language->code) }}" class="dropdown-item">
                                    {{ $language->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            {{-- lang --}}
            <li class="nav-item dropdown">
                <div class="nav-dropdown">
                    <a href="#" id="nav2" class="nav-item nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> <span>{{ auth()->user()->name }}</span> <i style="font-size: .8em;" class="fas fa-caret-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end nav-link-menu">
                        <ul class="nav-list">
                            <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-address-card"></i> Profile</a></li>
                            <div class="dropdown-divider"></div>
                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
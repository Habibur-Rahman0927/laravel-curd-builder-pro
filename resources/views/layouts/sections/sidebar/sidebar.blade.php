<nav id="sidebar" class="active">
    <div class="sidebar-header">
        <img src="{{ asset('assets/img/bootstraper-logo.png') }}" alt="bootraper logo" class="app-logo">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin-dashboard') }}"><i class="fas fa-tachometer-alt"></i><span
                    class="nav-text">Dashboard</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.index') }}"><i class="fas fa-users"></i><span class="nav-text">Users</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#settingsMenu"
                aria-expanded="false">
                <i class="fas fa-cog"></i><span class="nav-text">Settings</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="settingsMenu">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="#">General</a></li>
                    <li><a class="nav-link" href="#">Privacy</a></li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#"><i class="fas fa-chart-line"></i><span
                    class="nav-text">Reports</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#messagesMenu"
                aria-expanded="false">
                <i class="fas fa-envelope"></i><span class="nav-text">Messages</span>
                <i class="fas fa-caret-right indicator ms-auto"></i>
            </a>
            <div class="collapse" id="messagesMenu">
                <ul class="list-unstyled ms-4">
                    <li><a class="nav-link" href="#">Inbox</a></li>
                    <li><a class="nav-link" href="#">Sent</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>

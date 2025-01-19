<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('any', 'home') }}" class="logo-dark">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm"/>
            <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark"/>
        </a>

        <a href="{{ route('any', 'home') }}" class="logo-light">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm"/>
            <img src="/images/logo-light.png" class="logo-lg" alt="logo light"/>
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="iconamoon:arrow-left-4-square-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
    <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title">Dashboard</li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <span class="nav-icon"><iconify-icon icon="iconamoon:home-duotone"></iconify-icon></span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <li class="menu-title">Mitarbeiter</li>
        <li class="nav-item">
            <a class="nav-link menu-arrow" href="#sidebarTeam" data-bs-toggle="collapse" role="button">
                <span class="nav-icon"><iconify-icon icon="iconamoon:profile-duotone"></iconify-icon></span>
                <span class="nav-text">Team</span>
            </a>
            <div class="collapse" id="sidebarTeam">
                <ul class="nav sub-navbar-nav">
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('users.index') }}">Übersicht</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('roles.index') }}">Rollen</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('training.index') }}">Training</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="menu-title">Verwaltung</li>
        <li class="nav-item">
            <a class="nav-link menu-arrow" href="#sidebarVerwaltung" data-bs-toggle="collapse" role="button">
                <span class="nav-icon"><iconify-icon icon="iconamoon:settings-duotone"></iconify-icon></span>
                <span class="nav-text">Verwaltung</span>
            </a>
            <div class="collapse" id="sidebarVerwaltung">
                <ul class="nav sub-navbar-nav">
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('admin.equipment.index') }}">Ausrüstung</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('admin.vehicles.index') }}">Fahrzeuge</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('vacation.index') }}">Urlaub</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="menu-title">Planung</li>
        <li class="nav-item">
            <a class="nav-link menu-arrow" href="#sidebarPlanung" data-bs-toggle="collapse" role="button">
                <span class="nav-icon"><iconify-icon icon="iconamoon:calendar-duotone"></iconify-icon></span>
                <span class="nav-text">Planung</span>
            </a>
            <div class="collapse" id="sidebarPlanung">
                <ul class="nav sub-navbar-nav">
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('eventplanung.index') }}">Eventplanung</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('anfragen.index') }}">Anfragen</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="menu-title">Finanzen</li>
        <li class="nav-item">
            <a class="nav-link menu-arrow" href="#sidebarFinance" data-bs-toggle="collapse" role="button">
                <span class="nav-icon"><iconify-icon icon="iconamoon:money-duotone"></iconify-icon></span>
                <span class="nav-text">Finanzen</span>
            </a>
            <div class="collapse" id="sidebarFinance">
                <ul class="nav sub-navbar-nav">
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('admin.finance.index') }}">Übersicht</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('admin.finance.salaries.index') }}">Gehälter</a>
                    </li>
                    <li class="sub-nav-item">
                        <a class="sub-nav-link" href="{{ route('admin.deckel.index') }}">Deckel</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
    </div>
</div>

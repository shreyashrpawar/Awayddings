<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
                <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="mdi mdi-office-building menu-icon"></i>
                <span class="menu-title">Property</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property.index') }}">Property</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property-vendors.index') }}">Property Vendors</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property-rate.index') }}">Property Rate</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('locations.index') }}">Locations</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property-vendors.index') }}">Hotel Facilities</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property-rate.index') }}">Room Inclusion</a></li>
                </ul>
            </div>
        </li>

    </ul>
</nav>

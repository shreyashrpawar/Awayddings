<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic">
                <i class="mdi mdi-office-building menu-icon"></i>
                <span class="menu-title">Property</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic" style="">
                <ul class="nav flex-column sub-menu">
                    @can('property show')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('property.index') }}">Property</a></li>
                    @endcan
                    @can('property_vendor show')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('vendors.index') }}">Property Vendors</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#booking">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Bookings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="booking" style="">
                <ul class="nav flex-column sub-menu">
                    @can('pre-booking show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('pre-bookings.index') }}">Pre Bookings</a></li>
                    @endcan
                    @can('booking show')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('bookings.index') }}">Bookings</a></li>
                    @endcan

                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#event_management_submit">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Event Management Bookings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="event_management_submit" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('event-pre-booking.index') }}">Pre Bookings</a></li>
                
                    <li class="nav-item"> <a class="nav-link" href="{{ route('event-bookings.index') }}">Bookings</a></li>
                    
                </ul>
            </div>
        </li>
        @can('user show')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Registered Users</span>
            </a>
        </li>
        @endcan
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#event_management">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Event Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="event_management" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('events.index') }}">Events</a></li>
                
                    <!-- <li class="nav-item"> <a class="nav-link" href="{{ route('timeslots.index') }}">Timeslots</a></li> -->
                    <!-- <li class="nav-item"> 
                        <a class="nav-link collapsed" data-toggle="collapse" href="#artist">
                            <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                            <span class="menu-title">Artist</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="artist" style="">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link" href="{{ route('artists.index') }}">Artists</a></li>
                            
                                <li class="nav-item"> <a class="nav-link" href="{{ route('artist_person') }}">Artist Person</a></li>

                            </ul>
                        </div>
                    </li> -->
                    <li class="nav-item"> <a class="nav-link" href="{{ route('artists.index') }}">Artists</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('artist_person') }}">Artist Person</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('decorations.index') }}">Decorations</a></li>
                    <!-- <li class="nav-item"> <a class="nav-link" href="{{-- route('lightandsounds.index') --}}">Light and Sounds</a></li> -->
                    <li class="nav-item"> <a class="nav-link" href="{{ route('addon_facilities.index') }}">Facilty</a></li>
                    

                </ul>
            </div>
        </li>

        @hasrole('admin|superAdmin')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('leads.index') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Ads Leads</span>
            </a>
        </li>
        @endrole
        @hasrole('admin|superAdmin')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic1">
                <ul class="nav flex-column sub-menu">
                    @can('location show')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('locations.index') }}">Locations</a></li>
                    @endcan
                    @can('amenities show')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('amenities.index') }}">Amenities</a></li>
                    @endcan
                    @can('room_inclusion show')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('room-inclusion.index') }}">Room Inclusion</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endrole
    </ul>
</nav>

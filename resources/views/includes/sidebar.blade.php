<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        @can('dashboard-read')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="mdi mdi-clipboard-text-outline menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        @endcan   
        @canAny(['property-read', 'property-vendors-read'])
        <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic">
            <i class="mdi mdi-office-building menu-icon"></i>
            <span class="menu-title">Property</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic" style="">
            <ul class="nav flex-column sub-menu">
                @can('property-read')
                <li class="nav-item"> <a class="nav-link" href="{{ route('property.index') }}">Property</a></li>
                @endcan
                @can('property-vendors-read')
                <li class="nav-item"> <a class="nav-link" href="{{ route('vendors.index') }}">Property Vendors</a></li>
                @endcan
            </ul>
        </div>
        </li>
        @endcan
@canAny(['Venue/Resort-Booking-Bookings-read', 'Venue/Resort-Booking-Pre-Bookings-read'])
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#booking">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Venue/ Resort bookings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="booking" style="">
                <ul class="nav flex-column sub-menu">
                    @can('Venue/Resort-Booking-Pre-Bookings-read')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('pre-bookings.index') }}">Pre Bookings</a></li>
                    @endcan
                    @can('Venue/Resort-Booking-Bookings-read')
                        <li class="nav-item"> <a class="nav-link" href="{{ route('bookings.index') }}">Bookings</a></li>
                    @endcan

                </ul>
            </div>
        </li>
    @endcan
    @canAny(['Wedding-Planning-Bookings-Pre-Bookings-read', 'Wedding-Planning-Bookings-Bookings-read'])
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#event_management_submit">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Wedding Planning bookings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="event_management_submit" style="">
                <ul class="nav flex-column sub-menu">
                @can('Wedding-Planning-Bookings-Pre-Bookings-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('event-pre-booking.index') }}">Pre Bookings</a></li>
                @endcan
                @can('Wedding-Planning-Bookings-Bookings-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('event-booking.index') }}">Bookings</a></li>
                @endcan
                </ul>
            </div>
        </li>
        @endcan
        
        @can('Registered-Users-read')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Registered Users</span>
            </a>
        </li>
        @endcan
        @canAny(['Event-Management-Events-read', 'Event-Management-Artists-read','Event-Management-Artist-Person-read','Event-Management-Decorations-read','Event-Management-Facility-read'])
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#event_management">
                <i class="mdi mdi-seat-individual-suite menu-icon"></i>
                <span class="menu-title">Event Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="event_management" style="">
                <ul class="nav flex-column sub-menu">
                @can('Event-Management-Events-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('events.index') }}">Events</a></li>
                @endcan
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
                    @can('Event-Management-Artists-read')  
                    <li class="nav-item"> <a class="nav-link" href="{{ route('artists.index') }}">Artists</a></li>
                    @endcan
                    @can('Event-Management-Artist-Person-read')  
                    <li class="nav-item"> <a class="nav-link" href="{{ route('artist_person') }}">Artist Person</a></li>
                    @endcan
                    @can('Event-Management-Decorations-read')  
                    <li class="nav-item"> <a class="nav-link" href="{{ route('decorations.index') }}">Decorations</a></li>
                    @endcan
                    <!-- <li class="nav-item"> <a class="nav-link" href="{{-- route('lightandsounds.index') --}}">Light and Sounds</a></li> -->
                    @can('Event-Management-Facility-read')  
                    <li class="nav-item"> <a class="nav-link" href="{{ route('addon_facilities.index') }}">Facilty</a></li>
                    @endcan


                </ul>
            </div>
        </li>
        @endcan
        @can('Ads-Leads-read')  
        <li class="nav-item">
            <a class="nav-link" href="{{ route('leads.index') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Ads Leads</span>
            </a>
        </li>
        @endcan
        @can('Lost-Leads-read') 
        <li class="nav-item">
            <a class="nav-link" href="{{ route('lost-leads') }}">
                <i class="mdi mdi-account-group-outline menu-icon"></i>
                <span class="menu-title">Lost Leads</span>
            </a>
        </li>
        @endcan
        @canAny(['Settings-Amenities-read', 'Settings-Locations-read','Settings-Room-Inclusion-read'])
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic1">
                <ul class="nav flex-column sub-menu">
                    @can('Settings-Locations-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('locations.index') }}">Locations</a></li>
                    @endcan
                    @can('Settings-Amenities-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('amenities.index') }}">Amenities</a></li>
                    @endcan
                    @can('Settings-Room-Inclusion-read')
                    <li class="nav-item"> <a class="nav-link" href="{{ route('room-inclusion.index') }}">Room Inclusion</a></li>
                    @endcan
                </ul>
            </div>
        </li>
        @endcan
        @hasrole('superAdmin')
        <li class="nav-item">
            <li class="nav-item">
            <a class="nav-link" href="{{ route('permissions.index') }}">
                <i class="mdi mdi-settings menu-icon"></i>
                <span class="menu-title">Permission Settings </span>
            </a>
        @endrole
        </li>
        </li>
      
    </ul>
</nav>

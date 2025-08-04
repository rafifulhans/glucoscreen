<x-dashboard.sidebar-link icon="ti ti-atom" link="{{ route(auth()->user()->role .'.dashboard') }}" title="Dashboard" />

@switch(auth()->user()->role)
    @case('admin')
        <x-dashboard.sidebar-link icon="ti ti-archive" link="{{ route('posyandu') }}" title="Posyandu" />
        <x-dashboard.sidebar-link icon="ti ti-user" link="{{ route('admin.pemimpin') }}" title="Pemimpin" />
        <x-dashboard.sidebar-link icon="ti ti-help" link="{{ route('informasi') }}" title="Informasi" />
        @break
    @case('pemimpin')
        <x-dashboard.sidebar-link icon="ti ti-users" link="{{ route('pemimpin.kader') }}" title="Kader" />
        <x-dashboard.sidebar-link icon="ti ti-map" link="{{ route('pemimpin.pengunjung') }}" title="Pengunjung" />
        @break
@endswitch
<x-dashboard>

    <div class="gs-profile-card mb-4">
        <div class="avatar">
            <i class="ti ti-building-hospital"></i>
        </div>
        <h1>{{ \App\Models\Posyandu::with('user')->where('user_id', auth()->user()->id)->first()->nama ?? '' }}</h1>
        <div class="role">Pemimpin Posyandu</div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon teal"><i class="ti ti-users"></i></div>
                </div>
                <div class="gs-stat-label">Total Kader</div>
                <div class="gs-stat-value">{{ $total_kader }}</div>
            </div>
        </div>
    </div>

</x-dashboard>
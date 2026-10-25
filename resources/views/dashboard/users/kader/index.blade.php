<x-dashboard>

    <div class="gs-welcome">
        <div class="subtitle">Ringkasan Data</div>
        <h2>Dashboard Kader</h2>
        <div class="caption">Pantau data pengunjung posyandu Anda</div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon teal"><i class="ti ti-map-pin"></i></div>
                </div>
                <div class="gs-stat-label">Posyandu</div>
                <div class="gs-stat-value">{{ optional($posyandu)->nama ?? '-' }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon blue"><i class="ti ti-user"></i></div>
                </div>
                <div class="gs-stat-label">Total Pengunjung</div>
                <div class="gs-stat-value">{{ $total_pengunjung }}</div>
            </div>
        </div>
    </div>

</x-dashboard>

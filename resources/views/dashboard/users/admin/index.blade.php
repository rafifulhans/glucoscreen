<x-dashboard>

    @error('judul_informasi')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    @error('isi_informasi')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror

    <div class="gs-welcome">
        <div class="subtitle">Ringkasan Data</div>
        <h2>Dashboard Admin</h2>
        <div class="caption">Pantau seluruh data kesehatan secara real-time</div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon teal"><i class="ti ti-user"></i></div>
                </div>
                <div class="gs-stat-label">Total Pemimpin</div>
                <div class="gs-stat-value">{{ $total_pemimpin }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon blue"><i class="ti ti-archive"></i></div>
                </div>
                <div class="gs-stat-label">Total Posyandu</div>
                <div class="gs-stat-value">{{ $total_posyandu }}</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="gs-stat-card">
                <div class="gs-stat-top">
                    <div class="gs-stat-icon violet"><i class="ti ti-users"></i></div>
                </div>
                <div class="gs-stat-label">Total Kader</div>
                <div class="gs-stat-value">{{ $total_kader }}</div>
            </div>
        </div>
    </div>

</x-dashboard>
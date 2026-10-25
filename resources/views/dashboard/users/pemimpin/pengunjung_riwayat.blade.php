<x-dashboard>

    <div class="gs-welcome">
        <div class="subtitle">Riwayat Data</div>
        <h2>Riwayat Pengunjung</h2>
        <div class="caption">Riwayat kunjungan dan tren GDS {{ $nama }}</div>
    </div>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('pemimpin.pengunjung') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3">Informasi Pengunjung</h5>
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-1"><strong>Nama:</strong> {{ $nama }}</p>
                    <p class="mb-1"><strong>NIK:</strong> {{ $nik }}</p>
                    <p class="mb-1"><strong>Alamat:</strong> {{ $alamat }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>RT/RW:</strong> {{ $rt ?? '-' }}/{{ $rw ?? '-' }}</p>
                    <p class="mb-1"><strong>GDS Terbaru:</strong> {{ $gds_terbaru }}</p>
                    <p class="mb-1"><strong>Trend GDS:</strong> {{ $gds_trend }}</p>
                    <p class="mb-1"><strong>Total Kunjungan:</strong> {{ $total_kunjungan }} kali</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Grafik GDS</h5>
            <div style="position: relative; height: 260px;">
                <canvas id="gdsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Riwayat Kunjungan</h5>
            <div class="table-responsive">
                <table class="table table-stripped mb-0 text-nowrap varient-table align-middle fs-3">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>GDS</th>
                            <th>Gejala Klasik</th>
                            <th>Kategori</th>
                            <th>Kader</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item['tanggal_formatted'] }}</td>
                                <td>{{ $item['gds'] }}</td>
                                <td>{{ $item['gejala_klasik'] }}</td>
                                <td>{{ $item['kategori'] }}</td>
                                <td>{{ $item['kader'] ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada riwayat kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;
        const ctx = document.getElementById('gdsChart');
        if (!ctx) return;
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'GDS',
                    data: @json($chartData),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.15)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    })();
</script>
@endsection

</x-dashboard>

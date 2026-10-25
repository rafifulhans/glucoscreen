<x-dashboard>

    <div class="gs-welcome">
        <div class="subtitle">Data Pengunjung</div>
        <h2>Data Pengunjung</h2>
        <div class="caption">Kelola dan lihat daftar pengunjung posyandu Anda</div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="mb-0">Daftar Pengunjung</h5>
                <form method="GET" action="{{ route('pemimpin.pengunjung') }}" id="searchForm" class="d-flex gap-2 align-items-center">
                    <input type="text" name="search" id="searchPengunjung" class="form-control" placeholder="Cari NIK atau Nama..." value="{{ $search ?? '' }}" style="max-width: 260px;">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-stripped mb-0 text-nowrap varient-table align-middle fs-3" id="pengunjungTable">
                    <thead>
                        <tr>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>RT/RW</th>
                            <th>Total Kunjungan</th>
                            <th>GDS Terbaru</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengunjungGroups as $group)
                            <tr class="pengunjung-row" data-nik="{{ $group['nik'] }}" data-nama="{{ $group['nama'] }}">
                                <td>{{ $group['nik'] }}</td>
                                <td>{{ $group['nama'] }}</td>
                                <td>{{ $group['alamat'] }}</td>
                                <td>{{ $group['rt'] ?? '-' }}/{{ $group['rw'] ?? '-' }}</td>
                                <td>{{ $group['total_kunjungan'] }} kali</td>
                                <td>{{ $group['gds_terbaru'] }}</td>
                                <td>
                                    <a href="{{ route('pemimpin.pengunjung.riwayat', $group['nik']) }}" class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye"></i> Riwayat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada data pengunjung.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $pengunjungGroups->links() }}
            </div>
        </div>
    </div>

@section('scripts')
<script>
    const searchInput = document.getElementById('searchPengunjung');
    let searchTimer = null;
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#pengunjungTable tbody tr.pengunjung-row').forEach(function (row) {
                const nik = (row.dataset.nik || '').toLowerCase();
                const nama = (row.dataset.nama || '').toLowerCase();
                row.style.display = (nik.includes(q) || nama.includes(q)) ? '' : 'none';
            });
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                document.getElementById('searchForm').submit();
            }, 500);
        });
    }
</script>
@endsection

</x-dashboard>

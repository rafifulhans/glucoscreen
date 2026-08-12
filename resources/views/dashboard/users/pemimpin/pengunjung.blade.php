<x-dashboard>

    <div class="container mt-5">
        <div class="table-responsive">
            <table class="table table-stripped mb-0 text-nowrap varient-table align-middle fs-3">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Alamat</th>
                        <th>Tanggal Kunjungan</th>
                        <th>GDS</th>
                        <th>Kader</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengunjungs as $pengunjung)
                        <tr>
                            <td>{{ $pengunjung->nama }}</td>
                            <td>{{ $pengunjung->nik }}</td>
                            <td>{{ $pengunjung->alamat }}</td>
                            <td>{{ date('j F Y', strtotime($pengunjung->tanggal_kunjungan)) }}</td>
                            <td>{{ $pengunjung->gds }}</td>
                            <td>{{ \App\Models\User::where('id', \App\Models\Kader::where('id', $pengunjung->kader_id)->first()->user_id)->first()->name }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-dashboard>
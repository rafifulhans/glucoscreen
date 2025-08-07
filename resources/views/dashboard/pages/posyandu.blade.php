<x-dashboard>

    <div class="container">
        <div class="alert alert-info">
            <b>info!</b>
            <span>1 Pemimpin hanya untuk 1 Posyandu</span>
        </div>
        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#daftarkanPosyanduModal">
                Daftarkan Posyandu
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-4">
                    <table class="table table-stripped mb-0 text-nowrap varient-table align-middle fs-3">
                        <thead>
                            <tr>
                                <th scope="col" class="text-start">Nama Posyandu</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Total Kader</th>
                                <th scope="col">Total Pengunjung</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posyandus as $posyandu)
                                <tr>
                                    <td>{{ $posyandu->nama }}</td>
                                    <td>{{ $posyandu->alamat }}</td>
                                    <td>{{ $posyandu->total_kader }}</td>
                                    <td>{{ $posyandu->total_pengunjung }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                            data-target="#editPosyanduModal{{ $posyandu->id }}">
                                            <i class="ti ti-pencil"></i>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                            data-target="#hapusPosyanduModal{{ $posyandu->id }}">
                                            <i class="ti ti-trash"></i>
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Edit Posyandu -->
                                <div class="modal fade" id="editPosyanduModal{{ $posyandu->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="editPosyanduModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editPosyanduModalLabel">Edit Posyandu</h5>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('posyandu.update', $posyandu->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group mb-3">
                                                        <label for="nama">Nama Posyandu</label>
                                                        <input type="text" class="form-control" id="nama" name="nama"
                                                            value="{{ $posyandu->nama }}" placeholder="Masukkan nama posyandu">
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="alamat">Alamat Posyandu</label>
                                                        <textarea name="alamat" rows="5" placeholder="Masukkan alamat posyandu"
                                                            class="form-control">{{ $posyandu->alamat }}</textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Hapus Posyandu -->
                                <div class="modal fade" id="hapusPosyanduModal{{ $posyandu->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="hapusPosyanduModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="hapusPosyanduModalLabel">Hapus Posyandu</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus posyandu {{ $posyandu->nama }}?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <form action="{{ route('posyandu.destroy', $posyandu->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Daftarkan Posyandu -->
        <div class="modal fade" id="daftarkanPosyanduModal" tabindex="-1" role="dialog"
            aria-labelledby="daftarkanPosyanduModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="daftarkanPosyanduModalLabel">Daftarkan Posyandu</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('posyandu.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="nama">Nama Posyandu</label>
                                <input type="text" class="form-control" id="nama" name="nama"
                                    placeholder="Masukkan nama posyandu">
                            </div>
                            <div class="form-group mb-3">
                                <label for="alamat">Alamat Posyandu</label>
                                <textarea name="alamat" rows="5" placeholder="Masukkan alamat posyandu"
                                    class="form-control"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Daftarkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</x-dashboard>
<x-dashboard>

    @section('style')
        <style>
            .readable_password {
                border: none;
                background-color: transparent;
            }
        </style>
    @endsection

    @if ($errors->all())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat kesalahan pada input data.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Halaman Kader -->
    <div class="container mt-5">
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahKaderModal">
                Tambah Kader
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kaders as $kader)
                    <tr>
                        <td>{{ $kader->name }}</td>
                        <td>{{ $kader->username }}</td>
                        <td>
                            <input type="password" class="readable_password" id="readble_password_{{ $kader->id }}"
                                value="{{ $kader->readable_password }}" readonly>
                            <span class="toggle-password">
                                <i class="ti ti-eye" aria-hidden="true"></i>
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editKaderModal{{ $kader->id }}">
                                <i class="ti ti-pencil"></i>  
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#hapusKaderModal{{ $kader->id }}">
                                <i class="ti ti-trash"></i>  
                                Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Edit Kader -->
                    <div class="modal fade" id="editKaderModal{{ $kader->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="editKaderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editKaderModalLabel">Edit Kader</h5>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('pemimpin.kader.update', $kader->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group mb-3">
                                            <label for="name">Nama</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $kader->name }}"
                                                placeholder="Masukkan nama">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="{{ $kader->username }}"
                                                placeholder="Masukkan username">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"placeholder="Masukkan password">
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus Kader -->
                    <div class="modal fade" id="hapusKaderModal{{ $kader->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="hapusKaderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="hapusKaderModalLabel">Hapus Kader</h5>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus kader {{ $kader->name }}?</p>
                                                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('pemimpin.kader.destroy', $kader->id) }}" method="POST">
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

    <!-- Modal Tambah Kader -->
    <div class="modal fade" id="tambahKaderModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahKaderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKaderModalLabel">Tambah Kader</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pemimpin.kader.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ old('name') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function () {
                $('.toggle-password').on('click', function () {
                    var input = $(this).prev('input');
                    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
                });
            });
        </script>
    @endsection
</x-dashboard>
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
    <!-- Halaman Pemimpin -->
    <div class="container mt-5">
        @if(count($posyandus) == 0)
            <div class="alert alert-warning">
                <b class="fs-4" style="color: #f0ad4e;">Perhatian!</b>
                <hr>
                <p>Anda belum menambahkan posyandu, silahkan tambahkan posyandu terlebih dahulu</p>
                <p class="fs-3 fw-bolder">
                    <span class="text-danger">*</span>
                    <i>1 pemimpin hanya boleh dimiliki oleh 1 posyandu</i>
                </p>
            </div>
        @endif
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambahPemimpinModal" {{ count($posyandus) == 0 ? 'disabled' : '' }}>
                Tambah Pemimpin
            </button>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Dari Posyandu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemimpins as $pemimpin)
                    <tr>
                        <td>{{ $pemimpin->name }}</td>
                        <td>{{ $pemimpin->username }}</td>
                        <td>
                            <input type="password" class="readable_password" id="readble_password_{{ $pemimpin->id }}"
                                value="{{ $pemimpin->readable_password }}" readonly>
                            <span class="toggle-password">
                                <i class="ti ti-eye" aria-hidden="true"></i>
                            </span>
                        </td>
                        <td>{{ \App\Models\Posyandu::with('user')->where('user_id', $pemimpin->id)->first()->nama ?? 'Posyandu tidak ditemukan' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Pemimpin -->
    <div class="modal fade" id="tambahPemimpinModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahPemimpinModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPemimpinModalLabel">Tambah Pemimpin</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.pemimpin.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                                placeholder="Masukkan nama">
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}"
                                placeholder="Masukkan username">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"placeholder="Masukkan password">
                        </div>
                        <div class="form-group mb-3">
                            <label for="posyandu_id">Posyandu</label>
                            <select class="form-control" id="posyandu_id" name="posyandu_id">
                                <option disabled selected>-- Pilih Posyandu --</option>
                                @foreach($posyandus as $p)
                                    @if(!$p->user_id)
                                        <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                    @endif
                                @endforeach
                            </select>
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
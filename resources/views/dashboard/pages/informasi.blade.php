<x-dashboard>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat kesalahan pada input data.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container my-5">
        <div class="d-flex justify-content-end align-items-center mb-3 gap-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahInformasiModal">
                Tambah Informasi
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                @if(count($informasis) == 0)
                    <p class="text-center text-muted">Belum ada informasi</p>
                @else
                    <div class="row bg-light rounded mb-4 text-dark">
                        <div class="col col-sm-1 col-md-2 col-lg-2">
                            <div class="text-center py-2">
                                <b>Judul</b>
                            </div>
                        </div>
                        <div class="col col-sm-11 col-md-10 col-lg-10">
                            <div class="text-center py-2">
                                <b>Isi</b>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('informasi.update') }}" method="post">
                        @method('PATCH')
                        @csrf
                        @foreach ($informasis as $informasi)
                            <div class="form-group row">
                                <div class="col col-sm-1 col-md-2 col-lg-2">
                                    <div class="d-flex flex-column justify-content-between h-100 align-items-end">
                                        <input type="text" class="form-control"
                                            value="{{ old('judul_informasi[' . $informasi->id . ']') ?? $informasi->judul }}"
                                            name="judul_informasi[{{ $informasi->id }}]">
                                        <!-- <span>
                                                            <form action="{{ route('informasi.destroy', $informasi->id) }}" method="post" class="form-delete">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-delete">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>
                                                            </form>
                                                        </span> -->
                                    </div>
                                </div>
                                <div class="col col-sm-11 col-md-10 col-lg-10">
                                    <textarea name="isi_informasi[{{ $informasi->id }}]" rows="12"
                                        class="form-control">{{ old('isi_informasi[' . $informasi->id . ']') ?? $informasi->isi }}</textarea>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                        <button class="btn btn-warning" type="submit">Update</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahInformasiModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahInformasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="tambahInformasiModalLabel">Tambah Informasi</h5>
                </div>
                <form action="{{ route('informasi.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for="judulInformasi">Judul Informasi</label>
                            <input type="text" class="form-control" id="judulInformasi" name="judul_informasi"
                                placeholder="Masukkan judul informasi">
                        </div>
                        <div class="form-group">
                            <label for="isiInformasi">Isi Informasi</label>
                            <textarea class="form-control" id="isiInformasi" name="isi_informasi" rows="5"
                                placeholder="Masukkan isi informasi"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- @section('scripts')
            <script>

                $(document).ready(function () {

                    $('.btn-delete').on('click', function (e) {
                        e.preventDefault();
                        var form = $(this).closest('.form-delete');
                        console.log(form)
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: "Data yang dihapus tidak dapat dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        })
                    });
                });
            </script>
        @endsection -->


</x-dashboard>
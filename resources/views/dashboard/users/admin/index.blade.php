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

    <div class="container my-5">
        <div class="row">
            <div class="col col-md-4 col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="card-title bg-primary text-white p-2 rounded">Total Pemimpin</h3>
                        <div class="card-text mt-4">
                            <h2 class="text-muted">{{ $total_pemimpin }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="card-title bg-secondary text-white p-2 rounded">Total Posyandu</h3>
                        <div class="card-text mt-4">
                            <h2 class="text-muted">{{ $total_posyandu }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="card-title bg-info text-white p-2 rounded">Total Kader</h3>
                        <div class="card-text mt-4">
                            <h2 class="text-muted">{{ $total_kader }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-dashboard>
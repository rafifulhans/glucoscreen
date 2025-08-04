<x-dashboard>

    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <div class="rounded-circle bg-light p-4">
                        <img src="{{ asset('assets/images/hospital.png') }}" width="100" alt="posyandu">
                    </div>
                    <hr>
                    <h1>{{ \App\Models\Posyandu::with('user')->where('user_id', auth()->user()->id)->first()->nama ?? ''  }}</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col col-md-4 col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="card-title bg-primary text-white p-2 rounded">Total Kader</h3>
                        <div class="card-text mt-4">
                            <h2 class="text-muted">{{ $total_kader }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-dashboard>
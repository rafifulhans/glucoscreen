<x-dashboard>

    @section('style')
        <style>
            .readable_password {
                border: none;
                background-color: transparent;
            }
            
            /* Mobile-friendly card layout */
            @media (max-width: 768px) {
                .kader-card {
                    margin-bottom: 1rem;
                    border: 1px solid var(--gs-border);
                    border-radius: 12px;
                    padding: 1rem;
                    background: white;
                }
                
                .kader-card-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.75rem;
                    padding-bottom: 0.75rem;
                    border-bottom: 1px solid var(--gs-border);
                }
                
                .kader-card-body {
                    margin-bottom: 0.75rem;
                }
                
                .kader-card-item {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 0.5rem;
                    padding: 0.5rem 0;
                }
                
                .kader-card-label {
                    font-weight: 600;
                    color: var(--gs-muted);
                    font-size: 0.875rem;
                }
                
                .kader-card-value {
                    color: var(--gs-text);
                    font-weight: 500;
                    text-align: right;
                }
                
                .kader-card-actions {
                    display: flex;
                    gap: 0.5rem;
                    padding-top: 0.75rem;
                    border-top: 1px solid var(--gs-border);
                }
                
                .kader-card-actions .btn {
                    flex: 1;
                }
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
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Data Kader</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKaderModal">
                <i class="ti ti-plus"></i> Tambah Kader
            </button>
        </div>

        <!-- Desktop Table View -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0 varient-table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kaders as $kader)
                            <tr>
                                <td>{{ $kader->name }}</td>
                                <td>{{ $kader->username }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="password" class="readable_password form-control-sm" 
                                            id="readble_password_{{ $kader->id }}"
                                            value="{{ $kader->readable_password }}" readonly style="width: 150px;">
                                        <span class="toggle-password" style="cursor: pointer;">
                                            <i class="ti ti-eye" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editKaderModal{{ $kader->id }}">
                                        <i class="ti ti-pencil"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusKaderModal{{ $kader->id }}">
                                        <i class="ti ti-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <p class="text-muted mb-0">Belum ada data kader</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="d-block d-md-none">
            @forelse($kaders as $kader)
                <div class="kader-card">
                    <div class="kader-card-header">
                        <h5 class="mb-0">{{ $kader->name }}</h5>
                        <div class="kader-card-actions">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editKaderModal{{ $kader->id }}">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hapusKaderModal{{ $kader->id }}">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="kader-card-body">
                        <div class="kader-card-item">
                            <span class="kader-card-label">Username</span>
                            <span class="kader-card-value">{{ $kader->username }}</span>
                        </div>
                        <div class="kader-card-item">
                            <span class="kader-card-label">Password</span>
                            <span class="kader-card-value">
                                <div class="d-flex align-items-center gap-2">
                                    <input type="password" class="readable_password form-control-sm" 
                                        id="readble_password_{{ $kader->id }}"
                                        value="{{ $kader->readable_password }}" readonly style="width: 120px; font-size: 0.875rem;">
                                    <span class="toggle-password" style="cursor: pointer;">
                                        <i class="ti ti-eye" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="ti ti-users" style="font-size: 3rem; color: var(--gs-muted);"></i>
                    <p class="text-muted mt-3 mb-0">Belum ada data kader</p>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $kaders->links() }}
        </div>
    </div>

    @foreach($kaders as $kader)
        <!-- Modal Edit Kader -->
        <div class="modal fade" id="editKaderModal{{ $kader->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editKaderModalLabel{{ $kader->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKaderModalLabel{{ $kader->id }}">Edit Kader</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pemimpin.kader.update', $kader->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label for="name_{{ $kader->id }}">Nama</label>
                                <input type="text" class="form-control" id="name_{{ $kader->id }}" name="name" 
                                    value="{{ $kader->name }}" placeholder="Masukkan nama" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="username_{{ $kader->id }}">Username</label>
                                <input type="text" class="form-control" id="username_{{ $kader->id }}" name="username" 
                                    value="{{ $kader->username }}" placeholder="Masukkan username" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="password_{{ $kader->id }}">Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                <input type="password" class="form-control" id="password_{{ $kader->id }}" name="password" 
                                    placeholder="Masukkan password baru">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hapus Kader -->
        <div class="modal fade" id="hapusKaderModal{{ $kader->id }}" tabindex="-1" role="dialog"
            aria-labelledby="hapusKaderModalLabel{{ $kader->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusKaderModalLabel{{ $kader->id }}">Hapus Kader</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kader <strong>{{ $kader->name }}</strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

    <!-- Modal Tambah Kader -->
    <div class="modal fade" id="tambahKaderModal" tabindex="-1" role="dialog"
        aria-labelledby="tambahKaderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKaderModalLabel">Tambah Kader</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('pemimpin.kader.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
                // Toggle password visibility with icon change
                $('.toggle-password').on('click', function () {
                    var input = $(this).prev('input');
                    input.attr('type', input.attr('type') === 'password' ? 'text' : 'password');
                    
                    // Toggle icon
                    var icon = $(this).find('i');
                    if (icon.hasClass('ti-eye')) {
                        icon.removeClass('ti-eye').addClass('ti-eye-off');
                    } else {
                        icon.removeClass('ti-eye-off').addClass('ti-eye');
                    }
                });

                // Clear password field when edit modal is hidden
                $('.modal').on('hidden.bs.modal', function () {
                    var passwordInput = $(this).find('input[name="password"]');
                    if (passwordInput.length > 0) {
                        passwordInput.val('');
                    }
                });
            });
        </script>
    @endsection
</x-dashboard>

<x-dashboard>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> Terdapat kesalahan pada input data.
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="gs-welcome">
        <div class="subtitle">Edukasi</div>
        <h2>Modul Edukasi GDS</h2>
        <div class="caption">Kelola materi edukasi untuk mobile berdasarkan kategori, GDS, dan gejala klasik</div>
    </div>

    @php
        $badge = [
            'normal'      => 'success',
            'prediabetes' => 'warning',
            'diabetes'    => 'danger',
        ];
    @endphp

    <!-- Tambah Modul -->
    @if (count($taken) >= count($kategori))
        <div class="alert alert-warning py-2"><i class="ti ti-alert-circle"></i> Semua kategori (Normal, Prediabetes, Diabetes) sudah memiliki modul. Maksimal 1 modul per kategori, jadi form tambah dinonaktifkan. Silakan edit atau hapus modul yang ada jika ingin mengubahnya.</div>
    @else
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-1">Tambah Modul Edukasi</h5>
            <p class="text-muted small mb-3">Isi detail modul dan tambahkan materi edukasi menggunakan editor teks. (Maksimal 1 modul per kategori)</p>

            <form id="formTambahModul" class="gs-modul-form" action="{{ route('modul.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label for="judul" class="form-label">Judul Modul</label>
                        <input type="text" class="form-control" id="judul" name="judul"
                            value="{{ old('judul') }}" placeholder="cth: Modul 1 (Kategori Normal)" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            @foreach ($kategori as $slug => $label)
                                @if (in_array($slug, $taken))
                                    <option value="{{ $slug }}" disabled>{{ $label }} (sudah ada)</option>
                                @else
                                    <option value="{{ $slug }}" @selected(old('kategori') === $slug)>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="gds" class="form-label">GDS (mg/dL)</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="gds" name="gds"
                            value="{{ old('gds') }}" placeholder="cth: 140" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="gejala_klasik" class="form-label">Gejala Klasik</label>
                        <select class="form-select" id="gejala_klasik" name="gejala_klasik" required>
                            <option value="0" @selected((string) old('gejala_klasik') === '0')>Tidak Ada</option>
                            <option value="1" @selected((string) old('gejala_klasik') === '1')>Ada</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="hasil_pemeriksaan" class="form-label">Hasil Pemeriksaan</label>
                        <textarea class="form-control" id="hasil_pemeriksaan" name="hasil_pemeriksaan" rows="2"
                            placeholder="cth: Kadar GDS < 126 mg/dL (Tanpa Gejala Klasik)" required>{{ old('hasil_pemeriksaan') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label for="pesan_utama" class="form-label">Pesan Utama</label>
                        <textarea class="form-control" id="pesan_utama" name="pesan_utama" rows="2"
                            placeholder="Pesan utama untuk pengguna..." required>{{ old('pesan_utama') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Isi Materi <small class="text-muted">(bisa ditambahkan beberapa materi)</small></label>
                        <div class="materi-container">
                            @include('dashboard.pages.materi-item', ['materi' => null])
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary add-materi mt-2">
                            <i class="ti ti-plus"></i> Tambah Materi
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> Simpan Modul</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Daftar Modul per Kategori -->
    <div class="row g-3">
        @foreach ($kelompok as $slug => $data)
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Modul {{ $data['label'] }}</h5>
                        <p class="text-muted small mb-3">Rule: {{ $slug === 'normal' ? 'GDS < 126 & tanpa gejala' : ($slug === 'prediabetes' ? 'GDS 126-199 atau gejala' : 'GDS >= 200 & bergejala') }}</p>

                        @forelse ($data['moduls'] as $m)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <strong>{{ $m->judul }}</strong>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editModul{{ $m->id }}"><i class="ti ti-edit"></i></button>
                                        <form action="{{ route('modul.destroy', $m->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus modul ini?');">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="small mt-1">
                                    <span class="badge bg-secondary">GDS: {{ $m->gds }} mg/dL</span>
                                    <span class="badge bg-secondary">Gejala: {{ $m->gejala_klasik ? 'Ada' : 'Tidak Ada' }}</span>
                                    <span class="badge bg-secondary">{{ $m->materis->count() }} materi</span>
                                </div>
                                <div class="small mt-2"><strong>Hasil Pemeriksaan:</strong></div>
                                <div class="small text-muted" style="white-space:pre-wrap;">{{ $m->hasil_pemeriksaan }}</div>
                                <div class="small alert alert-info p-2 mt-2 mb-0" style="white-space:pre-wrap;">{{ $m->pesan_utama }}</div>
                                @if ($m->materis->count())
                                    <div class="small mt-2 d-flex justify-content-between align-items-center">
                                        <strong>Isi Materi</strong>
                                        <span class="badge bg-light text-dark" data-materi-counter="materiCarousel{{ $m->id }}">1 / {{ $m->materis->count() }}</span>
                                    </div>
                                    <div id="materiCarousel{{ $m->id }}" class="carousel slide gs-materi-carousel mt-1" data-bs-ride="false">
                                        <div class="carousel-inner">
                                            @foreach ($m->materis as $mi)
                                                <div class="carousel-item @if ($loop->first) active @endif">
                                                    <div class="gs-materi-render gs-materi-slide">{!! $mi->isi !!}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($m->materis->count() > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($m->materis as $mi)
                                                    <button type="button" data-bs-target="#materiCarousel{{ $m->id }}"
                                                        data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"
                                                        @if ($loop->first) aria-current="true" @endif
                                                        aria-label="Slide {{ $loop->iteration }}"></button>
                                                @endforeach
                                            </div>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#materiCarousel{{ $m->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Sebelumnya</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#materiCarousel{{ $m->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Berikutnya</span>
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted small mb-0">Belum ada modul untuk kategori ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Edit per Modul -->
    @foreach ($kelompok as $data)
        @foreach ($data['moduls'] as $m)
            <div class="modal fade" id="editModul{{ $m->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Modul Edukasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <form class="gs-modul-form" action="{{ route('modul.update', $m->id) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <label class="form-label">Judul Modul</label>
                                        <input type="text" class="form-control" name="judul" value="{{ $m->judul }}" required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Kategori</label>
                                        <select class="form-select" name="kategori" required>
                                            @foreach ($kategori as $slug => $label)
                                                @if (in_array($slug, $taken) && $slug !== $m->kategori)
                                                    <option value="{{ $slug }}" disabled>{{ $label }} (dipakai modul lain)</option>
                                                @else
                                                    <option value="{{ $slug }}" @selected($m->kategori === $slug)>{{ $label }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label">GDS (mg/dL)</label>
                                        <input type="number" step="0.01" min="0" class="form-control" name="gds"
                                            value="{{ $m->gds }}" required>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label">Gejala Klasik</label>
                                        <select class="form-select" name="gejala_klasik" required>
                                            <option value="0" @selected(!$m->gejala_klasik)>Tidak Ada</option>
                                            <option value="1" @selected($m->gejala_klasik)>Ada</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Hasil Pemeriksaan</label>
                                        <textarea class="form-control" name="hasil_pemeriksaan" rows="2" required>{{ $m->hasil_pemeriksaan }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Pesan Utama</label>
                                        <textarea class="form-control" name="pesan_utama" rows="2" required>{{ $m->pesan_utama }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Isi Materi <small class="text-muted">(bisa ditambahkan beberapa materi)</small></label>
                                        <div class="materi-container">
                                            @forelse ($m->materis as $mi)
                                                @include('dashboard.pages.materi-item', ['materi' => $mi])
                                            @empty
                                                @include('dashboard.pages.materi-item', ['materi' => null])
                                            @endforelse
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary add-materi mt-2">
                                            <i class="ti ti-plus"></i> Tambah Materi
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    @section('scripts')
        <script>
            function gsMateriRowHTML() {
                return '<div class="materi-item">' +
                    '<div class="gs-materi-head"><span class="materi-num fw-semibold">Materi</span>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger remove-materi"><i class="ti ti-trash"></i> Hapus</button></div>' +
                    '<div class="gs-editor-toolbar">' +
                    '<button type="button" data-cmd="bold" title="Tebal"><b>B</b></button>' +
                    '<button type="button" data-cmd="italic" title="Miring"><em>I</em></button>' +
                    '<button type="button" data-cmd="underline" title="Garis bawah"><u>U</u></button>' +
                    '<span class="gs-tb-sep"></span>' +
                    '<button type="button" data-cmd="formatBlock" data-val="<h4>" title="Judul">H1</button>' +
                    '<button type="button" data-cmd="formatBlock" data-val="<h5>" title="Sub judul">H2</button>' +
                    '<button type="button" data-cmd="formatBlock" data-val="<p>" title="Paragraf">&#182;</button>' +
                    '<span class="gs-tb-sep"></span>' +
                    '<button type="button" data-cmd="insertUnorderedList" title="List"><i class="ti ti-list"></i></button>' +
                    '<button type="button" data-cmd="insertOrderedList" title="List nomor"><i class="ti ti-list-numbers"></i></button>' +
                    '<span class="gs-tb-sep"></span>' +
                    '<button type="button" data-cmd="removeFormat" title="Hapus format">Tx</button>' +
                    '</div>' +
                    '<div class="gs-editor" contenteditable="true" data-editor></div>' +
                    '<textarea name="materi_isi[]" hidden></textarea>' +
                    '</div>';
            }

            function gsRenumber(container) {
                container.querySelectorAll('.materi-num').forEach(function (el, i) {
                    el.textContent = 'Materi ' + (i + 1);
                });
            }

            function gsBindEditor(form) {
                if (form.dataset.editorReady) return;
                form.dataset.editorReady = '1';

                var container = form.querySelector('.materi-container');

                form.addEventListener('click', function (e) {
                    var add = e.target.closest('.add-materi');
                    if (add) {
                        e.preventDefault();
                        container.insertAdjacentHTML('beforeend', gsMateriRowHTML());
                        gsRenumber(container);
                        return;
                    }
                    var rm = e.target.closest('.remove-materi');
                    if (rm) {
                        e.preventDefault();
                        rm.closest('.materi-item').remove();
                        gsRenumber(container);
                    }
                });

                form.addEventListener('mousedown', function (e) {
                    var btn = e.target.closest('.gs-editor-toolbar button');
                    if (!btn) return;
                    e.preventDefault();
                    document.execCommand(btn.getAttribute('data-cmd'), false, btn.getAttribute('data-val') || null);
                });

                form.addEventListener('submit', function () {
                    container.querySelectorAll('.materi-item').forEach(function (item) {
                        var ed = item.querySelector('[data-editor]');
                        var ta = item.querySelector('textarea');
                        if (ed && ta) ta.value = ed.innerHTML;
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form.gs-modul-form').forEach(gsBindEditor);
            });

            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.gs-materi-carousel').forEach(function (carousel) {
                    var counter = document.querySelector('[data-materi-counter="' + carousel.id + '"]');
                    if (!counter) return;
                    carousel.addEventListener('slid.bs.carousel', function (e) {
                        var items = carousel.querySelectorAll('.carousel-item');
                        var total = items.length;
                        var idx = Array.prototype.indexOf.call(items, e.relatedTarget) + 1;
                        counter.textContent = idx + ' / ' + total;
                    });
                });
            });
        </script>
    @endsection

</x-dashboard>

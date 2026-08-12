<div class="materi-item">
    <div class="gs-materi-head">
        <span class="materi-num fw-semibold">Materi</span>
        <button type="button" class="btn btn-sm btn-outline-danger remove-materi">
            <i class="ti ti-trash"></i> Hapus
        </button>
    </div>
    <div class="gs-editor-toolbar">
        <button type="button" data-cmd="bold" title="Tebal"><b>B</b></button>
        <button type="button" data-cmd="italic" title="Miring"><em>I</em></button>
        <button type="button" data-cmd="underline" title="Garis bawah"><u>U</u></button>
        <span class="gs-tb-sep"></span>
        <button type="button" data-cmd="formatBlock" data-val="<h4>" title="Judul">H1</button>
        <button type="button" data-cmd="formatBlock" data-val="<h5>" title="Sub judul">H2</button>
        <button type="button" data-cmd="formatBlock" data-val="<p>" title="Paragraf">&#182;</button>
        <span class="gs-tb-sep"></span>
        <button type="button" data-cmd="insertUnorderedList" title="List"><i class="ti ti-list"></i></button>
        <button type="button" data-cmd="insertOrderedList" title="List nomor"><i class="ti ti-list-numbers"></i></button>
        <span class="gs-tb-sep"></span>
        <button type="button" data-cmd="removeFormat" title="Hapus format">Tx</button>
    </div>
    <div class="gs-editor" contenteditable="true" data-editor>{!! $materi->isi ?? '' !!}</div>
    <textarea name="materi_isi[]" hidden></textarea>
</div>

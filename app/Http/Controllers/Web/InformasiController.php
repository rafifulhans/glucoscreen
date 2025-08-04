<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Informasi;

class InformasiController extends Controller
{
    public function __invoke()
    {
        $informasis = Informasi::all();
        
        return view('dashboard.pages.informasi', [
            'informasis' => $informasis,
        ]);
    }

    public function store(Request $request) {
        
        $request->validate([
            'judul_informasi' => 'required|min:3|max:255',
            'isi_informasi' => 'required|min:3',
        ]);

        $informasi = new Informasi();
        $informasi->judul = $request->judul_informasi;
        $informasi->isi = $request->isi_informasi;
        $informasi->save();

        Alert::success('Berhasil', 'Informasi berhasil ditambahkan');

        return redirect()->back();
    }

    public function update(Request $request) {
        
        $validatedData = $request->validate([
            'judul_informasi.*' => 'required|string|min:5|max:255',
            'isi_informasi.*' => 'required|string|min:10',
        ]);

        $data = [];

        foreach ($validatedData['judul_informasi'] as $key => $value) 
        {
            $informasi = Informasi::find($key);
            $informasi->judul = $value;
            $informasi->isi = $validatedData['isi_informasi'][$key];
            $informasi->save();
        }

        Alert::success('Berhasil', 'Informasi berhasil diupdate');
        return redirect()->back();
    }

    public function destroy($id) {
        Informasi::find($id)->delete();
        Alert::success('Berhasil', 'Informasi berhasil dihapus');
        return redirect()->back();
    }
}

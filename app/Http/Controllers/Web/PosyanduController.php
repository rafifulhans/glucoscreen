<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Posyandu;
use App\Models\User;

class PosyanduController extends Controller
{
    public function __invoke()
    {
        $posyandus = Posyandu::orderByDesc('created_at')->get();

        return view('dashboard.pages.posyandu', [
            'posyandus' => $posyandus
        ]);
    }

    public function store(Request $request)
    {
        $posyandu = new Posyandu();
        $posyandu->nama = $request->input('nama');
        $posyandu->alamat = $request->input('alamat');
        $posyandu->save();

        Alert::success('Berhasil', 'Posyandu berhasil didaftarkan!');

        return redirect()->back();
    }
}

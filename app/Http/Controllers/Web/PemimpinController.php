<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kader;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Pengunjung;
use App\Models\Posyandu;

class PemimpinController extends Controller
{
    public function __invoke()
    {
        $total_kader = User::where('role', 'kader')->count();

        return view('dashboard.users.pemimpin.index', [
            'total_kader' => $total_kader
        ]);
    }

    public function kader() {
        $kaders = User::where('role', 'kader')->orderByDesc('created_at')->get();

        return view('dashboard.users.pemimpin.kader', [
            'kaders' => $kaders
        ]);
    }

    public function kader_store(Request $request) {

        $request->validate([
            'name'     => 'required|min:3',
            'username' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        $user_kader = new User;
        $user_kader->name = $request->name;
        $user_kader->username = $request->username;
        $user_kader->password = bcrypt($request->password);
        $user_kader->readable_password = $request->password;
        $user_kader->role = 'kader';
        $user_kader->save();

        $kader = new Kader;
        $kader->user_id = $user_kader->id;
        $kader->pemimpin_user_id = auth()->user()->id;
        $kader->save();

        $current_posyandu = Posyandu::where('user_id', auth()->user()->id)->first();
        
        Posyandu::where('id', $current_posyandu->id)->update([
            'total_kader' => $current_posyandu->total_kader + 1
        ]);

        Alert::success('Berhasil', 'Kader berhasil ditambahkan');
        return redirect()->back();
    }

    public function pengunjung() {
        $pengunjungs = Pengunjung::where('posyandu_id', Posyandu::where('user_id', auth()->user()->id)->first()->id)->orderbyDesc('created_at')->get();

        return view('dashboard.users.pemimpin.pengunjung', [
            'pengunjungs' => $pengunjungs
        ]);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\User;
use App\Models\Posyandu;
use App\Models\Kader;

class AdminController extends Controller
{
    public function __invoke() {

        $total_pemimpin = User::where('role', 'pemimpin')->count();
        $total_posyandu = Posyandu::count();
        $total_kader    = Kader::count();

        return view('dashboard/users/admin/index', [
            'total_pemimpin' => $total_pemimpin,
            'total_posyandu' => $total_posyandu,
            'total_kader'    => $total_kader
        ]);
    }

    public function pemimpin() {
        $pemimpins = User::where('role', 'pemimpin')->orderByDesc('id')->paginate(10);

        foreach($pemimpins as $pemimpin) {
            $pemimpin->posyandu = Posyandu::where('user_id', $pemimpin->id)->first();
        }

        $posyandus = Posyandu::where('user_id', null)->get();

        $is_posyandu_added = !empty(Posyandu::first()) ? true : false;

        return view('dashboard/users/admin/pemimpin', [
            'pemimpins' => $pemimpins,
            'posyandus' => $posyandus,
            'is_posyandu_added' => $is_posyandu_added
        ]);
    }

    public function pemimpin_store(Request $request)
    {
        $request->validate([
            'name'     => 'required|min:3',
            'username' => 'required|unique:users',
            'password' => 'required',
            'posyandu_id' => 'required'
        ]);

        $pemimpin = new User;
        $pemimpin->name = $request->name;
        $pemimpin->username = $request->username;
        $pemimpin->password = bcrypt($request->password);
        $pemimpin->readable_password = $request->password;
        $pemimpin->role = 'pemimpin';
        $pemimpin->save();

        $posyandu = Posyandu::find($request->posyandu_id);
        $posyandu->user_id = $pemimpin->id;
        $posyandu->save();

        Alert::success('Berhasil', 'Pemimpin berhasil didaftarkan!');
        return redirect()->back();
    }

    public function pemimpin_update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'required|min:3',
            'username' => 'required|unique:users,username,' . $id,
            'password' => 'nullable|min:8',
            'posyandu_id' => 'required'
        ]);

        $pemimpin = User::find($id);
        $pemimpin->name = $request->name;
        $pemimpin->username = $request->username;
        
        // Only update password if provided
        if ($request->filled('password')) {
            $pemimpin->password = bcrypt($request->password);
            $pemimpin->readable_password = $request->password;
        }
        
        $pemimpin->save();

        $old_posyandu = Posyandu::where('user_id', $id)->first();
        if ($old_posyandu) 
        {
            $old_posyandu->user_id = null;
            $old_posyandu->save();
        }

        $posyandu = Posyandu::find($request->posyandu_id);
        $posyandu->user_id = $pemimpin->id;
        $posyandu->save();

        Alert::success('Berhasil', 'Pemimpin berhasil diupdate!');
        return redirect()->back();
    }

    public function pemimpin_destroy($id)
    {
        $posyandu = Posyandu::where('user_id', $id)->first();
        if (!empty($posyandu)) 
        {
            $posyandu->user_id = null;
            $posyandu->save();   
        }

        $pemimpin = User::find($id);
        $pemimpin->delete();

        Alert::success('Berhasil', 'Pemimpin berhasil dihapus!');
        return redirect()->back();
    }
}

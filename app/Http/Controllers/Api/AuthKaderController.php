<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kader;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthKaderController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->username || !$request->password) {
            return response()->json(['message' => 'Mohon isi username dan password'], 401);
        }
        
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json(['message' => 'Username tidak ditemukan'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        if ($user->role !== 'kader') {
            return response()->json(['message' => 'User bukan kader'], 401);
        }
        
        $kader = Kader::with('user:id,name,username')
                        ->select('id', 'user_id', 'pemimpin_user_id')
                        ->where('user_id', $user->id)
                        ->first();

        $kader['name'] = $kader->user->name;
        $kader['username'] = $kader->user->username;

        unset($kader['user']);

        $token = $kader->createToken('kader-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'kader' => $kader
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }
}

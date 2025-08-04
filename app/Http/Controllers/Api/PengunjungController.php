<?php

// app/Http/Controllers/Api/PengunjungController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Posyandu;
use App\Models\Kader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengunjungController extends Controller
{
    public function index(){
        return response()->json([
            'data' => Pengunjung::where('kader_id', Kader::where('user_id', auth()->user()->user_id)->first()->id)->get()
            // 'data' => Pengunjung::with('kader', 'posyandu')->where('kader_id', Kader::where('user_id', auth()->user()->user_id)->first()->id)->get()
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:pengunjungs,nik',
            'alamat' => 'required|string',
            'tanggal_kunjungan' => 'required|date',
            'gds' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pengunjung = Pengunjung::create([
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'gds' => $request->gds,
            'kader_id' => Kader::where('user_id', auth()->user()->user_id)->first()->id,
            'posyandu_id' => Posyandu::with('user')->where('user_id', auth()->user()->pemimpin_user_id)->first()->id
        ]);

        return response()->json([
            'message' => 'Data pengunjung berhasil disimpan.',
            'data' => $pengunjung,
        ], 201);
    }
}

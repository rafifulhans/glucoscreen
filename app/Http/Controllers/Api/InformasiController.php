<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Informasi;

class InformasiController extends Controller
{
    public function index(){
        return response()->json([
            'data' => Informasi::all()
        ], 200);
    }
}

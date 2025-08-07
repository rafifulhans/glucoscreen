<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Kader;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException; // Import ini untuk menangani autentikasi

class PengunjungController extends Controller
{
    /**
     * Mengambil daftar tanggal kunjungan unik untuk kader yang terautentikasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Pastikan user terautentikasi sebelum mencoba mengakses auth()->user()
            if (!auth()->check()) {
                throw new AuthenticationException('Unauthenticated.');
            }

            $perPage = $request->input('per_page', 10);

            $kader = Kader::where('user_id', auth()->user()->user_id)->first();

            if (!$kader) {
                return response()->json(['message' => 'Kader tidak ditemukan untuk pengguna yang terautentikasi.'], 404);
            }
            
            $uniqueDatesPaginated = Pengunjung::where('kader_id', $kader->id)
                                             ->select('tanggal_kunjungan')
                                             ->distinct()
                                             ->orderByDesc('tanggal_kunjungan')
                                             ->paginate($perPage);

            $formattedDates = $uniqueDatesPaginated->getCollection()->map(function($item) {
                return Carbon::parse($item->tanggal_kunjungan)->format('Y-m-d');
            });

            return response()->json([
                'data' => [
                    'current_page' => $uniqueDatesPaginated->currentPage(),
                    'data' => $formattedDates,
                    'first_page_url' => $uniqueDatesPaginated->url(1),
                    'from' => $uniqueDatesPaginated->firstItem(),
                    'last_page' => $uniqueDatesPaginated->lastPage(),
                    'last_page_url' => $uniqueDatesPaginated->url($uniqueDatesPaginated->lastPage()),
                    'next_page_url' => $uniqueDatesPaginated->nextPageUrl(),
                    'path' => $uniqueDatesPaginated->path(),
                    'per_page' => $uniqueDatesPaginated->perPage(),
                    'prev_page_url' => $uniqueDatesPaginated->previousPageUrl(),
                    'to' => $uniqueDatesPaginated->lastItem(),
                    'total' => $uniqueDatesPaginated->total(),
                ]
            ], 200);
        } catch (AuthenticationException $e) {
            // Tangani pengecualian autentikasi dan kembalikan JSON 401
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            // Tangani pengecualian umum lainnya dan kembalikan JSON 500
            return response()->json([
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage() // Sertakan pesan error untuk debugging
            ], 500);
        }
    }

    /**
     * Mengambil detail pengunjung berdasarkan tanggal tertentu dengan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function showByDate(Request $request, $date)
    {
        try {
            // Pastikan user terautentikasi sebelum mencoba mengakses auth()->user()
            if (!auth()->check()) {
                throw new AuthenticationException('Unauthenticated.');
            }

            $kader = Kader::where('user_id', auth()->user()->user_id)->first();
        
            if (!$kader) {
                return response()->json(['message' => 'Kader tidak ditemukan untuk pengguna yang terautentikasi.'], 404);
            }
        
            if (!Carbon::hasFormat($date, 'Y-m-d')) {
                return response()->json(['message' => 'Format tanggal tidak valid. Gunakan format YYYY-MM-DD.'], 400);
            }

            $perPage = $request->input('per_page', 10);
        
            $pengunjungPaginated = Pengunjung::where('kader_id', $kader->id)
                                      ->whereDate('tanggal_kunjungan', $date)
                                      ->orderBy('created_at', 'asc')
                                      ->paginate($perPage);
        
            if ($pengunjungPaginated->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada data pengunjung untuk tanggal ini.',
                    'data' => []
                ], 404);
            }
        
            return response()->json($pengunjungPaginated, 200);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menyimpan data pengunjung baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Pastikan user terautentikasi sebelum mencoba mengakses auth()->user()
            if (!auth()->check()) {
                throw new AuthenticationException('Unauthenticated.');
            }

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'nik' => 'required|string',
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

            $kader = Kader::where('user_id', auth()->user()->user_id)->first();
            if (!$kader) {
                return response()->json(['message' => 'Kader tidak ditemukan untuk pengguna yang terautentikasi.'], 404);
            }

            $posyandu = Posyandu::with('user')->where('user_id', auth()->user()->pemimpin_user_id)->first();
            if (!$posyandu) {
                return response()->json(['message' => 'Posyandu tidak ditemukan untuk pemimpin pengguna yang terautentikasi.'], 404);
            }

            $pengunjung = Pengunjung::create([
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'nama' => $request->nama,
                'nik' => $request->nik,
                'alamat' => $request->alamat,
                'gds' => $request->gds,
                'kader_id' => $kader->id,
                'posyandu_id' => $posyandu->id
            ]);

            return response()->json([
                'message' => 'Data pengunjung berhasil disimpan.',
                'data' => $pengunjung,
            ], 201);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan server saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
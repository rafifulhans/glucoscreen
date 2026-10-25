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
        // Hanya tampilkan kader milik pemimpin yang sedang login
        $kaders = User::where('role', 'kader')
            ->whereHas('kader', function ($query) {
                $query->where('pemimpin_user_id', auth()->user()->id);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

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

    public function kader_update(Request $request, $id) {
        $request->validate([
            'name'     => 'required|min:3',
            'username' => 'required|unique:users,username,' . $id,
            'password' => 'nullable|min:8'
        ]);

        $kader = User::find($id);
        $kader->name = $request->name;
        $kader->username = $request->username;
        
        // Only update password if provided
        if ($request->filled('password')) {
            $kader->password = bcrypt($request->password);
            $kader->readable_password = $request->password;
        }
        
        $kader->save();

        Alert::success('Berhasil', 'Kader berhasil diupdate!');
        return redirect()->back();
    }

    public function kader_destroy($id) {
        $kader = User::find($id);
        $kader->delete();

        Alert::success('Berhasil', 'Kader berhasil dihapus!');
        return redirect()->back();
    }

    public function pengunjung(Request $request) {
        $posyandu = Posyandu::where('user_id', auth()->user()->id)->first();

        $search = trim((string) $request->get('search', ''));

        $groups = [];
        if ($posyandu) {
            $pengunjungs = Pengunjung::with('kader.user')
                ->where('posyandu_id', $posyandu->id)
                ->orderBy('tanggal_kunjungan')
                ->orderBy('created_at')
                ->get();

            $groups = $pengunjungs
                ->groupBy('nik')
                ->sortByDesc(function ($items) {
                    return $items->max('created_at');
                })
                ->map(function ($items, $nik) {
                    $first = $items->sortBy('created_at')->first();
                    $latest = $items->sortByDesc('created_at')->first();

                    $gdsAwal = $first->gds ?? 0;
                    $gdsLatest = $latest->gds ?? 0;
                    $trend = 'stabil';
                    if ($gdsAwal < $gdsLatest) {
                        $trend = 'naik';
                    } elseif ($gdsAwal > $gdsLatest) {
                        $trend = 'turun';
                    }

                    $history = $items->sortBy('tanggal_kunjungan')->values()->map(function ($p) {
                        return [
                            'id' => (int) $p->id,
                            'tanggal_kunjungan' => (string) $p->tanggal_kunjungan,
                            'tanggal_formatted' => \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d M Y'),
                            'gds' => (float) $p->gds,
                            'gejala_klasik' => $p->gejala_klasik,
                            'kategori' => $p->kategori,
                            'alamat' => $p->alamat,
                            'rt' => $p->rt,
                            'rw' => $p->rw,
                            'nama' => $p->nama,
                            'kader' => optional(optional($p->kader)->user)->name,
                        ];
                    })->toArray();

                    return [
                        'nik' => $nik,
                        'nama' => $latest->nama ?? $first->nama,
                        'alamat' => $latest->alamat ?? $first->alamat,
                        'rt' => $latest->rt ?? $first->rt,
                        'rw' => $latest->rw ?? $first->rw,
                        'total_kunjungan' => $items->count(),
                        'gds_terbaru' => $gdsLatest,
                        'gds_trend' => $trend,
                        'gds_history' => $history,
                        'kunjungan_list' => $history,
                    ];
                })
                ->values()
                ->toArray();
        }

        if ($search !== '') {
            $groups = array_values(array_filter($groups, function ($g) use ($search) {
                return stripos((string) $g['nik'], $search) !== false
                    || stripos((string) $g['nama'], $search) !== false;
            }));
        }

        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;
        $total = count($groups);
        $items = array_slice($groups, ($page - 1) * $perPage, $perPage);

        $pengunjungGroups = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('dashboard.users.pemimpin.pengunjung', [
            'pengunjungGroups' => $pengunjungGroups,
            'search' => $search,
            'posyandu' => $posyandu,
        ]);
    }

    public function pengunjungRiwayat($nik) {
        $posyandu = Posyandu::where('user_id', auth()->user()->id)->first();

        $visits = collect();
        if ($posyandu) {
            $visits = Pengunjung::with('kader.user')
                ->where('posyandu_id', $posyandu->id)
                ->where('nik', $nik)
                ->orderByDesc('tanggal_kunjungan')
                ->orderByDesc('created_at')
                ->get();
        }

        $first = $visits->sortBy('created_at')->first();
        $latest = $visits->sortByDesc('created_at')->first();
        $gdsAwal = $first->gds ?? 0;
        $gdsLatest = $latest->gds ?? 0;
        $trend = 'stabil';
        if ($gdsAwal < $gdsLatest) {
            $trend = 'naik';
        } elseif ($gdsAwal > $gdsLatest) {
            $trend = 'turun';
        }

        $history = $visits->map(function ($p) {
            return [
                'id' => (int) $p->id,
                'tanggal_kunjungan' => (string) $p->tanggal_kunjungan,
                'tanggal_formatted' => \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d M Y'),
                'gds' => (float) $p->gds,
                'gejala_klasik' => $p->gejala_klasik,
                'kategori' => $p->kategori,
                'alamat' => $p->alamat,
                'rt' => $p->rt,
                'rw' => $p->rw,
                'nama' => $p->nama,
                'kader' => optional(optional($p->kader)->user)->name,
            ];
        })->values()->toArray();

        $chartAsc = $visits->sortBy('tanggal_kunjungan')->values();
        $chartLabels = $chartAsc->map(function ($p) {
            return \Carbon\Carbon::parse($p->tanggal_kunjungan)->format('d M Y');
        })->toArray();
        $chartData = $chartAsc->map(function ($p) {
            return (float) $p->gds;
        })->toArray();

        return view('dashboard.users.pemimpin.pengunjung_riwayat', [
            'nik' => $nik,
            'nama' => $latest->nama ?? ($first->nama ?? '-'),
            'alamat' => $latest->alamat ?? ($first->alamat ?? '-'),
            'rt' => $latest->rt ?? ($first->rt ?? '-'),
            'rw' => $latest->rw ?? ($first->rw ?? '-'),
            'total_kunjungan' => $visits->count(),
            'gds_terbaru' => $gdsLatest,
            'gds_trend' => $trend,
            'history' => $history,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}

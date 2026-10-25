<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Kader;
use App\Models\Posyandu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KaderController extends Controller
{
    public function __invoke()
    {
        $posyandu = Posyandu::with('user')->where('user_id', auth()->user()->id)->first();

        $total_pengunjung = 0;
        $total_pengunjung_baru = 0;
        if ($posyandu) {
            $total_pengunjung = Pengunjung::where('posyandu_id', $posyandu->id)->count();
            $total_pengunjung_baru = Pengunjung::where('posyandu_id', $posyandu->id)
                ->select('nik')
                ->distinct()
                ->count();
        }

        return view('dashboard.users.kader.index', [
            'posyandu'   => $posyandu,
            'total_pengunjung' => $total_pengunjung,
            'total_pengunjung_baru' => $total_pengunjung_baru,
        ]);
    }

    public function pengunjung(Request $request)
    {
        $kader = Kader::where('user_id', auth()->user()->id)->first();

        $search = trim((string) $request->get('search', ''));

        $groups = [];
        if ($kader) {
            $pengunjungs = Pengunjung::where('kader_id', $kader->id)
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
                            'tanggal_formatted' => Carbon::parse($p->tanggal_kunjungan)->format('d M Y'),
                            'gds' => (float) $p->gds,
                            'gejala_klasik' => $p->gejala_klasik,
                            'kategori' => $p->kategori,
                            'alamat' => $p->alamat,
                            'rt' => $p->rt,
                            'rw' => $p->rw,
                            'nama' => $p->nama,
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

        $pengunjungGroups = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('dashboard.users.kader.pengunjung', [
            'pengunjungGroups' => $pengunjungGroups,
            'search' => $search,
            'kader' => $kader,
        ]);
    }

    public function pengunjungRiwayat($nik)
    {
        $kader = Kader::where('user_id', auth()->user()->id)->first();

        $visits = collect();
        if ($kader) {
            $visits = Pengunjung::where('kader_id', $kader->id)
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

        $historyDesc = $visits->map(function ($p) {
            return [
                'id' => (int) $p->id,
                'tanggal_kunjungan' => (string) $p->tanggal_kunjungan,
                'tanggal_formatted' => Carbon::parse($p->tanggal_kunjungan)->format('d M Y'),
                'gds' => (float) $p->gds,
                'gejala_klasik' => $p->gejala_klasik,
                'kategori' => $p->kategori,
                'alamat' => $p->alamat,
                'rt' => $p->rt,
                'rw' => $p->rw,
                'nama' => $p->nama,
            ];
        })->values()->toArray();

        $chartAsc = $visits->sortBy('tanggal_kunjungan')->values();
        $chartLabels = $chartAsc->map(function ($p) {
            return Carbon::parse($p->tanggal_kunjungan)->format('d M Y');
        })->toArray();
        $chartData = $chartAsc->map(function ($p) {
            return (float) $p->gds;
        })->toArray();

        return view('dashboard.users.kader.pengunjung_riwayat', [
            'nik' => $nik,
            'nama' => $latest->nama ?? ($first->nama ?? '-'),
            'alamat' => $latest->alamat ?? ($first->alamat ?? '-'),
            'rt' => $latest->rt ?? ($first->rt ?? '-'),
            'rw' => $latest->rw ?? ($first->rw ?? '-'),
            'total_kunjungan' => $visits->count(),
            'gds_terbaru' => $gdsLatest,
            'gds_trend' => $trend,
            'history' => $historyDesc,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}

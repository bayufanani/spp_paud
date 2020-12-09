<?php

namespace App\Http\Controllers;

use App\models\daful;
use App\Models\Keuangan;
use App\Models\Siswa;
use App\transaksi_daful;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiDafulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswa = Siswa::all();
        $data['siswa'] = $siswa;

        $transaksi = transaksi_daful::orderBy('created_at', 'desc')->paginate(10);
        $data['transaksi'] = $transaksi;

        return view('daful.index', $data);
    }

    public function getdaful($siswa)
    {
        $cicilan = transaksi_daful::where([
            'siswa_id' => $siswa,
            'cicilan' => 1
        ])->get();
        $murid = Siswa::find($siswa);
        $sudah_lunas = transaksi_daful::select('daful_id')->distinct('daful_id')->where('siswa_id', $siswa)->where('lunas', 1)->get();
        // return response()->json($sudah_lunas);
        $daftar_ulang = daful::whereNotIn('id', $sudah_lunas)->where('periode_id', $murid->kelas->periode->id)->get();
        $daftar_ulang->map(function ($item) use ($siswa) {
            $item_cicilan = transaksi_daful::where([
                'siswa_id' => $siswa,
                'cicilan' => 1,
                'daful_id' => $item->id
            ])->get();
            $jumlah_cicilan = $item_cicilan->sum('jumlah_bayar');
            $item['cicilan'] = $jumlah_cicilan;
            $item['jumlah'] = $item->jumlah - $jumlah_cicilan;
            return $item;
        });
        return response()->json($daftar_ulang);
    }

    public function simpan_daful(Request $req)
    {
        // ambil bayaran daftar ulang
        $daful = daful::find($req->daful_id);

        $transaksi_daful = new transaksi_daful;
        $jumlah_bayar = $req->tipe_bayar == 'cicilan' ? format_idr($req->bayar) : format_idr($daful->jumlah);
        $transaksi_daful->keterangan = "Daftar ulang di bayar dengan {$req->tipe_bayar} sebesar {$jumlah_bayar}, " . $req->keterangan;

        $transaksi_daful->jumlah_bayar = $req->bayar;
        $transaksi_daful->daful_id = $daful->id;
        $transaksi_daful->cicilan = 1;
        $transaksi_daful->lunas = 0;
        if ($req->tipe_bayar != 'cicilan') {
            $transaksi_daful->jumlah_bayar = $daful->jumlah;
            $transaksi_daful->daful_id = $daful->id;
            $transaksi_daful->cicilan = 0;
            $transaksi_daful->lunas = 1;
        }

        // cek jika sudah lunas
        $tr_sebelumnya = transaksi_daful::where([
            'siswa_id' => $req->siswa,
            'daful_id' => $daful->id
        ])->get();
        $sisa = $daful->jumlah - $tr_sebelumnya->sum('jumlah_bayar');
        if ($sisa == 0) {
            $transaksi_daful->lunas = 1; // lunaskan
        }

        $transaksi_daful->siswa_id = $req->siswa;
        $transaksi_daful->daful_id = $daful->id;
        $transaksi_daful->save();

        $data_siswa = Siswa::find($transaksi_daful->siswa_id);
        $now = Carbon::now();
        $kas = Keuangan::orderBy('created_at', 'desc')->first();
        $keuangan = new Keuangan;
        $keuangan->tipe = 'in';
        $keuangan->jumlah = $transaksi_daful->jumlah_bayar;
        $keuangan->total_kas = $transaksi_daful->jumlah_bayar + $kas->total_kas;
        $keuangan->keterangan = "Pembayaran daftar ulang oleh {$data_siswa->nama}dibayarkan pada {$now} secara {$req->tipe_bayar}";
        $keuangan->daful_id = $transaksi_daful->id;
        $keuangan->save();

        return response()->json(['msg' => 'berhasil disimpan']);
    }

    public function print(Request $request)
    {
        // dd($request->ids);
        $items = transaksi_daful::whereIn('id', explode(',', $request->ids))->get();
        // dd($items);
        return view('daful.print', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

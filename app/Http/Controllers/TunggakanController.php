<?php

namespace App\Http\Controllers;

use App\models\daful;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Transaksi;
use App\Models\transaksi_daful;
use Illuminate\Http\Request;

class TunggakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswa = Siswa::paginate(15);
        $data['siswa'] = $siswa;
        $data['tagihan'] = Tagihan::orderBy('created_at', 'desc')->get();
        return view('tunggakan.index', $data);
    }

    public function tunggakan($id_tagihan)
    {
        $siswa = "";
        if ($id_tagihan == 'daful') {
            $tunggakan = Siswa::get()->where('is_daful_lunas', false);
            // cek lagi untuk periode yang sama
            $i = 0;
            foreach ($tunggakan as $item) {
                ++$i;
                $siswa .= "
                    <tr>
                        <td>{$i}</td>
                        <td>{$item->nama}</td>
                        <td>Daftar Ulang</td>
                    </tr>";
            }
            return $siswa;
        }

        $all_siswa = Siswa::all();
        $i = 0;
        foreach ($all_siswa as $item_siswa) {
            // cek pembayaran bulanan setiap siswa
            $periode = $item_siswa->kelas->periode;
            $bulans = bulan_interval_index($periode->tgl_mulai, $periode->tgl_selesai);

            // cek bulan yg bayar
            $transaksi_spp = Transaksi::where([
                'tagihan_id' => $id_tagihan,
                'siswa_id' => $item_siswa->id
            ])->get();

            foreach ($transaksi_spp as $transaksi) {
                // unset bulan yg ada di transaksi
                if (isset($bulans[$transaksi->bulan])) {
                    unset($bulans[$transaksi->bulan]);
                }
            }

            $str_bulans = join(', ', $bulans);

            if (count($bulans) > 0) {
                ++$i;
                $siswa .= "
                <tr>
                    <td>{$i}</td>
                    <td>{$item_siswa->nama}</td>
                    <td>Pembayaran kurang di bulan: {$str_bulans}</td>
                </tr>";
            }
        }
        return $siswa;
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

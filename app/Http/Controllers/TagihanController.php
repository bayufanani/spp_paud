<?php

namespace App\Http\Controllers;

use App\models\daful;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Role;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Redirect;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tagihan = Tagihan::orderBy('created_at', 'desc')->paginate(10);
        $daful = daful::orderBy('created_at', 'desc')->paginate(10);
        return view('tagihan.index', ['tagihan' => $tagihan, 'daful' => $daful]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        $siswa = Siswa::where('is_yatim', '!=', '1')->get();
        return view('tagihan.form', [
            'kelas' => $kelas,
            'siswa' => $siswa
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'jumlah' => 'required|numeric',
            'peserta' => 'required|numeric'
        ]);

        $tagihan = Tagihan::make($request->except('kelas_id'));

        switch ($request->peserta) {
            case 1: // semua
                $tagihan->wajib_semua = 1;
                break;
            case 2: // hanya kelas
                $tagihan->kelas_id = $request->kelas_id;
                break;
            case 3: // siswa , make role
                $tagihan->save();
                foreach ($request->siswa_id as $siswa_id) {
                    $tagihan->siswa()->save(Siswa::find($siswa_id));
                }
                break;
            default:
                return Redirect::back()->withErrors(['Peserta Wajib diisi']);
        }

        if ($tagihan->save()) {
            return redirect()->route('tagihan.index')->with([
                'type' => 'success',
                'msg' => 'Item Tagihan ditambahkan'
            ]);
        } else {
            return redirect()->route('tagihan.index')->with([
                'type' => 'danger',
                'msg' => 'Err.., Terjadi Kesalahan'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Tagihan $tagihan)
    {
        $kelas = Kelas::all();
        $siswa = Siswa::where('is_yatim', '!=', '1')->get();
        return view('tagihan.form', [
            'kelas' => $kelas,
            'siswa' => $siswa,
            'tagihan' => $tagihan
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'jumlah' => 'required|numeric',
            'peserta' => 'required|numeric'
        ]);

        $tagihan->fill($request->except('kelas_id'));

        //remove all related
        $tagihan->siswa()->detach();
        $tagihan->kelas_id = null;
        $tagihan->wajib_semua = null;

        switch ($request->peserta) {
            case 1: // semua
                $tagihan->wajib_semua = 1;
                break;
            case 2: // hanya kelas
                $tagihan->kelas_id = $request->kelas_id;
                break;
            case 3: // siswa , make role
                foreach ($request->siswa_id as $siswa_id) {
                    $tagihan->siswa()->save(Siswa::find($siswa_id));
                }
                break;
            default:
                return Redirect::back()->withErrors(['Peserta Wajib diisi']);
        }

        if ($tagihan->save()) {
            return redirect()->route('tagihan.index')->with([
                'type' => 'success',
                'msg' => 'Item Tagihan diubah'
            ]);
        } else {
            return redirect()->route('tagihan.index')->with([
                'type' => 'danger',
                'msg' => 'Err.., Terjadi Kesalahan'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tagihan $tagihan)
    {
        if ($tagihan->transaksi->count() != 0) {
            return redirect()->route('tagihan.index')->with([
                'type' => 'danger',
                'msg' => 'tidak dapat menghapus tagihan yang masih memiliki transaksi'
            ]);
        }
        $tagihan->siswa()->detach();
        if ($tagihan->delete()) {
            return redirect()->route('tagihan.index')->with([
                'type' => 'success',
                'msg' => 'tagihan telah dihapus'
            ]);
        }
    }

    public function tagihanSiswa($tagihan, $siswa)
    {
        $data = [];
        $data['tagihan'] = Tagihan::find($tagihan);
        $data['siswa'] = Siswa::find($siswa);
        $periode = $data['siswa']->kelas->periode;
        $data['bulans'] = bulan_interval($periode->tgl_mulai, $periode->tgl_selesai);
        $bulan_dibayar = Transaksi::where([
            'tagihan_id' => $data['tagihan']->id,
            'siswa_id' => $data['siswa']->id
        ])->get()->pluck('bulan');

        // dd(array_search(2, $bulan_dibayar->toArray()));

        //hapus data yg sudah ada di transaksi
        foreach ($data['bulans'] as $key => $bln) {
            if (array_search($bln['index'], $bulan_dibayar->toArray()) !== false) {
                $data['bulans'][$key]['lunas'] = true;
            }
        }

        return view('tagihan.siswa', $data);
    }
}

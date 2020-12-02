<?php

namespace App\Http\Controllers;

use App\models\daful;
use App\Models\Siswa;
use App\transaksi_daful;
use Illuminate\Http\Request;

class DafulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('daful.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $daful_baru = new daful;
        $daful_baru->keterangan = $request->keterangan;
        $daful_baru->jumlah = $request->jumlah;
        $daful_baru->save();
        return redirect()->route('tagihan.index')->with([
            'type' => 'success',
            'daful' => true,
            'msg' => 'Tagihan Daftar Ulang disimpan'
        ]);
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
        return view('daful.create', ['tagihan' => daful::findOrFail($id)]);
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
        $daful_baru = daful::find($id);
        $daful_baru->keterangan = $request->keterangan;
        $daful_baru->jumlah = $request->jumlah;
        $daful_baru->save();
        return redirect()->route('tagihan.index')->with([
            'type' => 'success',
            'daful' => true,
            'msg' => 'Tagihan Daftar Ulang disimpan'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        daful::find($id)->delete();
        return redirect()->route('tagihan.index')->with([
            'type' => 'success',
            'daful' => true,
            'msg' => 'Tagihan Daftar Ulang dihapus'
        ]);
    }
}

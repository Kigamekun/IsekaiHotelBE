<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tipe;
class TipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tipe', [
            'data' => Tipe::all()
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
        $this->validate($request, [
            'nama' => 'required',
        ]);
            Tipe::create([
                'nama' => $request->nama,
            ]);


        return redirect()->route('admin.tipe.index')->with(['message'=>'Tipe berhasil di tambah !','status'=>'success']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\kamar  $kamar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Tipe::where('id', $id)
        ->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.tipe.index')->with(['message'=>'Tipe Berhasil di Update!','status'=>'warning']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\kamar  $kamar
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Tipe::destroy($id);
        return redirect()->route('admin.tipe.index')->with(['message'=>'Tipe Berhasil di delete','status'=>'danger']);
    }
}

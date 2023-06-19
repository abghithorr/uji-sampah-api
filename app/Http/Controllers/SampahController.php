<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Exception;

class SampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $sampahs = Sampahs::all();
        $search = $request->search_kepala_keluarga;
        // ambil data dari key limit bagian params nya postman
        $limit = $request->limit;
        // ambil semua data melalui model
        $sampah = Sampah::where('kepala_keluarga', 'LIKE', '%' . $search . '%')->limit($limit)->get();

        if ($sampah) {
            return ApiFormatter::createApi(200, 'success', $sampah);
        }else {
            return ApiFormatter::createApi(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kepala_keluarga' => 'required',
                'no_rumah' => 'required',
                'rt_rw' => 'required',
                'total_karung_sampah' => 'required',
                'tanggal_pengangkutan' => 'required',
            ]);
            if ($request->total_karung_sampah > 3) {
                $kriteria = "collapse";
            }else {
                $kriteria = "standar";
            }
            $sampah = Sampah::create([
                'kepala_keluarga' => $request->kepala_keluarga,
                'no_rumah' => $request->no_rumah,
                'rt_rw' => $request->rt_rw,
                'total_karung_sampah' => $request->total_karung_sampah,
                'kriteria' => $kriteria,
                'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
            ]);

            $getDataSaved = Sampah::where('id', $sampah->id)->first();

            if ($getDataSaved) {
                return ApiFormatter::createApi(200, 'success', $getDataSaved);
            }else {
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'failed', $error);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sampah = Sampah::find($id);
            if ($sampah) {
                return ApiFormatter::createApi(200, 'success', $sampah);
            }else {
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sampah $sampah)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kepala_keluarga' => 'required',
                'no_rumah' => 'required',
                'rt_rw' => 'required',
                'total_karung_sampah' => 'required',
                'tanggal_pengangkutan' => 'required',
            ]);
            if ($request->total_karung_sampah > 3) {
                $kriteria = "collapse";
            }else {
                $kriteria = "standar";
            } 
            $sampah = Sampah::find($id);
            $sampah->update([
                'kepala_keluarga' => $request->kepala_keluarga,
                'no_rumah' => $request->no_rumah,
                'rt_rw' => $request->rt_rw,
                'total_karung_sampah' => $request->total_karung_sampah,
                'kriteria' => $kriteria,
                'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
            ]);

            $updateData = Sampah::where('id', $sampah->id)->first();
            if ($updateData) {
                return ApiFormatter::createApi(200, 'success', $updateData);
            }else {
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sampah = Sampah::findOrFail($id);
            $proses = $sampah->delete();
            if ($proses) {
                return ApiFormatter::createApi(200, 'success delete data');
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }

    public function trash()
    {
        try {
            $sampah = Sampah::onlyTrashed()->get();
            if ($sampah) {
                return ApiFormatter::createApi(200, 'success', $sampah);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $sampah = Sampah::onlyTrashed()->where('id', $id);
            $sampah->restore();
            $dataRestore = Sampah::where('id', $id)->first();
            if ($dataRestore) {
                return ApiFormatter::createApi(200, 'success', $dataRestore);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $sampah = Sampah::onlyTrashed()->where('id', $id);
            $proses = $sampah->forceDelete();
            if ($proses) {
                return ApiFormatter::createApi(200, 'success delete data', 'Data dihapus permanen!');
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }
}

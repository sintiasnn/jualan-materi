<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\PaketMateri;
use App\Models\PaketList;
use App\Models\Submateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaketContentController extends Controller
{
    public function index(){
        return view('livewire.pages.tutor.paket');
    }

    public function show(){
        //
    }

    public function create($id){
        $materi = PaketList::find($id);
        foreach($materi->materi as $materi_item){
            $materi_item->submateri = Submateri::where('materi_id', $materi_item->id)->select('kode_submateri','nama_submateri')->get();
        }
        return view('livewire.pages.tutor.paket-form', [
            'arrayMateri' => $materi->materi,
            'namaPaket' => $materi->nama_paket,
            'id' =>$id
        ]);
    }

    public function store(Request $request, $id){
        $materi_id = $request->materi_id;

        try {
            foreach ($materi_id as $materi) {
                $data[] = [
                    'materi_id' => $materi,
                    'paket_id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::beginTransaction();
            $paketContent = new PaketMateri();
            if(!is_null($materi_id) && $paketContent->insert($data)){
                DB::commit();
                return redirect()->route('tutor.paket.materi.create',$id)->with('message', 'materi berhasil ditambahkan');
            }
            else {
                DB::rollBack();
                return redirect()->route('tutor.paket.materi.create',$id)->with('error', 'Error ketika menyimpan data');
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('tutor.paket.materi.create', $id)->with('error-message', $e->getMessage());
        }
    }

    public function destroy($id){
        try{
            DB::beginTransaction();
            PaketMateri::find($id)->delete();
            DB::commit();
            return response()->json(['message' => 'Materi berhasil dihapus dari paket', 'success' => true]);
        } catch (\Exception $e){
            DB::rollBack();
            return response('Hapus materi dari paket gagal', 500);
        }
    }

    public function materi(Request $request){

        try {
            $query  = Materi::select('id','subdomain_id','kode_materi','nama_materi');
            if($request->filled('subdomain')){
                $query->where('subdomain_id', $request->subdomain);
            }
            if($request->filled('domain')){
                $query->whereHas('subdomain', function($q) use($request){
                    $q->where('domain_code', $request->domain);
                });
            }
            $data = $query->get();
            if($request->filled('paket')){
                foreach($data as $materi){
                    $paket = PaketMateri::where('paket_id', $request->paket)->where('materi_id', $materi->id);
                    $materi->is_selected = $paket->exists();
                    $materi->paket_materi_id = $paket->pluck('id')[0] ?? null;
                    $materi->submateri = Submateri::where('materi_id', $materi->id)->select('kode_submateri','nama_submateri')->get();
                }
            }
            return $data;

        } catch (\Exception $e) {
            \Log::error('Fetch Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while fetching data'
            ], 500);
        }


    }
}

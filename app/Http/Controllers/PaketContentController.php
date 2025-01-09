<?php

namespace App\Http\Controllers;

use App\Models\ClassContent;
use App\Models\PaketContent;
use App\Models\PaketList;
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
        return view('livewire.pages.tutor.paket-form', [
            'arrayMateri' => $this->restructureMateri($materi->materi),
            'namaPaket' => $materi->nama_paket,
            'id' =>$id
        ]);
    }

    public function store(Request $request, $id){
        $content_id = $request->content_id;
        try {
            foreach ($content_id as $content) {
                $data[] = [
                    'content_id' => $content,
                    'paket_id' => $id,
                    'activation_date' => now(),
                    'expired_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::beginTransaction();
            $paketContent = new PaketContent();
            if(is_null($content_id) && $paketContent->insert($data)){
                DB::commit();
                return redirect()->route('tutor.paket.materi')->with('message', 'materi berhasil ditambahkan');
            }
            else {
                DB::rollBack();
                return redirect()->route('tutor.paket.materi.create',$id)->with('error', true);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('tutor.paket.materi.create', $id)->with('error-message', $e->getMessage());
        }
    }

    public function restructureMateri($materis){
        $arrayMateri = [];
        foreach ($materis as $key => $materi) {
            $arrayMateri[$materi->kode_materi]['kode_materi'] = $materi->kode_materi;
            $arrayMateri[$materi->kode_materi]['nama_materi'] = $materi->nama_materi;
            $arrayMateri[$materi->kode_materi]['submateri'][$materi->kode_submateri]['kode_submateri'] = $materi->kode_submateri;
            $arrayMateri[$materi->kode_materi]['submateri'][$materi->kode_submateri]['nama_submateri'] = $materi->nama_submateri;
            $arrayMateri[$materi->kode_materi]['submateri'][$materi->kode_submateri]['id'] = $materi->id;

            $arrayMateri[$materi->kode_materi]['submateri'] = array_map(function($array) {
                return (object) $array;
            }, $arrayMateri[$materi->kode_materi]['submateri']);
        }
        $arrayMateri = array_map(function($array) {
            return (object) $array;
        }, $arrayMateri);

        return $arrayMateri;
    }

    public function materi(Request $request){

        try {
            $query  = ClassContent::select('id','subdomain_id','kode_materi','nama_materi','kode_submateri','nama_submateri');
            if($request->filled('subdomain')){
                $query->where('subdomain_id', $request->subdomain);
            }
            if($request->filled('domain')){
                $query->whereHas('subdomain', function($q) use($request){
                    $q->where('domain_code', $request->domain);
                });
            }
            return $this->restructureMateri($query->get());

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

<?php

namespace App\Http\Controllers;

use App\Models\ClassContent;
use App\Models\PaketContent;
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
        $materi = ClassContent::where('kode_materi',14)->get();
        return view('livewire.pages.tutor.paket-form', [
            'materis' => $materi,
            'id' => $id,
        ]);
    }

    public function store(Request $request, $id){
        $content_id = $request->content_id;
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
        try {
            DB::beginTransaction();
            $paketContent = new PaketContent();
            if($paketContent->insert($data)){
                DB::commit();
                return redirect()->route('tutor.paket.materi')->with('message', 'materi berhasil ditambahkan');
            }
            else {
                DB::rollBack();
                return redirect()->route('tutor.paket.materi')->with('error', true);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('tutor.paket.materi')->with('error-message', $e->getMessage());
        }
    }

}

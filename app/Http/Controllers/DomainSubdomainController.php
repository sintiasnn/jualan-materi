<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Domain;
use App\Models\RefBidangList;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DomainSubdomainController extends Controller
{
    public function index(){
        return view('livewire.pages.tutor.domain');
    }

    public function storeDomain(Request $request){
        try {
            DB::beginTransaction();
            $classContent = new Domain($request->all());
            if($classContent->saveOrFail()){
                DB::commit();
                return response()->json(['message'=> 'Domain berhasil ditambahkan', 'success' => true]);
            }
            else {
                DB::rollBack();
                return response()->json(['message'=> 'Materi gagal ditambahkan', 'success' => false]);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message'=> $e->getMessage(), 'success' => false]);
        }
    }

    public function deleteDomain($id){
        try {
            Domain::find($id)->delete();
            return response()->json(['message'=> 'Domain berhasil dihapus', 'success' => true]);
        } catch (\Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }
}

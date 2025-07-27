<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Domain;
use App\Models\RefBidangList;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DomainSubdomainController extends Controller
{
    public function index(){
        return view('livewire.pages.tutor.domain');
    }

    public function storeDomain(Request $request){
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'code' => 'required|unique:domain,code',
                'keterangan' => 'required',
            ], [
                'code.required' => 'Kode tidak boleh kosong',
                'code.unique' => 'Kode sudah terdaftar',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
            ]);
            $domain = new Domain($request->all());
            if($validate && $domain->saveOrFail()){
                DB::commit();
                return response()->json(['message'=> 'Domain berhasil ditambahkan', 'success' => true]);
            }
            else {
                DB::rollBack();
                return response()->json(['message'=> 'Domain gagal ditambahkan', 'success' => false]);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message'=> $e->getMessage(), 'success' => false]);
        }
    }

    public function storeSubdomain(Request $request){
        try {
            DB::beginTransaction();
            $validate = $request->validate([
                'domain_code' => 'required',
                'code' => 'required|unique:domain,code',
                'keterangan' => 'required',
            ], [
                'domain_code.required' => 'Domain tidak boleh kosong',
                'code.required' => 'Kode subdomain tidak boleh kosong',
                'keterangan.required' => 'Keterangan tidak boleh kosong',
            ]);
            $subdomain = new Subdomain($request->all());
            if($validate && $subdomain->saveOrFail()){
                DB::commit();
                return response()->json(['message'=> 'Subdomain berhasil ditambahkan', 'success' => true]);
            }
            else {
                DB::rollBack();
                return response()->json(['message'=> 'Subdomain gagal ditambahkan', 'success' => false]);
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

<?php

namespace App\Http\Controllers;

use App\Livewire\Pages\Tutor\Materi;
use App\Models\ClassContent;
use App\Models\Classes;
use App\Models\Domain;
use App\Models\RefBidangList;
use App\Models\Subdomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{

    public function index(){
        return view('livewire.pages.tutor.materi');
    }

    public function create(){
        $subdomains = Subdomain::all();
        $domains = Domain::all();
        return view('livewire.pages.tutor.components.materi-form', compact( 'subdomains', 'domains'));
    }

    public function store(Request $request){
        try {
            DB::beginTransaction();
            $classContent = new ClassContent($request->request->all());
            if($classContent->saveOrFail()){
                DB::commit();
                return redirect()->route('materi.index')->with('message', 'materi berhasil ditambahkan');
            }
            else {
                DB::rollBack();
                return redirect()->route('materi.create')->with('error', true);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('materi.create')->with('error-message', $e->getMessage());
        }
    }

    public function show($id){
        $content = ClassContent::find($id);
        $content->viewOnly = true;
        return view('livewire.pages.tutor.components.materi-show', [
            'content' => $content,
        ]);
    }

    public function edit($id){
        $content = ClassContent::find($id);
        $subdomains = Subdomain::all();
        $domains = Domain::all();
        $content->viewOnly = false;
        $content->editMode = true;
        return view('livewire.pages.tutor.components.materi-form', [
            'content' => $content,
            'subdomains' => $subdomains,
            'domains' => $domains,
            'editMode' => $content->editMode,

        ]);
    }

    public function update(Request $request, $id){
        try {
            DB::beginTransaction();
            $classContent = ClassContent::find($id)->fill($request->request->all());
            if($classContent->saveOrFail()){
                DB::commit();
                return redirect()->route('materi.index')->with('message', 'materi berhasil diperbarui');
            }
            else {
                DB::rollBack();
                return redirect()->route('materi.edit',$id)->with('error', true);
            }
        } catch (\Exception $e){
            DB::rollBack();
            return redirect()->route('materi.edit', $id)->with('error-message', $e->getMessage());
        }
    }

    public function destroy($id){
        try {
            ClassContent::find($id)->delete();
            return response()->json(['message'=> 'Materi berhasil dihapus', 'success' => true]);
        } catch (\Exception $exception){
            return response($exception->getMessage(), 500);
        }
    }

    public function getSubdomain($domainCode){
        return Subdomain::select('id', 'domain_code','code', 'keterangan')
            ->where('domain_code', $domainCode)->get();
    }

}

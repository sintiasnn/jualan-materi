<?php

namespace App\Http\Controllers;

use App\Models\ClassContent;
use App\Models\Domain;
use App\Models\Materi;
use App\Models\Subdomain;
use App\Models\Submateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MateriController extends Controller
{

    public function index(){
        return view('livewire.pages.tutor.materi');
    }

    public function create(){
        return view('livewire.pages.tutor.materi-create');
    }

    public function store(Request $request){
        $materiItem = [
            'subdomain_id' => $request->subdomain_id,
            'kode_materi' => $request->kode_materi,
            'nama_materi' => $request->nama_materi,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
        ];

        try {
            DB::beginTransaction();
            $materi = Materi::create($materiItem);

            foreach(range(1, $request->submateri_count) as $idx){
                $submateriItem[$idx] = [
                    'materi_id' => $materi->id,
                    'kode_submateri' => $request->kode_submateri[$idx],
                    'nama_submateri' => $request->nama_submateri[$idx],
                    'deskripsi' => $request->deskripsi[$idx],
                ];
            }

            if($materi->saveOrFail()){
                Submateri::insert($submateriItem);
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
        $content = Materi::find($id);
        $content->submateri = Submateri::where('materi_id', $id)->get();
        $content->viewOnly = true;
        return view('livewire.pages.tutor.components.materi-show', [
            'content' => $content,
        ]);
    }

    public function edit($id){
        $content = Materi::find($id);
        $subdomains = Subdomain::all();
        $domains = Domain::all();
        $content->viewOnly = false;
        $content->editMode = true;
        return view('livewire.pages.tutor.materi-create', [
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

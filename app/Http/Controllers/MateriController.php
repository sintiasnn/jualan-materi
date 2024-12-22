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
        $classes = Classes::all();
        $bidang = RefBidangList::all();
        $subdomains = Subdomain::all();
        $domains = Domain::all();
        return view('livewire.pages.tutor.components.materi-form', compact('classes', 'bidang', 'subdomains', 'domains'));
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
        $classes = Classes::all();
        $bidang = RefBidangList::all();
        $content = ClassContent::find($id);
        $content->viewOnly = true;
        return view('livewire.pages.tutor.components.materi-show', [
            'classes' => $classes,
            'bidang' => $bidang,
            'content' => $content,
        ]);
    }

    public function edit($id){
        $classes = Classes::all();
        $bidang = RefBidangList::all();
        $content = ClassContent::find($id);
        $content->viewOnly = false;
        return view('livewire.pages.tutor.components.materi-form', [
            'classes' => $classes,
            'bidang' => $bidang,
            'content' => $content
        ]);
    }

    public function update(){

    }

    public function destroy(){


    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Illuminate\Support\Facades\DB;
use App\Berita;

use Auth;

use App\Kategori;

class BeritaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $berita = new Berita;
        $beritas = $berita->all();
        $i = 1;
        return view('berita.index', compact('beritas', 'i'));
    }

    public function create() {
        return view('berita.create');
    }

    public function store(Request $request, $id=0) {
        // Validasi data yang masuk
        $berita = new Berita;
        
        $validatedData = Validator::make($request->all(),   [
            'judul' => ['required', 'string', 'max:100'],
            'kategori_id' => ['required'],
            'foto' => ['mimes:jpeg,jpg,png']
        ]);
        
        if($id == 0) {
            $berita = new Berita;
            DB::table('kategori')->where('id', $request->kategori_id)->increment('jml');
        } else {
            $berita = Berita::findOrFail($id);
        }
        //Apabila Error maka halaman akan redirect ke halaman sebelumnya
        if($validatedData->fails()) {
            Session::flash('error', $validatedData->messages()->first());
            return redirect()
                    ->back()
                    ->withErrors($validatedData)
                    ->withInput();
        }
        // Proses save
        
        $berita->user_id = Auth::user()->id;
        $berita->kategori_id = $request->kategori_id;
        $berita->judul = $request->judul;
        $berita->deskripsi = $request->deskripsi;
        
        // Image File Handler
        if($request->hasFile('foto')) {
            $image_file = $request->file('foto');
            $image_name = 'berita'.time().".jpg";
            $uploaded = $image_file->move(public_path('uploads/images/berita/'),$image_name);
            if($uploaded) {
                $berita->foto = $image_name;
            } else {
                return redirect()->back()->with('fail-msg','failed to upload image');
            }
        }
        // Apabila sukses di save
        if($berita->save()) {
            // return redirect()->json($berita);
            return redirect('admin/berita')->with('success-msg', 'Data berhasil disimpan');
        }
    }

    public function view($id) {
        $berita = Berita::findOrFail($id);
        return response()->json($berita);
    }

    public function edit($id) {
        $berita = Berita::findOrFail($id);
        return view('berita.edit', compact('berita'));
    }

    public function show($id) {
        $berita = Berita::findOrFail($id);
        return view('berita.view', compact('berita'));
    }

    public function delete($id) {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        return response()->json($berita);
    }
}

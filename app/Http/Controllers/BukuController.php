<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin')->except(['index']);
    }

    public function index()
    {
        $buku = Buku::with('kategori')->get();
        return view('buku.index', compact('buku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'       => 'required|string|max:20|unique:buku,kode',
            'judul'      => 'required|string|max:150',
            'pengarang'  => 'required|string|max:100',
            'idkategori' => 'required',
        ]);

        Buku::create($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'kode'       => 'required|string|max:20|unique:buku,kode,' . $buku->idbuku . ',idbuku',
            'judul'      => 'required|string|max:150',
            'pengarang'  => 'required|string|max:100',
            'idkategori' => 'required',
        ]);

        $buku->update($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus');
    }
}
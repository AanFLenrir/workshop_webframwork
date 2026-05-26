<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalBuku = Buku::count();
        $totalKategori = Kategori::count();
        $totalUser = User::count();

        return view('home', compact(
            'totalBuku',
            'totalKategori',
            'totalUser'
        ));
    }
}
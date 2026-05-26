<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WilayahController extends Controller
{
    private $base = 'https://emsifa.github.io/api-wilayah-indonesia/api';

    public function provinsi()
    {
        $response = Http::get($this->base . '/provinces.json');
        return response()->json($response->json());
    }

    public function kota($provinsi_id)
    {
        $response = Http::get($this->base . '/regencies/' . $provinsi_id . '.json');
        return response()->json($response->json());
    }

    public function kecamatan($kota_id)
    {
        $response = Http::get($this->base . '/districts/' . $kota_id . '.json');
        return response()->json($response->json());
    }

    public function kelurahan($kecamatan_id)
    {
        $response = Http::get($this->base . '/villages/' . $kecamatan_id . '.json');
        return response()->json($response->json());
    }
}
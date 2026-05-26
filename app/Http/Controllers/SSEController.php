<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SSEController extends Controller
{
    public function semua(Request $request)
    {
        $data = Cache::get('antrian_state', [
            'sedang_dipanggil' => null,
            'antrian'          => [],
            'terlambat'        => [],
        ]);
        return response()->json($data);
    }

    public function poli(Request $request, $kode)
    {
        $data = Cache::get('antrian_state_' . $kode, [
            'sedang_dipanggil' => null,
            'antrian'          => [],
            'terlambat'        => [],
        ]);
        return response()->json($data);
    }
}

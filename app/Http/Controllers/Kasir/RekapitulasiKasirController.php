<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;

class RekapitulasiKasirController extends Controller
{
    public function index()
    {
        return view('kasir.rekapitulasi');
    }
}

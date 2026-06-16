<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthKasirController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN LOGIN
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('kasir.login');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES LOGIN
    |--------------------------------------------------------------------------
    */

    public function processLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | LOGIN ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            $request->username === 'admin' &&
            $request->password === 'admin'
        ) {

            session([
                'login' => true,
                'user_id' => $user->id,
                'role' => $user->role,
                'username' => $user->name,
            ]);

            return redirect()->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN KASIR
        |--------------------------------------------------------------------------
        */

        if (
            $request->username === 'kasir' &&
            $request->password === 'kasir'
        ) {

            session([
                'login' => true,
                'role' => 'kasir',
                'username' => $request->username,

                // MODAL AWAL AKAN MUNCUL POPUP
                'modal_awal' => null,
            ]);

            return redirect()->route('kasir.transaksi');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN GAGAL
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'error',
            'USERNAME ATAU PASSWORD SALAH'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN MODAL AWAL
    |--------------------------------------------------------------------------
    */

    public function storeModalAwal(Request $request)
    {
        $request->validate([
            'modal_awal' => 'required',
        ]);

        $modalAwal = preg_replace(
            '/\D/',
            '',
            $request->modal_awal
        );

        session([
            'modal_awal' => $modalAwal,
        ]);

        return redirect()->route('kasir.transaksi');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }
}

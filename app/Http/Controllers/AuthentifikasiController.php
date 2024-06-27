<?php

namespace App\Http\Controllers;

use App\Models\ModelToken;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;


class AuthentifikasiController extends Controller
{
    public function login(Request $request) {
        if ($request->has('login')) {
            $this->validate($request,['username' => 'required', 'password' => 'required']);
            $response = Http::asForm()->post(config('app.data').'/api/api-login.php', [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            $respon = json_decode($response->body());
            if ($response->successful()) {
                if ($respon->status == 'success') {
                    $token = new ModelToken();
                    $token->token = $respon->token;
                    $token->save();
                    Session::put('data', $respon);
                    Alert::success($respon->status,$respon->message);
                    return redirect('dashboard');
                } else {
                    Alert::error($respon->status,$respon->message);
                    return view('login');
                }
            } else {
                Alert::error('error' ,'Server tidak merespon');
                return view('login');
            }
        }
        return view('login');
    }

    public function register(Request $request) {
        if ($request->has('register')) {
            $this->validate($request, [
                'nama'          => 'required|min:5',
                'username'      => 'required|min:5|different:nama',
                'password'      => 'required|same:repassword|min:6',
                'repassword'    => 'required'
            ], [
                'nama.required'         => 'Nama harus diisi.',
                'nama.min'              => 'Nama harus memiliki minimal :min karakter.',
                'username.required'     => 'Username harus diisi.',
                'username.min'          => 'Username harus memiliki minimal :min karakter.',
                'username.different'    => 'Username harus berbeda dengan nama.',
                'password.required'     => 'Password harus diisi.',
                'password.same'         => 'Password dan konfirmasi password harus sama.',
                'password.min'          => 'Password harus memiliki minimal :min karakter.',
                'repassword.required'   => 'Konfirmasi password harus diisi.'
            ]);
            $response = Http::asForm()->post(config('app.data').'/api/api-register.php', [
                'username' => $request->username,
                'password' => $request->repassword,
                'nama' => $request->nama,
            ]);
            $respon = json_decode($response->body());
            if ($response->successful()) {
                if ($respon->status == 'success') {
                    Alert::success($respon->status,$respon->message);
                    return redirect(route('login'));
                } else {
                    Alert::error($respon->status,$respon->message);
                    return view('register');
                }
            } else {
                Alert::error('error' ,'Server tidak merespon');
                return view('register');
            }

        }
        return view('register');
    }

    public function logout() {
        if (Session::has('data')) {
            ModelToken::where('token', '=', Session::get('data')->token)->delete();
            Session::flush();
            Cache::flush();
            Alert::success('Success', 'Berhasil Logout');
            return redirect(route('login'));
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Pastikan ini ada

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    protected function authenticated(Request $request, $user)
    {
        if ($user->role !== 'admin') {
            Auth::logout(); // Keluar jika bukan admin
            return redirect('/login')->with('error', 'Anda tidak memiliki akses.');
        }

        return redirect()->intended($this->redirectTo);
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}

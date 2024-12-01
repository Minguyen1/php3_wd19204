<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('login');
    }

    public function postLogin(Request $request){
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if(Auth::attempt($data)){
            if(Auth::user()->role == 'admin'){
                return redirect()->route('posts.index');
            }
            return redirect()->route('home');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác'])->withInput();
    }

    public function register(){
        return view('register');
    }

    public function postRegister(Request $request){
        $data = $request->validate([
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'password_confirm' => ['required', 'same:password'],
        ]);

        User::query()->create($data);

        return redirect()->route('login')->with('message', 'Đăng ký thành công');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}

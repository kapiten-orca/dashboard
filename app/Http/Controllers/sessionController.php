<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class sessionController extends Controller
{
    function index()
    {
        return view("auth.login");
    }
    function login(Request $request)
    {
        Session::flash('email', $request->email);
        $request->validate([
            'email'=>'required',
            'password'=>'required',

        ],[
            'email.required'=>'Email wajib diisi!',
            'password.required'=>'Password wajib diisi!',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($infologin)){
            return Redirect('index')->with('success', 'Berhasil login');
        }else{
            return Redirect('sesi')-> withErrors('Email dan Password yang dimasukan tidak valid');
        }
    }
    function logout(){
        Auth::logout();
        return Redirect('sesi')->with('success', 'Berhasil logout');
    }

    function register(){
        return view('auth/register');
    }

    function create(Request $request){
        Session::flash('name', $request->name);
        Session::flash('email', $request->email);
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',

        ],[
            'name.required'=>'Nama wajib diisi!',
            'email.required'=>'Email wajib diisi!',
            'email.email'=>'Silahkan isi dengan email yang valid!',
            'email.unique'=>'Email sudah digunakan!',
            'password.required'=>'Password wajib diisi!',
            'password.min'=>'Password minimal 8 karakter!',
        ]);

        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ];

        User::create($data);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($infologin)){
            return redirect('index')->with('success', Auth::user()->name . ' Berhasil login'); 
        }else{
            return redirect('sesi')-> withErrors('Email dan Password yang dimasukan tidak valid');
        }
    }

}

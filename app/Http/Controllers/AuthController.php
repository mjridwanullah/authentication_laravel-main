<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\facades\Auth;
use Illuminate\Support\facades\hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\View\View;
use session; 


class AuthController extends Controller
{
    public function index(){
        if (auth::check())
        {
            return view('student.index');
        }
        else
        {
            return view('auth.login');
        }
    }
    
    public function postlogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(auth::attempt($data)){
            return redirect('student');
        }
        else
        {
            return back();
        }
    }

    public function regis()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        User::create([
                    'name' => $request->name,
        'email' => $request->email,
        'password' => hash::make ($request->password),
        'remember_token' => Str::random(60),
        ]);


        return redirect ('login');

    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
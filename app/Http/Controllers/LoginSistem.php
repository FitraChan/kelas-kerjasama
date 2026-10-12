<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\LoginMod;
use App\Models\User;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Http;


class LoginSistem extends Controller
{
    //

   public function index()
   {
       return view('login_custom');
   }

    public function proses_login(Request $request)
    {
        
          $request->validate([
        'g-recaptcha-response' => 'required'
    ], [
        'g-recaptcha-response.required' => 'Silakan centang reCAPTCHA.'
    ]);
        
        
        
       $response = Http::asForm()->post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'secret' => '6LfIL2UsAAAAANhNo1Zh4i4RFIWESsKp8x8pOiwB',
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]
    )->json();

    if (!($response['success'] ?? false)) {
        return back()->withErrors(['captcha' => 'Captcha tidak valid.']);
    }
        

        $credentials = [
        'email' => $request->email,
        'password' => $request->password,
        'level' => 1   // hanya user level 1 yang boleh login
    ];


       if (Auth::attempt($credentials)) {
                return redirect()->to('/post');
               // session()->flash('error', 'email and password true.');
        }else{
             return redirect()->back();
              	 //$try = LoginMod::all();

    	 //dd($try);
        }
    }

    public function loginApi(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ],'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();
            if ( ! Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ],'Authenticated');
        } catch (\Exception $error) {
          return ResponseFormatter::error([
    'message' => 'Something went wrong',
    'error' => $error->getMessage(),  // <-- ambil pesan error
    'line' => $error->getLine(),
    'file' => $error->getFile(),
],'Authentication Failed', 500);
        }
    }


    public function logout(Request $request) {
    //    $request->session()->flush();
    //    Auth::logout();
    //    return Redirect('/');

       Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
   }


   	public function daftar()
   {

       return view('livewire.daftar');

   }


    public function registrasi(Request $request)
    {

        $password = Hash::make($request->password);
        $email = $request->email;
        $nama = $request->name;

        LoginMod::create(['name' => $nama ,'email' => $email,'password' => $password]);
        return redirect()->to('/post');
    }

     public function daftar_user(Request $request)
    {
            $user = User::get();

        return  $user;
    }
}

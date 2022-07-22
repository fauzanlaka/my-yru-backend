<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
// use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    //ตรวจสอบประเภท username ว่าเป็นชื่อผู้ใช้หรืออีเมลย์
    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    // public function login(Request $request)
    // {
    //     $rules = [
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ];
    //     $messages = [
    //         'username.required' => 'กรุณากรอกอีเมลหรือชื่อผู้ใช้งาน',
    //         'password.required' => 'กรุณากรอกรหัสผ่าน',
    //     ];
    //     $request->validate($rules, $messages);
    //     $login = $this->findUsername();
    //     $user = User::where($login, $request->username)->first();
    //     if(!$user){
    //         throw ValidationException::withMessages([
    //             'username' => 'ไม่พบอีเมลย์หรือชื่อผู้ใช้ดังกล่าว'
    //         ]);
    //     }else if(!Hash::check($request->password, $user->password)){
    //         throw ValidationException::withMessages([
    //             'password' => 'รหัสผ่านไม่ถูกต้อง'
    //         ]);
    //     }else{
    //         $agent = $request->userAgent();
    //         $user->tokens()->where('name', $agent)->delete();
    //         $token = $user->createAuthToken($agent)->plainTextToken;
    //         $cookie = cookie('XSRF-TOKEN', $token, 60*24*365);
    //         return response([
    //             'message' => 'success',
    //             'token' => $token,
    //         ])->withCookie($cookie);
    //     }
    //     // return $request->all();
    // }

    // public function logout(Request $request)
    // {
    //     $user = auth()->user();
    //     $agent = $request->userAgent();
    //     $user->tokens()->where('name', $agent)->delete();
    //     $cookie = Cookie::forget('XSRF-TOKEN');
    //     return response([
    //         'message' => 'loged out',
    //     ])->withCookie($cookie);
    // }

    public function login(Request $request)
    {
        $login       = $this->findUsername();
        $rules = [
            $login => 'required|string',
            'password' => 'required|string',
        ];
        $messages = [
            $login.'.required' => 'กรุณากรอกชื่อผู้ใช้ หรือ อีเมล',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ];
        $request->validate($rules, $messages);
        // $credentials = $request->validate([
        //     $login     => 'required|string',
        //     'password' => 'required|string',
        // ]);

        $user = User::where($login, $request->username)->first();
        if(!$user){
            throw ValidationException::withMessages([
                'username' => 'ไม่พบอีเมลย์หรือชื่อผู้ใช้ดังกล่าว'
            ]);
        }else if(!Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'password' => 'รหัสผ่านไม่ถูกต้อง'
            ]);
        }else{
            Auth::attempt([$login => $request->$login, 'password' => $request->password]);  
            return response([
                'messages' => 'loged in',
                'passkey'  => 'eyJpdiI6IlhqeWNCZjF3MWhJenRNY0pOcE1rMEE9PS',
                // 'username' => $request->$login,
            ]); 
        }
    }

    public function logout()
    {
        Auth::logout();

        return response([
            'message' => 'Loged Out',
        ]);
    }

}
